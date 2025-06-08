<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Services\BookingTransactionService;
use App\Http\Requests\BookingTransactionRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyOrderController extends Controller
{
    //
    private $bookingTransactionService;

    public function __construct(BookingTransactionService $bookingTransactionService)
    {
        $this->bookingTransactionService = $bookingTransactionService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $orders = $this->bookingTransactionService->getAllForUser($userId);
        return response()->json(TransactionResource::collection($orders), 200);
    }

    public function show(int $id)
    {
        $user = Auth::user();
        $userId = $user->id;

        //use try catch
        try {
            $order = $this->bookingTransactionService->getById($id, $userId);
            return response()->json(new TransactionResource($order), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    }

    public function store (BookingTransactionRequest $request)
    {
        $transaction = $this->bookingTransactionService->create($request->validated());
        return response()->json(new TransactionResource($transaction), 201);


    }
}
