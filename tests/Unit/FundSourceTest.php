<?php

namespace Tests\Unit;

use App\Models\FundSource;
use App\Models\User;
use App\Repositories\FundSourceRepository;
use App\Services\FundSourceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundSourceTest extends TestCase
{
    use RefreshDatabase;

    protected $fundSourceRepository;
    protected $fundSourceService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fundSourceRepository = new FundSourceRepository(new FundSource());
        $this->fundSourceService = new FundSourceService($this->fundSourceRepository);
        $this->user = User::factory()->create();
    }

    public function test_can_create_fund_source()
    {
        $data = [
            'user_id' => $this->user->id,
            'name' => 'Cash',
            'balance' => 1000.00,
        ];

        $fundSource = $this->fundSourceService->createFundSource($data);

        $this->assertInstanceOf(FundSource::class, $fundSource);
        $this->assertEquals('Cash', $fundSource->name);
        $this->assertEquals(1000.00, $fundSource->balance);
        $this->assertDatabaseHas('fund_sources', $data);
    }

    public function test_can_get_all_fund_sources()
    {
        FundSource::factory()->count(3)->create(['user_id' => $this->user->id]);

        $fundSources = $this->fundSourceService->getAllFundSources();

        $this->assertCount(3, $fundSources);
        $this->assertContainsOnlyInstancesOf(FundSource::class, $fundSources);
    }

    public function test_can_get_fund_source_by_id()
    {
        $fundSource = FundSource::factory()->create(['user_id' => $this->user->id]);

        $foundFundSource = $this->fundSourceService->getFundSourceById($fundSource->id);

        $this->assertInstanceOf(FundSource::class, $foundFundSource);
        $this->assertEquals($fundSource->id, $foundFundSource->id);
    }

    public function test_can_update_fund_source()
    {
        $fundSource = FundSource::factory()->create(['user_id' => $this->user->id]);

        $newData = [
            'name' => 'Updated Cash',
            'balance' => 1500.00,
        ];

        $updatedFundSource = $this->fundSourceService->updateFundSource($fundSource->id, $newData);

        $this->assertInstanceOf(FundSource::class, $updatedFundSource);
        $this->assertEquals('Updated Cash', $updatedFundSource->name);
        $this->assertEquals(1500.00, $updatedFundSource->balance);
        $this->assertDatabaseHas('fund_sources', ['id' => $fundSource->id, 'name' => 'Updated Cash', 'balance' => 1500.00]);
    }

    public function test_can_delete_fund_source()
    {
        $fundSource = FundSource::factory()->create(['user_id' => $this->user->id]);

        $deleted = $this->fundSourceService->deleteFundSource($fundSource->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('fund_sources', ['id' => $fundSource->id]);
    }
}