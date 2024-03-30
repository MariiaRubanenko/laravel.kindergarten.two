<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }

    public function child_profiles()
    {

        return $this->hasMany(Child_profile::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
