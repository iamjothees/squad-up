<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Enums\Point\GenerationArea;
use App\Models\User;

class UserService
{
    public function __construct( protected PointService $pointService )
    {
        //
    }

    public function creditSignupBonusPoints(UserDTO $userDTO): void{
        $this->pointService->credit( 
            pointGenerationDTO: $this->pointService->generate( generationArea: GenerationArea::SIGNUP, pointGeneratorDTO: $userDTO ) 
        );
    }

    public function verifyPhone(UserDTO $userDTO): void{
        $user = $userDTO->toModel();
        $user->phone_verified_at = now();
        $user->push();
    }

    public function getExpectingPoints(User $user): int{
        return $user->points()->nonCredited()->sum('points');
    }

    public function getCurrentPoints(UserDTO $userDTO): int{
        return $this->pointService->getUserCurrentPoints(userDTO: $userDTO);
    }

    public function generateReferalPartnerCode(User $user){
        $id = str_pad($user->id, 3, '0', STR_PAD_LEFT);

        $suffix = $user->phone 
                    ? str($user->phone)->take(-4)
                    : rand(1000, 9999);

        return "RPC-{$id}-{$suffix}";
    }

    public function syncCurrentPoints(UserDTO $userDTO): UserDTO{
        $userDTO->toModel()->current_points = $this->pointService->getUserCurrentPoints(userDTO: $userDTO);
        $userDTO->toModel()->push();
        return $userDTO;
    }
}
