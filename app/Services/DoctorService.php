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
            $data['hospital_id'],
            $data['specialization_id']
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
        $doctor = $this->doctorRepository->getById($id, ['*']);

        if ($doctor->photo) {
            // Delete the doctor's photo if it exists
            $this->deletePhoto($doctor->photo);
        }
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

    public function filterBySpecialistAndHospital(int $hospitalId, int $specialistId)
    {
        // Filter doctors by specialization and hospital
        return $this->doctorRepository->filterBySpecialistAndHospital($hospitalId, $specialistId);
    }

    public function getAvailableSlots(int $doctorId)
    {
        // Logic to get available slots for a doctor on a specific date
        $doctor =  $this->doctorRepository->getById($doctorId, ['id']);

        $dates = collect([
            now()->addDays(1)->startOfDay(),
            now()->addDays(2)->startOfDay(),
            now()->addDays(3)->startOfDay(),
        ]);

        $timeSlots = collect([
            '10:00', '11:00', '13:00',
            '14:00', '15:00', '16:00', '17:00'
        ]);

        $availability = [];

        foreach ($dates as $date) {
            $dateStr = $date->toDateString();
            $availability[$dateStr] = [];
            
            foreach ($timeSlots as $time) {
                $isTaken = $doctor->bookingTransactions()
                ->whereDate('started_at', $dateStr)
                ->whereTime('started_at', $time)
                ->exists();

                if (!$isTaken) {
                    $availability[$dateStr] = $time;
                }
            }
        }
    }
}   