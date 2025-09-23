<?php

namespace App\Repositories;

use App\Models\FundSource;

class FundSourceRepository extends BaseRepository
{
    /**
     * FundSourceRepository constructor.
     *
     * @param FundSource $model
     */
    public function __construct(FundSource $model)
    {
        parent::__construct($model);
    }

    // Add specific methods for FundSource if needed
}
