<?php

namespace App\Services;

use App\Models\MedicalRecord;
use App\Repositories\MedicalRecordRepository;

class MedicalRecordService
{
    protected $repository;

    public function __construct(MedicalRecordRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->repository->find($id);
        return $this->repository->update($record, $data);
    }

    public function delete($id)
    {
        $record = $this->repository->find($id);
        return $this->repository->delete($record);
    }
}
