<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'zoom_room'])]
class ZoomRoom extends Model
{
    public $incrementing = false;

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_zoom_room');
    }
}
