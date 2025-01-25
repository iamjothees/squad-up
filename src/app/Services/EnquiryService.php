<?php

namespace App\Services;

use App\DTOs\EnquiryDTO;
use App\Models\Enquiry;

class EnquiryService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function store( EnquiryDTO $enquiryDTO ): EnquiryDTO{
        $enquiry = Enquiry::create( $enquiryDTO->toCreateArray() );

        // Notify admin through Email / SMS / WhatsApp
        return EnquiryDTO::fromModel($enquiry);
    }
}
