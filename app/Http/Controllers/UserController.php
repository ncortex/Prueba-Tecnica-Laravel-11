<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserController extends Controller {
    use ValidatesRequests;

    public function getFavorites(Request $request){
        $characters = Character::whereIn('id', $request->user()->favorites)->get();

        return response()->json($characters);
    }

    public function postFavorite(Request $request){
        $this->validate($request, [
            'id' => 'required|integer|exists:characters,id',
        ]);
        $user = $request->user();

        $userFavorites = $user->favorites;
        if(in_array($request->id, $userFavorites)){
            return response()->json(['message' => 'Character already in favorites']);
        }
        $userFavorites[] = $request->id;
        $user->favorites = $userFavorites;
        $user->save();

        return response()->json(['message' => 'Character added to favorites']);
    }

    public function deleteFavorite(Request $request){
        $this->validate($request, [
            'id' => 'required|integer|exists:characters,id',
        ]);

        $user = $request->user();
        $userFavorites = $user->favorites;
        if(!in_array($request->id, $userFavorites)){
            return response()->json(['message' => 'Character not in favorites']);
        }
        $userFavorites = array_diff($userFavorites, [$request->id]);
        $user->favorites = $userFavorites;
        $user->save();

        return response()->json(['message' => 'Character removed from favorites']);
    }


}
