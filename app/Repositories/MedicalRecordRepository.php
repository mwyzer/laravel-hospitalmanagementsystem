<?php

namespace App\Repositories;

use App\Models\MedicalRecord;

class MedicalRecordRepository
{
    public function all()
    {
        return MedicalRecord::with(['doctor', 'user', 'hospital'])->get();
    }

    public function find($id)
    {
        return MedicalRecord::with(['doctor', 'user', 'hospital'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return MedicalRecord::create($data);
    }

    public function update(MedicalRecord $record, array $data)
    {
        $record->update($data);
        return $record;
    }

    public function delete(MedicalRecord $record)
    {
        return $record->delete();
    }
}
