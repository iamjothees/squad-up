<?php

namespace App\DTOs;

use App\Models\PointRedeem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class PointRedeemDTO extends ModelDTO
{
    use InteractsWithModelDTO;

    private PointRedeem $model;
    private string $modelType = PointRedeem::class;

    public ?int $id;
    public int $owner_id;
    public int $points;
    public ?Carbon $redeemed_at;

    public function __construct()
    {
        //
    }

    protected function fill( array $data ): void{
        $validator = Validator::make($data, [
            'id' => ['nullable', 'exists:point_redeems,id'],
            'owner_id' => ['required', 'exists:users,id'],
            'points' => ['required', 'integer', 'min:0'],
            'redeemed_at' => ['nullable', 'date', 'before_or_equal:now'],
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $this->id = $data['id'] ?? null;
        $this->owner_id = $data['owner_id'];
        $this->points = $data['points'];
        $this->redeemed_at = $data['redeemed_at'] ?? null ? Carbon::parse($data['redeemed_at']) : null;
    }
}
