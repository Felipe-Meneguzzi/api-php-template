<?php
declare(strict_types=1);

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class UserEntity extends Model{
    protected $table = 'users';
    protected $hidden = [
        'password',
        'id'
    ];

    protected $fillable = [
        'uuid',
        'name',
        'login',
        'password',
        'email',
        'phone'
    ];

    public $timestamps = false;

}