<?php

namespace App\Repositories\Company;

use App\Models\CompanyInformation;

class CompanyInfoRepository
{
    protected $companyInfo;

    public function __construct(CompanyInformation $companyInfo)
    {
        $this->companyInfo = $companyInfo;
    }

    public function create($companyInfoData)
    {
        return $this->companyInfo->create($companyInfoData);
    }

    public function find($id = null)
    {
        return $this->companyInfo->find($id);
    }

    public function get()
    {
        $companyInfo = $this->companyInfo->select(['id', 'name', 'mobile_phone_no', 'sms_phone_no', 'whatsapp_number', 'email'])->orderBy('id', 'asc');
        return $companyInfo->first();
    }

    public function update($id, $data)
    {
        $companyInfo = $this->companyInfo->find($id);
        $companyInfo->update($data);
        return $companyInfo;
    }

    public function delete($id)
    {
        $companyInfo = $this->companyInfo->find($id);
        $companyInfo->delete();
        return $companyInfo;
    }

    public function exists($id)
    {
        $companyInfo = $this->companyInfo->where('id', $id)->exists();
        return $companyInfo;
    }
}
