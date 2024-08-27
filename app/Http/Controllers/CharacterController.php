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
use Illuminate\Validation\ValidationException;

class CharacterController extends Controller {
    use ValidatesRequests;

    public function getCharacters(Request $request){
        try {
            $this->validate($request, [
                'name' => 'string|max:255|nullable',
                'status' => 'string|in:Alive,Dead,unknown|nullable',
                'species' => 'string|nullable',
                'gender' => 'string|in:Female,Male,Genderless,unknown|nullable',
                'per_page' => 'integer|min:1|max:50|nullable',
                'page' => 'integer|min:1|nullable',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Invalid query parameters', 'errors' => $e->errors()], 400);
        }

        $query = Character::query();
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request['name'] . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request['status']);
        }

        if ($request->has('species')) {
            $query->where('species', $request['species']);
        }

        if ($request->has('gender')) {
            $query->where('gender', $request['gender']);
        }

        $perPage = $request->query('per_page', 20);

        // Get the paginated results for the specified page (internally it uses the $page variable)
        $characters = $query->paginate($perPage);

        // Return the paginated and filtered results
        return response()->json($characters);
    }

    public function getCharacter(Request $request, $id){
        // $id already validated by Laravel in the route definition
        $character = Character::findOrFail($id);

        return response()->json($character);
    }
}
