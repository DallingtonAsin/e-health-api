<?php

namespace App\Repositories\Appointments;

use App\Models\Call;

class CallRepository
{
    protected $call;

    public function __construct(Call $call)
    {
        $this->call = $call;
    }

    public function get()
    {
        return $this->call->get();
    }

    public function find($id)
    {
        return $this->call->find($id);
    }

    public function create($data)
    {
        return $this->call->create($data);
    }

    public function updateOrCreateCall($criteria, $scheduleData)
    {
        return $this->call->updateOrCreate($criteria, $scheduleData);
    }

    public function update($id, $callData)
    {
        $call = $this->call->find($id);
        $call->update($callData);
        return $call;
    }

    public function delete($id)
    {
        $call = $this->call->find($id);
        $call->delete();
        return $call;
    }

    public function exists($id)
    {
        $call = $this->call->where('id', $id)->exists();
        return $call;
    }
}
