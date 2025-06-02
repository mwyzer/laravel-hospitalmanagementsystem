<?php

namespace App\Http\Controllers;

use App\Services\DoctorService;
use App\Http\Resources\DoctorResource;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    //
    private $doctorService;
    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }
    public function index(Request $request)
    {
        $fields = ['id', 'name', 'photo', 'gender', 'yoe', 'specialist_id', 'hospital_id'];
        $doctors = $this->doctorService->getAll($fields);
        return response()->json(DoctorResource::collection($doctors));
    }
}
