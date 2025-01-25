<?php

namespace Database\Factories;

use App\Enums\EnquiryStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enquiry>
 */
class EnquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'enquirer_name' => fake()->name(),
            'enquirer_contact' => fake()->boolean() ? fake()->phoneNumber() : fake()->email(),
            'message' => fake()->text(),
            'status' => EnquiryStatus::PENDING,
            'created_at' => fake()->dateTimeBetween('-1 year', now())
        ];
    }

    function responded(): static
    {
        return $this->state([
            'status' => EnquiryStatus::RESPONDED,
        ]);
    }

    function cancelled(): static
    {
        return $this->state([
            'status' => EnquiryStatus::CANCELLED,
        ]);
    }
}
