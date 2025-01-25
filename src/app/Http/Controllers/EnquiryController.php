<?php

namespace App\Http\Controllers;

use App\DTOs\EnquiryDTO;
use App\Services\EnquiryService;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function store(Request $request, EnquiryService $enquiryService){
        $enquiryService->store( EnquiryDTO::fromApiRequest($request->all()) );

        return response()->json([ 'error_code' => 0, 'message' => 'Enquiry created successfully' ]);
    }
}
