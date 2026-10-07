<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['reported_by', 'location', 'category', 'description', 'status'])]
class GeneralComplaint extends Model
{
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by', 'nip_lama');
    }
}
