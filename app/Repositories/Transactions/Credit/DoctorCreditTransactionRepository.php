<?php

namespace App\Repositories\Transactions\Credit;

use App\Models\DoctorCreditTransaction;

class DoctorCreditTransactionRepository
{
    protected $transaction;

    public function __construct(DoctorCreditTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function get()
    {
        return $this->transaction->get();
    }

    public function find($id)
    {
        return $this->transaction->find($id);
    }

    public function create($data)
    {
        return $this->transaction->create($data);
    }

    public function updateOrCreateCall($criteria, $scheduleData)
    {
        return $this->transaction->updateOrCreate($criteria, $scheduleData);
    }

    public function update($id, $transactionData)
    {
        $transaction = $this->transaction->find($id);
        $transaction->update($transactionData);
        return $transaction;
    }

    public function delete($id)
    {
        $transaction = $this->transaction->find($id);
        $transaction->delete();
        return $transaction;
    }

    public function exists($id)
    {
        $transaction = $this->transaction->where('id', $id)->exists();
        return $transaction;
    }

    public function getTotalCredit($doctor_id)
    {
        return $this->transaction->where('doctor_id', $doctor_id)->sum('amount');
    }
}
