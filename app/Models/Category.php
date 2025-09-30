<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function workouts()
    {
        return $this->belongsToMany(Workout::class, 'category_workout', 'category_id', 'workout_id');
    }
}
