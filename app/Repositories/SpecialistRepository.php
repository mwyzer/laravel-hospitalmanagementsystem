<?php

namespace App\Repositories;

use App\Models\Specialist;
use Illuminate\Support\Facades\Log;

class SpecialistRepository
{
    public function getAll(array $fields)
    {
        try {
            return Specialist::select($fields)
                ->latest()
                ->with(['hospitals', 'doctors'])
                ->paginate(10);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function getById(int $id, array $fields)
    {
        return Specialist::select($fields)
            ->with([
                'hospitals' => function ($query) use ($id) {
                    $query->withCount(['doctors as doctors_count' => function ($query) use ($id) {
                        $query->where('specialist_id', $id);
                    }]);
                },
                'doctors' => function ($query) use ($id) {
                    $query->where('specialist_id', $id)
                    ->with('hospital:id, name, city, post_code');
                }
                ])
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        return Specialist::create($data);
    }

    public function update(int $id, array $data)
    {
        $specialist = Specialist::findOrFail($id);
        $specialist->update($data);
        return $specialist;
    }

    public function delete(int $id)
    {
        $specialist = Specialist::findOrFail($id);
        $specialist->delete();
        return $specialist;
    }
}
