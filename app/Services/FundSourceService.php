<?php

namespace App\Services;

use App\Repositories\FundSourceRepository;

class FundSourceService extends BaseService
{
    protected $fundSourceRepository;

    public function __construct(FundSourceRepository $fundSourceRepository)
    {
        $this->fundSourceRepository = $fundSourceRepository;
    }

    public function getAllFundSources()
    {
        return $this->fundSourceRepository->all();
    }

    public function createFundSource(array $data)
    {
        return $this->fundSourceRepository->create($data);
    }

    public function updateFundSource(int $id, array $data)
    {
        return $this->fundSourceRepository->update($id, $data);
    }

    public function deleteFundSource(int $id)
    {
        return $this->fundSourceRepository->delete($id);
    }

    public function getFundSourceById(int $id)
    {
        return $this->fundSourceRepository->find($id);
    }
}
