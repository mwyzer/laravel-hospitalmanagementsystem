<?php 

namespace App\Services;

use App\Repositories\HospitalRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HospitalService
{
    private $hospitalRepository;
    public function __construct(HospitalRepository $hospitalRepository)
    {
        $this->hospitalRepository = $hospitalRepository;
    }

    public function getAll(array $fields)
    {
        return $this->hospitalRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->hospitalRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        if (!isset($data['photo']) && $data['photo'] instanceOf UploadedFile) {
            $data['photo'] = $this->uploadPhoto($data['photo']);
        };

        return $this->hospitalRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        $fields = ['*'];
        $hospital = $this ->hospitalRepository->getById($id, $data);

        if (isset($data['photo']) && $data['photo'] instanceOf UploadedFile) {
            if(!empty($hospital->photo)) {
                $this->deletePhoto($hospital->photo);
            }
            $data['photo'] = $this->uploadPhoto($data['photo']);
        }

        return $this->hospitalRepository->update($id, $data);
    }

    public function uploadPhoto(UploadedFile $photo)
    {
        return $photo->store('hospitals', 'public');
    }

    public function deletePhoto(string $photoPath)
    {
        $relativePath = str_replace('hospitals/', '', $photoPath);
        if (Storage::disk('public')->exists($photoPath)) {
            Storage::disk('public')->delete($photoPath);
        }
    }

    public function attachSpecialist(int $hospitalId, int $specialistId)
    {
        $hospital = $this->hospitalRepository->getById($hospitalId, ['id']);
        $hospital->specialist()->syncWithoutDetaching([$specialistId]);
    }

    public function detachSpecialist(int $hospitalId, int $specialistId)
    {
        $hospital = $this->hospitalRepository->getById($hospitalId, ['id']);
        $hospital->specialist()->detach($specialistId);
    }

}