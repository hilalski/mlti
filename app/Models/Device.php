<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['id', 'id_type', 'year', 'id_source', 'brand', 'series', 'serial_number', 'id_status_bmn', 'id_condition', 'keterangan', 'id_user'])]
class Device extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'nip_lama');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'id_type');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class, 'id_condition');
    }

    public function source()
    {
        return $this->belongsTo(Source::class, 'id_source');
    }

    public function statusBmn()
    {
        return $this->belongsTo(StatusBmn::class, 'id_status_bmn');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'device_id', 'id');
    }

    public function activeReport()
    {
        return $this->reports()->whereIn('status', ['menunggu', 'diproses'])->latest()->first();
    }
}
