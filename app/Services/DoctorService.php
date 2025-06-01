<?php

namespace App\Services;
use App\Repositories\DoctorRepository;
use App\Repositories\HospitalSpecialistRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DoctorService
{
    private $doctorRepository;
    private $hospitalSpecialistRepository;

    public function __construct(
        DoctorRepository $doctorRepository,
        HospitalSpecialistRepository $hospitalSpecialistRepository
    ) {
        // Dependency injection for repositories
        $this->doctorRepository = $doctorRepository;
        $this->hospitalSpecialistRepository = $hospitalSpecialistRepository;
    }

    public function getAll(array $fields)
    {
        // Retrieve all doctors with specified fields
        return $this->doctorRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        // Retrieve a doctor by ID with specified fields
        return $this->doctorRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        //Check if specialization and hospital are provided
        if (!$this->hospitalSpecialistRepository->existsForHospitalAndSpecialist(
            $data['hospital_id'] ?? null,
            $data['specialization_id'] ?? null
        )) {
            throw ValidationException::withMessages([
                'specialist_id' => ['Selected specialist does not exist for the selected hospital.'],
            ]);
        }
        
        // Check if photo is provided and is an instance of UploadedFile
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }

        // Create a new doctor
        return $this->doctorRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        // Check if the doctor exists
        $doctor = $this->doctorRepository->getById($id, ['*']);

        // Check if specialization and hospital are provided
        if (!$this->hospitalSpecialistRepository->existsForHospitalAndSpecialist(
            $data['hospital_id'] ?? null,
            $data['specialization_id'] ?? null
        )) {
            throw new \Exception('Invalid hospital or specialization ID');
        }

        // Check if photo is provided and is an instance of UploadedFile
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            // Delete the old photo if it exists
            if (!empty($doctor->photo)) {
                $this->deletePhoto($doctor->photo);
            }
            // Upload the new photo
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }

        // Update the doctor
        return $this->doctorRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        // Delete a doctor by ID
        return $this->doctorRepository->delete($id);
    }

    private function uploadPhoto(UploadedFile $photo)
    {
        // Logic to upload a photo
        return $photo->store('doctors', 'public');
  
    }

    private function deletePhoto(string $photoPath)
    {
        $relativePath = 'doctors/' . basename($photoPath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}   