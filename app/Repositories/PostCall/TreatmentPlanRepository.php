<?php

namespace App\Repositories\PostCall;

use App\Models\TreatmentPlan;

class TreatmentPlanRepository
{
    protected $treatmentPlan;

    public function __construct(TreatmentPlan $treatmentPlan)
    {
        $this->treatmentPlan = $treatmentPlan;
    }

    public function create($treatmentPlanData)
    {
        return $this->treatmentPlan->create($treatmentPlanData);
    }

    public function updateOrCreate($criteria, $data)
    {
        return $this->treatmentPlan->updateOrCreate($criteria, $data);
    }

    public function find($id )
    {
        return $this->treatmentPlan->find($id);
    }

    public function get()
    {
        $treatmentPlans = $this->treatmentPlan->select(['id', 'appointment_id', 'treatment_plan'])->orderBy('id', 'asc');
        return $treatmentPlans->get();
    }

    public function update($id, $treatmentPlanData)
    {
        $treatmentPlan = $this->treatmentPlan->find($id);
        $treatmentPlan->update($treatmentPlanData);
        return $treatmentPlan;
    }

    public function delete($id)
    {
        $treatmentPlan = $this->treatmentPlan->find($id);
        $treatmentPlan->delete();
        return $treatmentPlan;
    }

    public function exists($id)
    {
        $treatmentPlan = $this->treatmentPlan->where('id', $id)->exists();
        return $treatmentPlan;
    }
}
