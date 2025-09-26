<?php

namespace App\Repositories;

use App\Models\FundSourceTransfer;

class FundSourceTransferRepository extends BaseRepository
{
    public function __construct(FundSourceTransfer $model)
    {
        parent::__construct($model);
    }
}
