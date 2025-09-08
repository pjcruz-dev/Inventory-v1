<?php

namespace Tests\Unit\Models;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\User;
use App\Models\AssetTransfer;
use App\Models\Peripheral;
use App\Models\PrintLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_asset()
    {
        $assetType = AssetType::factory()->create();
        $user = User::factory()->create();

        $assetData = [
            'asset_tag' => 'ASSET001',
            'serial_no' => 'SN123456',
            'asset_type_id' => $assetType->id,
            'model' => 'Dell Laptop',
            'manufacturer' => 'Dell',
            'purchase_date' => '2023-01-01',
            'warranty_until' => '2025-01-01',
            'cost' => 1500.00,
            'status' => 'available',
            'location' => 'Office A',
            'assigned_to_user_id' => null,
            'created_by' => $user->id,
        ];

        $asset = Asset::create($assetData);

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('ASSET001', $asset->asset_tag);
        $this->assertEquals('available', $asset->status);
        $this->assertEquals(1500.00, $asset->cost);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $asset = new Asset();
        $expected = [
            'asset_tag',
            'serial_no',
            'asset_type_id',
            'model',
            'manufacturer',
            'purchase_date',
            'warranty_until',
            'cost',
            'status',
            'location',
            'assigned_to_user_id',
            'created_by',
        ];

        $this->assertEquals($expected, $asset->getFillable());
    }

    /** @test */
    public function it_casts_dates_correctly()
    {
        $asset = Asset::factory()->create([
            'purchase_date' => '2023-01-01',
            'warranty_until' => '2025-01-01',
            'cost' => 1500.50
        ]);

        $this->assertInstanceOf(Carbon::class, $asset->purchase_date);
        $this->assertInstanceOf(Carbon::class, $asset->warranty_until);
        $this->assertEquals('1500.50', $asset->cost);
    }

    /** @test */
    public function it_belongs_to_asset_type()
    {
        $assetType = AssetType::factory()->create(['name' => 'Laptop']);
        $asset = Asset::factory()->create(['asset_type_id' => $assetType->id]);

        $this->assertInstanceOf(AssetType::class, $asset->assetType);
        $this->assertEquals('Laptop', $asset->assetType->name);
    }

    /** @test */
    public function it_belongs_to_assigned_user()
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $asset = Asset::factory()->create(['assigned_to_user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $asset->assignedTo);
        $this->assertEquals('John Doe', $asset->assignedTo->name);
    }

    /** @test */
    public function it_belongs_to_creator()
    {
        $creator = User::factory()->create(['name' => 'Admin User']);
        $asset = Asset::factory()->create(['created_by' => $creator->id]);

        $this->assertInstanceOf(User::class, $asset->createdBy);
        $this->assertEquals('Admin User', $asset->createdBy->name);
    }

    /** @test */
    public function it_has_many_peripherals()
    {
        $asset = Asset::factory()->create();
        $peripheral = Peripheral::factory()->create(['asset_id' => $asset->id]);

        $this->assertTrue($asset->peripherals()->exists());
        $this->assertEquals($peripheral->id, $asset->peripherals->first()->id);
    }

    /** @test */
    public function it_has_many_transfers()
    {
        $asset = Asset::factory()->create();
        $transfer = AssetTransfer::factory()->create(['asset_id' => $asset->id]);

        $this->assertTrue($asset->transfers()->exists());
        $this->assertEquals($transfer->id, $asset->transfers->first()->id);
    }

    /** @test */
    public function it_has_many_print_logs()
    {
        $asset = Asset::factory()->create();
        $printLog = PrintLog::factory()->create(['asset_id' => $asset->id]);

        $this->assertTrue($asset->printLogs()->exists());
        $this->assertEquals($printLog->id, $asset->printLogs->first()->id);
    }

    /** @test */
    public function it_returns_correct_validation_rules_for_new_asset()
    {
        $rules = Asset::validationRules();

        $this->assertArrayHasKey('asset_tag', $rules);
        $this->assertArrayHasKey('asset_type_id', $rules);
        $this->assertArrayHasKey('status', $rules);
        $this->assertContains('required', $rules['asset_tag']);
        $this->assertContains('required', $rules['asset_type_id']);
        $this->assertContains('required', $rules['status']);
    }

    /** @test */
    public function it_returns_correct_validation_rules_for_existing_asset()
    {
        $asset = Asset::factory()->create();
        $rules = Asset::validationRules($asset->id);

        $this->assertArrayHasKey('asset_tag', $rules);
        $this->assertIsArray($rules['asset_tag']);
    }

    /** @test */
    public function it_validates_status_values()
    {
        $rules = Asset::validationRules();
        $statusRule = $rules['status'];

        $this->assertContains('in:available,assigned,in_repair,disposed', $statusRule);
    }

    /** @test */
    public function it_validates_purchase_date_not_in_future()
    {
        $rules = Asset::validationRules();
        $purchaseDateRule = $rules['purchase_date'];

        $this->assertContains('before_or_equal:today', $purchaseDateRule);
    }

    /** @test */
    public function it_validates_warranty_after_purchase_date()
    {
        $rules = Asset::validationRules();
        $warrantyRule = $rules['warranty_until'];

        $this->assertContains('after_or_equal:purchase_date', $warrantyRule);
    }

    /** @test */
    public function it_validates_cost_range()
    {
        $rules = Asset::validationRules();
        $costRule = $rules['cost'];

        $this->assertContains('min:0', $costRule);
        $this->assertContains('max:999999.99', $costRule);
    }
}