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

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function owner(){
        return $this->belongsTo(User::class);
    }

    public function referer(){
        return $this->hasOneThrough(User::class, Requirement::class, 'referer_id');
    }

    public function admin(){
        return $this->belongsTo(User::class);
    }

    public function requirement(){
        return $this->belongsTo(Requirement::class);
    }

    public function point(){
        return $this->hasOneThrough(Point::class, Requirement::class);
    }
}
