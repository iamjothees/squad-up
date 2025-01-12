<?php

namespace App\DTOs;

use App\Interfaces\PointGeneratorDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class UserDTO extends ModelDTO implements PointGeneratorDTO
{
    use InteractsWithModelDTO;

    private User $model;
    private string $modelType = User::class;

    public ?int $id;
    public string $name;
    public ?string $email;
    public ?Carbon $email_verified_at;
    public ?string $phone;
    public ?Carbon $phone_verified_at;
    public string $password;
    public int $current_points;

    public function __construct()
    {
        //
    }

    public static function fromModel(Model $model): UserDTO
    {
        $dto = new self();
        $dto->fill([
            ...$model->attributesToArray(), 
            'email_verified_at' => $model->email_verified_at,
            'phone_verified_at' => $model->phone_verified_at,
        ]);
        return $dto;
    }

    public function getPointsToGenerateInAmount(): float{
        return $this->toModel()->getSignUpBonusInAmount();
    }

    public function getPointOwnerId(): int{
        return $this->toModel()->id;
    }

    public function getIdentificationReference(): string{
        return $this->name;
    }

    protected function fill( array $data ): void{
        $data['id'] ??= null;
        $validator = Validator::make($data, [
            'id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required_if:phone,null', 'nullable', 'email', 'max:255', 'unique:users,email,' . $data['id']],
            'phone' => ['required_if:email,null', 'nullable', 'max:255', 'unique:users,phone,' . $data['id']],
            'password' => ['required', 'string', 'min:8'],
            'current_points' => ['nullable', 'numeric', 'min:0'],
            'email_verified_at' => ['nullable', 'date'],
            'phone_verified_at' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->password = $data['password'];
        $this->current_points = $data['current_points'] ?? 0;
        $this->email_verified_at = $data['email_verified_at'] ?? null;
        $this->phone_verified_at = $data['phone_verified_at'] ?? null;
    }
}
