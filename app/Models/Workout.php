<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{

    use HasFactory;

    protected $fillable = [
        'workout_name',
        'description',
        'duration',
        'image',
        'difficulty',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_workout', 'workout_id', 'category_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_workouts', 'workout_id', 'user_id')
            ->withPivot(['remaining_time', 'is_paused', 'completed', 'started_at', 'paused_at'])
            ->withTimestamps();
    }
}
