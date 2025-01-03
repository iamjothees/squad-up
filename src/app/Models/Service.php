<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'status' => ServiceStatus::class,
    ];

    function scopeActive(){
        return $this->where('status', ServiceStatus::ACTIVE);
    }
}
