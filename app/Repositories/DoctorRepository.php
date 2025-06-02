<?php

namespace App\Repositories;
use App\Models\Doctor;

class DoctorRepository
{
    public function getAll(array $fields)
    {
        // Logic to retrieve all doctors with specified fields
        return Doctor::select($fields)
            ->with(['specialist', 'hospital'])
            ->latest()
            ->paginate(10);
    }

    public function getById(int $id, array  $fields)
    {
        // Logic to retrieve a doctor by ID with specified fields
        return Doctor::select($fields)
            ->with(['specialist', 'hospital', 'bookingTransactions.user'])
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        // Logic to create a new doctor
        return Doctor::create($data);
    }
    public function update(int $id, array $data)
    {
        // Logic to update an existing doctor
        $doctor = Doctor::findOrFail($id);
        $doctor->update($data);
        return $doctor;
    }
    public function delete(int $id)
    {
        // Logic to delete a doctor by ID
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();
        return $doctor;
    }

    public function filterBySpecialistAndHospital(int $hospitalId, int $specialistId)
    {
        // Logic to filter doctors by specialization and hospital
        return Doctor::with('specialist','hospital')
            ->where('hospital_id', $hospitalId)
            ->where('specialist_id', $specialistId)
            ->get();
    }

}