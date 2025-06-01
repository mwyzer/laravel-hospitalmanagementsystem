<?php

namespace App\Repositories;

use App\Models\HospitalSpecialist;

class HospitalSpecialistRepository
{
    public function existsForHospitalAndSpecialist($hospitalId, $specializationId)
    {
        // Logic to check if a hospital-specialist relationship exists
        return HospitalSpecialist::where('hospital_id', $hospitalId)
            ->where('specialization_id', $specializationId)
            ->exists();
    }
}