<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// TODO: Plan
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'start_at' => 'datetime',
        'completion_at' => 'datetime',
        'deliver_at' => 'datetime',
        'committed_budget' => MoneyCast::class,
        'initial_payment' => MoneyCast::class,
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
