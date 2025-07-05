<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'image',
        'location',
        'duration',
        'budget',
        'partners',
        'progress',
        'impact',
        'objectives'
    ];

    protected $casts = [
        'partners' => 'json',
        'impact' => 'json',
        'objectives' => 'json',
        'progress' => 'integer'
    ];

    protected $attributes = [
        'progress' => 0
    ];
}
