<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['booking_code', 'requested_by', 'type', 'room_id', 'id_zoom_room', 'title', 'purpose', 'participants', 'live_streaming', 'admin_notes', 'starts_at', 'ends_at', 'status'])]
class Booking extends Model
{
    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'live_streaming' => 'boolean'];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by', 'nip_lama');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function zoomRoom()
    {
        return $this->belongsTo(ZoomRoom::class, 'id_zoom_room');
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking): void {
            if ($booking->type !== 'zoom' || $booking->booking_code) {
                return;
            }

            do {
                $booking->booking_code = 'ZMT-' . static::randomLetters(6);
            } while (static::where('booking_code', $booking->booking_code)->exists());
        });
    }

    private static function randomLetters(int $length): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';

        for ($index = 0; $index < $length; $index++) {
            $result .= $letters[random_int(0, strlen($letters) - 1)];
        }

        return $result;
    }
}
