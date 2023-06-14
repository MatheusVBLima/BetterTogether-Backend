<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'experience_name'
    ];

    public function users(){
        return $this -> belongsToMany(User::class);
    }

    public function projects(){
        return $this -> belongsToMany(Project::class);
    }
}
