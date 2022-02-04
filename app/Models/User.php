<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'gender',
        'position',
        'role',
        'password'
    ];

    //one user belongs to one team
    public function teams() {
        return $this->belongsToMany(Team::class);
    }


}
