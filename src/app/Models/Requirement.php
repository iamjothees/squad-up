<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enum\RequirementStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requirement extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'required_at' => 'datetime',
        'expecting_budget' => MoneyCast::class,
        'status' => RequirementStatus::class
    ];

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function owner(){
        return $this->belongsTo(User::class);
    }

    public function referer(){
        return $this->belongsTo(User::class);
    }

    public function admin(){
        return $this->belongsTo(User::class);
    }

    public function canBeEditedBy(User $user): bool{
        return $this->status === RequirementStatus::PENDING || $user->id === $this->admin_id;
    }
    

}
