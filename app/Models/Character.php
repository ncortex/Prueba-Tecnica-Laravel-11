<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model{
    //use HasFactory;

    public $timestamps = false;
    protected $table = 'characters';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'status',
        'species',
        'type',
        'gender',
        'origin_name',
        'origin_url',
        'location_name',
        'location_url',
        'image',
        'url',
        'episode',
        'created',
    ];

    protected $casts = [
        'created' => 'datetime',
        'episode' => 'array',
    ];

}
