<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Point\GeneratedArea;
use App\Observers\UserObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;
 
    public function canAccessPanel(Panel $panel): bool
    {
        return match($panel->getId()){
            'admin' => $this->hasRole('admin'),
            'user' => true,
            default => false
        };
    }

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeOnlyTeamMembers($query){
        return $query
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->where('model_has_roles.role_id', config('auth.roles.team_member'));
    }

    public function scopeOnlyUsers($query){
        return $query
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->whereNull('model_has_roles.role_id');
    }

    public function referedRequirements(){
        return $this->hasMany(Requirement::class, 'referer_id');
    }

    public function requirements(){
        return $this->hasMany(Requirement::class, 'owner_id');
    }

    public function projects(){
        return $this->hasMany(Project::class, 'owner_id');
    }

    public function points(){
        return $this->hasMany(Point::class, 'owner_id');
    }

    // public function getCurrentPointsAttribute(){
    //     return $this
    //     $this->points()
    //         ->where(
    //             fn ($q) => $q->where('generated_area', GeneratedArea::SIGNUP)->
    //         )
    // }
}
