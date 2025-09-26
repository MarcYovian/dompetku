<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService extends BaseService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->all();
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    public function updateCategory(int $id, array $data)
    {
        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory(int $id)
    {
        return $this->categoryRepository->delete($id);
    }

    public function getCategoryById(int $id)
    {
        return $this->categoryRepository->find($id);
    }

    public function getGroupedCategoriesByType(string $type)
    {
        return $this->categoryRepository->getGroupedCategoriesByType($type);
    }
}
