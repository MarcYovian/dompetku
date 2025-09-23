<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\FundSource;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\FundSourceRepository;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected $transactionRepository;
    protected $fundSourceRepository;
    protected $transactionService;
    protected $user;
    protected $fundSource;
    protected $incomeCategory;
    protected $expenseCategory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionRepository = new TransactionRepository(new Transaction());
        $this->fundSourceRepository = new FundSourceRepository(new FundSource());
        $this->transactionService = new TransactionService($this->transactionRepository, $this->fundSourceRepository);
        $this->user = User::factory()->create();
        $this->fundSource = FundSource::factory()->create(['user_id' => $this->user->id, 'balance' => 1000]);
        $this->incomeCategory = Category::factory()->income()->create(['user_id' => $this->user->id]);
        $this->expenseCategory = Category::factory()->expense()->create(['user_id' => $this->user->id]);
    }

    public function test_can_create_income_transaction_and_update_fund_source_balance()
    {
        $data = [
            'user_id' => $this->user->id,
            'category_id' => $this->incomeCategory->id,
            'fund_source_id' => $this->fundSource->id,
            'amount' => 500.00,
            'type' => 'income',
            'description' => 'Salary',
            'transaction_date' => '2025-01-01',
        ];

        $transaction = $this->transactionService->createTransaction($data);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals(500.00, $transaction->amount);
        $this->assertEquals('income', $transaction->type);
        $this->assertDatabaseHas('transactions', $data);

        $this->fundSource->refresh();
        $this->assertEquals(1500.00, $this->fundSource->balance); // 1000 (initial) + 500 (income)
    }

    public function test_can_create_expense_transaction_and_update_fund_source_balance()
    {
        $data = [
            'user_id' => $this->user->id,
            'category_id' => $this->expenseCategory->id,
            'fund_source_id' => $this->fundSource->id,
            'amount' => 200.00,
            'type' => 'expense',
            'description' => 'Groceries',
            'transaction_date' => '2025-01-02',
        ];

        $transaction = $this->transactionService->createTransaction($data);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals(200.00, $transaction->amount);
        $this->assertEquals('expense', $transaction->type);
        $this->assertDatabaseHas('transactions', $data);

        $this->fundSource->refresh();
        $this->assertEquals(800.00, $this->fundSource->balance); // 1000 (initial) - 200 (expense)
    }

    public function test_can_get_latest_transactions_for_user()
    {
        Transaction::factory()->count(5)->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $this->expenseCategory->id]);
        $latestTransaction = Transaction::factory()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $this->expenseCategory->id, 'transaction_date' => now()->addDay()]);

        $transactions = $this->transactionService->getLatestTransactions($this->user->id, 5);

        $this->assertCount(5, $transactions);
        $this->assertEquals($latestTransaction->id, $transactions->first()->id);
    }

    public function test_can_get_monthly_income_and_expense_for_user()
    {
        // Income
        Transaction::factory()->income()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $this->incomeCategory->id, 'amount' => 1000, 'transaction_date' => '2025-09-15']);
        Transaction::factory()->income()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $this->incomeCategory->id, 'amount' => 500, 'transaction_date' => '2025-09-20']);
        // Expense
        Transaction::factory()->expense()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $this->expenseCategory->id, 'amount' => 300, 'transaction_date' => '2025-09-10']);
        Transaction::factory()->expense()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $this->expenseCategory->id, 'amount' => 200, 'transaction_date' => '2025-09-25']);

        $monthlyData = $this->transactionService->getMonthlyIncomeExpense($this->user->id, 2025, 9);

        $this->assertEquals(1500, $monthlyData['income']);
        $this->assertEquals(500, $monthlyData['expense']);
    }

    public function test_can_get_expense_distribution_by_category_for_user()
    {
        $category1 = Category::factory()->expense()->create(['user_id' => $this->user->id, 'name' => 'Food']);
        $category2 = Category::factory()->expense()->create(['user_id' => $this->user->id, 'name' => 'Transport']);

        Transaction::factory()->expense()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $category1->id, 'amount' => 100, 'transaction_date' => '2025-09-01']);
        Transaction::factory()->expense()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $category1->id, 'amount' => 150, 'transaction_date' => '2025-09-05']);
        Transaction::factory()->expense()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $category2->id, 'amount' => 50, 'transaction_date' => '2025-09-10']);

        $distribution = $this->transactionService->getExpenseDistributionByCategory($this->user->id, 2025, 9);

        $this->assertCount(2, $distribution);
        $this->assertEquals(250, $distribution->where('category_name', 'Food')->first()->total_amount);
        $this->assertEquals(50, $distribution->where('category_name', 'Transport')->first()->total_amount);
    }

    public function test_can_get_filtered_transactions_for_user()
    {
        $fundSource2 = FundSource::factory()->create(['user_id' => $this->user->id]);

        // Transactions for filtering
        Transaction::factory()->income()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $this->incomeCategory->id, 'amount' => 100, 'transaction_date' => '2025-01-05']);
        Transaction::factory()->expense()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $this->expenseCategory->id, 'amount' => 200, 'transaction_date' => '2025-01-10']);
        Transaction::factory()->income()->create(['user_id' => $this->user->id, 'fund_source_id' => $fundSource2->id, 'category_id' => $this->incomeCategory->id, 'amount' => 300, 'transaction_date' => '2025-01-15']);
        Transaction::factory()->expense()->create(['user_id' => $this->user->id, 'fund_source_id' => $fundSource2->id, 'category_id' => $this->expenseCategory->id, 'amount' => 400, 'transaction_date' => '2025-02-01']);

        // Filter by date range and type (income)
        $filtered = $this->transactionService->getFilteredTransactions($this->user->id, '2025-01-01', '2025-01-31', 'income', null);
        $this->assertCount(2, $filtered);
        $this->assertEquals(300, $filtered->first()->amount);

        // Filter by date range and fund source
        $filtered = $this->transactionService->getFilteredTransactions($this->user->id, '2025-01-01', '2025-01-31', null, $this->fundSource->id);
        $this->assertCount(2, $filtered);

        // Filter by all criteria
        $filtered = $this->transactionService->getFilteredTransactions($this->user->id, '2025-01-01', '2025-01-31', 'expense', $this->fundSource->id);
        $this->assertCount(1, $filtered);
        $this->assertEquals(200, $filtered->first()->amount);
    }

    public function test_can_get_filtered_expense_distribution_by_category_for_user()
    {
        $category1 = Category::factory()->expense()->create(['user_id' => $this->user->id, 'name' => 'Food']);
        $category2 = Category::factory()->expense()->create(['user_id' => $this->user->id, 'name' => 'Transport']);
        $fundSource2 = FundSource::factory()->create(['user_id' => $this->user->id]);

        Transaction::factory()->expense()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $category1->id, 'amount' => 100, 'transaction_date' => '2025-01-01']);
        Transaction::factory()->expense()->create(['user_id' => $this->user->id, 'fund_source_id' => $this->fundSource->id, 'category_id' => $category1->id, 'amount' => 150, 'transaction_date' => '2025-01-05']);
        Transaction::factory()->expense()->create(['user_id' => $this->user->id, 'fund_source_id' => $fundSource2->id, 'category_id' => $category2->id, 'amount' => 50, 'transaction_date' => '2025-01-10']);

        // Filter by date range
        $distribution = $this->transactionService->getFilteredExpenseDistributionByCategory($this->user->id, '2025-01-01', '2025-01-31', null);
        $this->assertCount(2, $distribution);
        $this->assertEquals(250, $distribution->where('category_name', 'Food')->first()->total_amount);

        // Filter by date range and fund source
        $distribution = $this->transactionService->getFilteredExpenseDistributionByCategory($this->user->id, '2025-01-01', '2025-01-31', $this->fundSource->id);
        $this->assertCount(1, $distribution);
        $this->assertEquals(250, $distribution->where('category_name', 'Food')->first()->total_amount);
    }
}