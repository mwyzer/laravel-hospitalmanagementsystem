<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MedicalRecord extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'examination_date',
        'diagnosis',
        'prescription',
        'additional_notes',
        'doctor_id',
        'hospital_id',
    ];
    
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
}
