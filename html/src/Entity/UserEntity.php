<?php
declare(strict_types=1);

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class UserEntity extends Model{
    protected $table = 'users';
    protected $primaryKey = 'uuid';

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'uuid',
        'name',
        'login',
        'password',
        'email',
        'phone'
    ];

    protected $casts = [
        'uuid' => 'string'
    ];

    public $timestamps = false;

    public $incrementing = false;

}