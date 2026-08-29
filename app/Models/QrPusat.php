<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrPusat extends Model
{
    protected $table = 'qr_pusats';

    protected $fillable = [
        'qr_pusat',
        'id_user',
    ];
}
