<?php

namespace App\Services;

use App\Models\BookingTransaction;
use App\Repositories\BookingTransactionRepository;
use App\Repositories\DoctorRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;

class BookingTransactionService
{
    private BookingTransactionRepository $bookingTransactionRepository;
    private DoctorRepository $doctorRepository;
    public function __construct(
        BookingTransactionRepository $bookingTransactionRepository,
        DoctorRepository $doctorRepository
    ) {
        $this->bookingTransactionRepository = $bookingTransactionRepository;
        $this->doctorRepository = $doctorRepository;
    }

    //admin services
    public function getAll()
    {
        return $this->bookingTransactionRepository->getAll();
    }

    public function getByIdForManager(int $id)
    {
        return $this->bookingTransactionRepository->getByIdForManager($id);
    }

    public function updateStatus(int $id, string $status)
    {
       if (!in_array($status, ['Approved', 'Rejected', 'Pending'])) {
            throw ValidationException::withMessages([
                'status' => 'Invalid status provided.'
            ]);
        }

        return $this->bookingTransactionRepository->updateStatus($id, $status);
    }

    //user services
    public function getAllForUser(int $userId)
    {
        return $this->bookingTransactionRepository->getAllForUser($userId);
    }

    public function getById(int $id, int $userId)
    {
        return $this->bookingTransactionRepository->getById($id, $userId);
    }

    public function create(array $data)
    {
        $data['user_id'] = auth()->id();

        if ($this->bookingTransactionRepository->isTimeSlotTakenForDoctor($data['doctor_id'], $data['started_at'], $data['time_at'])) {
            throw ValidationException::withMessages([
                'time_at' => 'This doctor is already booked at this time.'
            ]);
        }

        $doctor = $this->doctorRepository->getById($data['doctor_id'], ['*']);

        $price = $doctor->specialist->price;
        $tax = (int) round($price * 0.11);
        $grand = $price + $tax;

        $data['sub_total'] = $price;
        $data['tax'] = $tax;
        $data['grand_total'] = $grand;
        $data['status'] = 'Waiting';

        if (isset($data['proof']) && $data['proof'] instanceOf UploadedFile) {
            $data['proof'] = $this->uploadProof($data['proof']);
        }
    }

    private function uploadProof($proof)
    {
        // Handle the file upload logic here
        // For example, you can use Storage facade to store the file
        return $proof->store('proofs', 'public');
    }


}
