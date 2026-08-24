<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['id', 'ruang'])]
class Room extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;

    public function users()
    {
        return $this->hasMany(User::class, 'id_ruang', 'id');
    }

    public function devices()
    {
        return $this->hasMany(Device::class, 'id_user', 'id');
    }
}
