<?php

namespace App\Service;

use App\Models\User;

class UserService
{
    public function __construct()
    {
        //
    }

    public function generateReferalPartnerCode(User $user){
        $id = str_pad($user->id, 3, '0', STR_PAD_LEFT);

        $suffix = $user->phone 
                    ? str($user->phone)->take(-4)
                    : rand(1000, 9999);

        return "RPC-{$id}-{$suffix}";
    }
}
