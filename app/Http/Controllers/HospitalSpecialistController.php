<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Services\HospitalService;
use Illuminate\Http\Request;

class HospitalSpecialistController extends Controller
{
    //
    private $hospitalService;

    public function __construct(HospitalService $hospitalService)
    {
        $this->hospitalService = $hospitalService;
    }

    public function attach(Request $request, int $hospitalId)
    {
        $hospital = Hospital::findOrFail($hospitalId);
        
        // Validate the request
        $request->validate([
            'specialist_id' => 'required|exists:specialists,id',
        ]);

        $this->hospitalService->attachSpecialist($hospitalId, $request->input('specialist_id'));

        return response()->json([
            'message' => 'Specialist attached successfully.'
        ], 200);
    }

    public function detach(int $hospitalId, int $specialistId)
    {
        $hospital = Hospital::findOrFail($hospitalId);
        
        $this->hospitalService->detachSpecialist($hospitalId, $specialistId);

        return response()->json([
            'message' => 'Specialist detached successfully.'
        ], 200);
    }




}
