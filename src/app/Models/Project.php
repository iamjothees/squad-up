<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'started_at' => 'datetime',
        'expected_completed_at' => 'datetime',
        'completed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'committed_budget' => MoneyCast::class,
    ];
}
