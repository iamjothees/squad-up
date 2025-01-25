<?php

namespace App\DTOs;

use App\Models\Enquiry;
use App\DTOs\ModelDTO;
use App\DTOs\InteractsWithModelDTO;
use App\Enums\EnquiryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EnquiryDTO extends ModelDTO
{
    use InteractsWithModelDTO;

    private Enquiry $model;
    private string $modelType = Enquiry::class;
    
    public ?int $id;
    public string $enquirer_name;
    public string $enquirer_contact;
    public string $message;
    public ?EnquiryStatus $status;

    public function __construct()
    {
        //
    }

    public static function fromModel(Model $model): self{
        $data = $model->attributesToArray();
        $data['status'] = $model->status;
        $self = self::fromArray($data);
        return $self;
    }

    public static function fromApiRequest(array $data): self{
        $dto = new self();
        $formattedData = [];
        $formattedData['enquirer_name'] = $data['name'];
        $formattedData['enquirer_contact'] = $data['contact'];
        $formattedData['message'] = $data['message'];

        $dto->fill($formattedData);
        return $dto;
    }

    protected function fill(array $data): void{
        $Validator = Validator::make($data, [
            'id' => ['nullable', 'exists:enquiries,id'],
            'enquirer_name' => ['required', 'string', 'max:255'],
            'enquirer_contact' => ['required', 'string'],
            'message' => ['required', 'string'],
            'status' => ['nullable', Rule::enum(EnquiryStatus::class)],

        ]);

        if ($Validator->fails()) {
            throw new \Exception($Validator->errors()->first());
        }
        $data = $Validator->validated();

        $this->id = $data['id'] ?? null;
        $this->enquirer_name = $data['enquirer_name'];
        $this->enquirer_contact = $data['enquirer_contact'];
        $this->message = $data['message'];
        $this->status = $data['status'] ?? null;
    }
}
