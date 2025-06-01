<?php

namespace App\Repositories;
use App\Models\BookingTransaction;

class BookingTransactionRepository
{
    //query for admin or manager
    public function getAll()
    {
        // Return Booking Transactions with doctor, doctor.hospital, doctor.specialist, user, method latest and paginate 10
        return BookingTransaction::with([
            'doctor',
            'doctor.hospital',
            'doctor.specialist',
            'user'
        ])->latest()->paginate(10);
    }

    public function getByIdForManager(int $id)
    {
        // Return Booking Transaction by id with doctor, doctor.hospital, doctor.specialist, user
        return BookingTransaction::where('id', $id)
            ->with(['doctor', 'doctor.hospital', 'doctor.specialist', 'user'])
            ->firstOrFail($id);
    }

    //update status
    public function updateStatus(int $id, string $status)
    {
        // Update Booking Transaction status by admin or manager
        $transaction = $this->getByIdForManager($id);
        $transaction->update(['status' => $status]);
        return $transaction;
    }

    // getAllForUser
    public function getAllForUser(int $userId)
    {
        // Return Booking Transactions for a specific user where doctor, doctor.hospital, doctor.specialist, user, method latest and paginate 10
        return BookingTransaction::where('user_id', $userId)
            ->with(['doctor', 'doctor.hospital', 'doctor.specialist'])
            ->latest()
            ->paginate(10);
    }

    public function getById(int $id, int $userId)
    {
        // Return Booking Transaction by id with doctor, doctor.hospital, doctor.specialist, user
        return BookingTransaction::where('id', $id)
            ->where('user_id', $userId)
            ->with(['doctor', 'doctor.hospital', 'doctor.specialist'])
            ->firstOrFail();
    }

    public function create(array $data)
    {
        return BookingTransaction::create($data);
    }

    public function isTimeSlotTakenForDoctor(int $doctorId, string $date, string $time)
    {
        // Check if the time slot is available for the doctor on the given date
        return BookingTransaction::where('doctor_id', $doctorId)
            ->whereDate('started_at', $date)
            ->whereTime('time_at', $time)
            ->exists();
    }

}