<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    /**
     * CategoryRepository constructor.
     *
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    // Add specific methods for Category if needed
    public function getGroupedCategoriesByType(string $type)
    {
        return $this->model->where('type', $type)->get();
    }
}
