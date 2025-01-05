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

    public function admin(){
        return $this->belongsTo(User::class);
    }

    public function requirement(){
        return $this->hasOne(Requirement::class);
    }
}
