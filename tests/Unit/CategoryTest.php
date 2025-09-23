<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryRepository;
    protected $categoryService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = new CategoryRepository(new Category());
        $this->categoryService = new CategoryService($this->categoryRepository);
        $this->user = User::factory()->create();
    }

    public function test_can_create_category()
    {
        $data = [
            'user_id' => $this->user->id,
            'name' => 'Food',
            'type' => 'expense',
        ];

        $category = $this->categoryService->createCategory($data);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Food', $category->name);
        $this->assertEquals('expense', $category->type);
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_can_get_all_categories()
    {
        Category::factory()->count(3)->create(['user_id' => $this->user->id]);

        $categories = $this->categoryService->getAllCategories();

        $this->assertCount(3, $categories);
        $this->assertContainsOnlyInstancesOf(Category::class, $categories);
    }

    public function test_can_get_category_by_id()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $foundCategory = $this->categoryService->getCategoryById($category->id);

        $this->assertInstanceOf(Category::class, $foundCategory);
        $this->assertEquals($category->id, $foundCategory->id);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $newData = [
            'name' => 'Updated Food',
            'type' => 'income',
        ];

        $updatedCategory = $this->categoryService->updateCategory($category->id, $newData);

        $this->assertInstanceOf(Category::class, $updatedCategory);
        $this->assertEquals('Updated Food', $updatedCategory->name);
        $this->assertEquals('income', $updatedCategory->type);
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Food', 'type' => 'income']);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $deleted = $this->categoryService->deleteCategory($category->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}