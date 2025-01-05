<?php

namespace App\DTOs;

use App\Interfaces\PointGeneratorDTO;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserDTO extends ModelDTO implements PointGeneratorDTO
{
    use InteractsWithModelDTO;

    private User $model;
    private string $modelType = User::class;

    public ?int $id;
    public string $name;
    public ?string $email;
    public ?string $phone;
    public string $password;
    public int $current_points;

    public function __construct()
    {
        //
    }

    public function getPointsToGenerateInAmount(): int{
        return $this->toModel()->getSignUpBonusInAmount();
    }

    public function getPointOwnerId(): int{
        return $this->toModel()->id;
    }

    protected function fill( array $data ): void{
        $data['id'] ??= null;
        $validator = Validator::make($data, [
            'id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required_if:phone,null', 'string', 'email', 'max:255', 'unique:users,email,' . $data['id']],
            'phone' => ['required_if:email,null', 'string', 'max:255', 'unique:users,phone,' . $data['id']],
            'password' => ['required', 'string', 'min:8'],
            'current_points' => ['nullable', 'numeric', 'min:0'],
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
    }
}
