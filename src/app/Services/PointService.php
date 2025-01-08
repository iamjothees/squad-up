<?php

namespace App\Services;

use App\DTOs\PointGenerationDTO;
use App\DTOs\PointRedeemDTO;
use App\DTOs\UserDTO;
use App\Enums\Point\GenerationArea;
use App\Interfaces\PointGeneratorDTO;
use App\Jobs\SyncUserPoints;
use App\Models\PointGeneration;
use App\Models\PointRedeem;
use App\Models\User;
use App\PointConfig;
use App\Settings\GeneralSettings;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PointService
{
    public function __construct( private PointConfig $pointsConfig, private GeneralSettings $generalSettings )
    {
        //
    }

    public function generate( GenerationArea $generationArea, ?PointGeneratorDTO $pointGeneratorDTO = null ): PointGenerationDTO {
        $points = $this->getPointsToGenerate( generationArea: $generationArea, pointGeneratorDTO: $pointGeneratorDTO );
        
        $pointGeneration = PointGeneration::create([
            'points' => $points,
            'owner_id' => $pointGeneratorDTO?->getPointOwnerId(),
            'generation_area' => $generationArea,
            'generator_type' => $pointGeneratorDTO?->toModel()->getMorphClass(),
            'generator_id' =>  $pointGeneratorDTO?->id,
            'calc_config' => $this->generalSettings->points_config
        ]);
        return PointGenerationDTO::fromModel($pointGeneration);
    }

    public function credit( PointGenerationDTO $pointGenerationDTO ): void{
        $validator = Validator::make($pointGenerationDTO->toArray(), [
            'credited_at' => [
                fn ($attribute, $value, $fail) => (!is_null($value)) ? $fail("{$attribute} must be null") : null 
            ]
        ]);

        if ($validator->fails()) throw new Exception("Point already credited");
        
        $pointGenerationDTO->toModel()->credited_at = now();
        $pointGenerationDTO->toModel()->save();


        $userDTO = UserDTO::fromModel( User::find($pointGenerationDTO->owner_id) );
        SyncUserPoints::dispatch( userDTOs: collect()->push( $userDTO ) );
    }

    public function destroy( PointGenerationDTO $pointGenerationDTO ): void{
        $validator = Validator::make($pointGenerationDTO->toArray(), [
            'credited_at' => [
                fn ($attribute, $value, $fail) => (!is_null($value)) ? $fail("{$attribute} must not be null") : null 
            ]
        ]);

        if ($validator->fails()) throw new Exception("Cannot destroy! Point Already credited");

        $pointGenerationDTO->toModel()->delete();
    }

    public function requestForRedeem( UserDTO $userDTO, int $points ): PointRedeemDTO{

        // Validations for first redeem
        if ( 
            !$userDTO->toModel()->redeems  // is first request
            && !$userDTO->toModel()->points()->credited()->whereNot( 'generation_area', GenerationArea::SIGNUP)->exists() // does have any other point generation other thsn signup
        ) throw new Exception("First redeem request validations not meet up");

        // Other validations
        $validator = Validator::make(
            data: [
                'user' => $userDTO->toArray(),
                'points' => $points
            ], 
            rules:[
                'points' => [
                    'lte:user.current_points', "gte:{$this->generalSettings->least_redeemable_point}",
                    fn ($attribute, $value, $fail) => (($value % $this->generalSettings->points_redeem_interval) !== 0) ? $fail("{$attribute} must be multiple of {$this->generalSettings->points_redeem_interval}") : null
                ], 
            ],
            attributes: [
                'user.current_points' => 'User points',
                'points' => 'Requested Points'
            ]
        );

        if ($validator->fails()) throw new Exception($validator->errors()->first());

        $pointRedeem = DB::transaction(
            function () use ($userDTO, $points){
                $pointRedeem = PointRedeem::create([
                    'owner_id' => $userDTO->id,
                    'points' => $points,            
                ]);

                $userDTO->current_points -= $points;
                $userDTO->toModel()->save();

                return $pointRedeem;
            }
        );


        return PointRedeemDTO::fromModel($pointRedeem);
    }

    public function redeem( PointRedeemDTO $pointRedeemDTO ): void {
        $validator = Validator::make($pointRedeemDTO->toArray(), [
            'redeemed_at' => ['null']
        ]);

        if ($validator->fails()) throw new Exception("Point already redeemed");

        $pointRedeemDTO->toModel()->redeemed_at = now();
        $pointRedeemDTO->toModel()->save();
    }

    public function calcPointsInAmount(float $amount, int $participationLevel = 1): float{
        return $amount * ( $this->pointsConfig->getPercent( amount: $amount, participationLevel: $participationLevel ) / 100 );
    }

    public function getUserCurrentPoints(UserDTO $userDTO): float{
        $user = $userDTO->toModel();
        $creditedPoints = (int) $user->points()->credited()->sum('points');
        $redeemedPoints = (int) $user->redeems()->sum('points');
        
        return $creditedPoints - $redeemedPoints;
    }

    private function getPointsToGenerate( GenerationArea $generationArea, ?PointGeneratorDTO $pointGeneratorDTO = null ): int {
        return match($pointGeneratorDTO){
            null => $generationArea->getPointsToGenerateInAmount(),
            default => $pointGeneratorDTO->getPointsToGenerateInAmount()
        } * $this->generalSettings->point_per_amount;
    }
}
