<?php
declare(strict_types=1);

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class UserEntity extends Model{
    protected $table = 'users';
    protected $guarded = [
        'id'
    ];
    protected $hidden = [
        'password'
    ];

    public $timestamps = false;
}