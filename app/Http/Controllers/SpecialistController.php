<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialistRequest;
use App\Http\Resources\SpecialistResource;
use App\Models\Specialist;
use App\Services\SpecialistService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    //
    private $specialistService;

    public  function __construct($specialistService)
    {
        $this->specialistService = $specialistService;
    }

    public function index()
    {
        $fields = ['id', 'name', 'photo', 'price'];
        $specialists = $this->specialistService->getAllSpecialists($fields);
        return response()->json(SpecialistResource::collection($specialists), 200);
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $specialist = $this->specialistService->getSpecialistById($id, $fields);
            return response()->json(new SpecialistResource($specialist), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Specialist not found'], 404);
        }
    }

    public function store(SpecialistRequest $request)
    {
        $data = $request->validated();
        $specialist = $this->specialistService->createSpecialist($data);
        return response()->json(new SpecialistResource($specialist), 201);
    }

    public function update(SpecialistRequest $request, int $id)
    {
        try {
            $specialist = $this->specialistService->update($id, $request->validated());
            return response()->json(new SpecialistResource($specialist), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Specialist not found'], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->specialistService->delete($id);
            return response()->json(['message' => 'Specialist deleted successfully'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Specialist not found'], 404);
        }
    }
}
