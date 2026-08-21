<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusBmn extends Model
{
    protected $table = 'status_bmn';
    protected $fillable = ['id', 'status'];
    public $incrementing = false;
}
