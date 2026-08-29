<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['device_id', 'id_ruang', 'reported_by', 'issue_type', 'description', 'status', 'technician_notes', 'handled_by', 'id_vendor', 'resolved_at'])]
class Report extends Model
{
    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_ruang', 'id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by', 'nip_lama');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'handled_by', 'nip_lama');
    }

    public function vendor()
    {
        return $this->belongsTo(VendorService::class, 'id_vendor');
    }
}
