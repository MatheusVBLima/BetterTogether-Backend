<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'user_id', 'contact', 'areasOfExperience'
    ];

    public function user(){
        return $this -> belongsTo(User::class);
    }

    public function experiences(){
        return $this -> belongsToMany(Experience::class);
    }

}
