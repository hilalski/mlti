<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorService extends Model
{
    protected $table = 'vendor_services';
    protected $fillable = ['id', 'vendor_service'];
    public $incrementing = false;
}
