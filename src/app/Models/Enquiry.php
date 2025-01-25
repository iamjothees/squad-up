<?php

namespace App\Models;

use App\Enums\EnquiryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => EnquiryStatus::class,
        'created_at' => 'datetime',
    ];

    public function enquirerByEmail()
    {
        return $this->belongsTo(User::class, 'enquirer_contact', 'email');
    }

    public function enquirerByPhone()
    {
        return $this->belongsTo(User::class, 'enquirer_contact', 'phone');
    }
}
