<?php

namespace Tests\Unit\Models;

use App\Models\AssetTransfer;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AssetTransferTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created()
    {
        $asset = Asset::factory()->create();
        $fromUser = User::factory()->create();
        $toUser = User::factory()->create();
        $processedBy = User::factory()->create();

        $transfer = AssetTransfer::create([
            'asset_id' => $asset->id,
            'from_location' => 'Office A',
            'to_location' => 'Office B',
            'from_user_id' => $fromUser->id,
            'to_user_id' => $toUser->id,
            'transfer_reason' => 'Employee relocation',
            'transfer_date' => '2024-01-15',
            'processed_by' => $processedBy->id,
            'status' => 'pending'
        ]);

        $this->assertInstanceOf(AssetTransfer::class, $transfer);
        $this->assertEquals($asset->id, $transfer->asset_id);
        $this->assertEquals('Office A', $transfer->from_location);
        $this->assertEquals('Office B', $transfer->to_location);
        $this->assertEquals($fromUser->id, $transfer->from_user_id);
        $this->assertEquals($toUser->id, $transfer->to_user_id);
        $this->assertEquals('Employee relocation', $transfer->transfer_reason);
        $this->assertEquals('pending', $transfer->status);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'asset_id',
            'from_location',
            'to_location',
            'from_user_id',
            'to_user_id',
            'transfer_reason',
            'transfer_date',
            'processed_by',
            'status'
        ];
        $transfer = new AssetTransfer();

        $this->assertEquals($fillable, $transfer->getFillable());
    }

    /** @test */
    public function it_casts_transfer_date_to_date()
    {
        $transfer = new AssetTransfer();
        $casts = $transfer->getCasts();

        $this->assertArrayHasKey('transfer_date', $casts);
        $this->assertEquals('date', $casts['transfer_date']);
    }

    /** @test */
    public function it_belongs_to_asset()
    {
        $asset = Asset::factory()->create();
        $transfer = AssetTransfer::factory()->create(['asset_id' => $asset->id]);

        $this->assertInstanceOf(Asset::class, $transfer->asset);
        $this->assertEquals($asset->id, $transfer->asset->id);
    }

    /** @test */
    public function it_belongs_to_from_user()
    {
        $fromUser = User::factory()->create();
        $transfer = AssetTransfer::factory()->create(['from_user_id' => $fromUser->id]);

        $this->assertInstanceOf(User::class, $transfer->fromUser);
        $this->assertEquals($fromUser->id, $transfer->fromUser->id);
    }

    /** @test */
    public function it_belongs_to_to_user()
    {
        $toUser = User::factory()->create();
        $transfer = AssetTransfer::factory()->create(['to_user_id' => $toUser->id]);

        $this->assertInstanceOf(User::class, $transfer->toUser);
        $this->assertEquals($toUser->id, $transfer->toUser->id);
    }

    /** @test */
    public function it_belongs_to_processed_by_user()
    {
        $processedBy = User::factory()->create();
        $transfer = AssetTransfer::factory()->create(['processed_by' => $processedBy->id]);

        $this->assertInstanceOf(User::class, $transfer->processedBy);
        $this->assertEquals($processedBy->id, $transfer->processedBy->id);
    }

    /** @test */
    public function it_has_validation_rules()
    {
        $rules = AssetTransfer::validationRules();

        $this->assertArrayHasKey('asset_id', $rules);
        $this->assertArrayHasKey('from_location', $rules);
        $this->assertArrayHasKey('to_location', $rules);
        $this->assertArrayHasKey('from_user_id', $rules);
        $this->assertArrayHasKey('to_user_id', $rules);
        $this->assertArrayHasKey('transfer_reason', $rules);
        $this->assertArrayHasKey('transfer_date', $rules);
        $this->assertArrayHasKey('status', $rules);
        
        $this->assertContains('required', $rules['asset_id']);
        $this->assertContains('exists:assets,id', $rules['asset_id']);
        
        $this->assertContains('nullable', $rules['from_location']);
        $this->assertContains('string', $rules['from_location']);
        $this->assertContains('max:200', $rules['from_location']);
        
        $this->assertContains('nullable', $rules['to_location']);
        $this->assertContains('string', $rules['to_location']);
        $this->assertContains('max:200', $rules['to_location']);
        
        $this->assertContains('nullable', $rules['from_user_id']);
        $this->assertContains('exists:users,id', $rules['from_user_id']);
        
        $this->assertContains('nullable', $rules['to_user_id']);
        $this->assertContains('exists:users,id', $rules['to_user_id']);
        
        $this->assertContains('required', $rules['transfer_reason']);
        $this->assertContains('string', $rules['transfer_reason']);
        $this->assertContains('max:500', $rules['transfer_reason']);
        
        $this->assertContains('required', $rules['transfer_date']);
        $this->assertContains('date', $rules['transfer_date']);
        
        $this->assertContains('required', $rules['status']);
        $this->assertContains('string', $rules['status']);
        $this->assertContains('in:pending,completed,cancelled', $rules['status']);
    }

    /** @test */
    public function it_can_have_null_location_fields()
    {
        $asset = Asset::factory()->create();
        
        $transfer = AssetTransfer::create([
            'asset_id' => $asset->id,
            'from_location' => null,
            'to_location' => null,
            'from_user_id' => null,
            'to_user_id' => null,
            'transfer_reason' => 'System maintenance',
            'transfer_date' => '2024-01-15',
            'status' => 'pending'
        ]);

        $this->assertInstanceOf(AssetTransfer::class, $transfer);
        $this->assertNull($transfer->from_location);
        $this->assertNull($transfer->to_location);
        $this->assertNull($transfer->from_user_id);
        $this->assertNull($transfer->to_user_id);
    }

    /** @test */
    public function it_validates_status_values()
    {
        $rules = AssetTransfer::validationRules();
        
        $this->assertContains('in:pending,completed,cancelled', $rules['status']);
    }

    /** @test */
    public function it_casts_transfer_date_properly()
    {
        $asset = Asset::factory()->create();
        
        $transfer = AssetTransfer::create([
            'asset_id' => $asset->id,
            'transfer_reason' => 'Test transfer',
            'transfer_date' => '2024-01-15',
            'status' => 'pending'
        ]);

        $this->assertInstanceOf(Carbon::class, $transfer->transfer_date);
        $this->assertEquals('2024-01-15', $transfer->transfer_date->format('Y-m-d'));
    }

    /** @test */
    public function it_can_be_updated()
    {
        $transfer = AssetTransfer::factory()->create([
            'status' => 'pending',
            'transfer_reason' => 'Initial reason'
        ]);

        $transfer->update([
            'status' => 'completed',
            'transfer_reason' => 'Updated reason'
        ]);

        $this->assertEquals('completed', $transfer->status);
        $this->assertEquals('Updated reason', $transfer->transfer_reason);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $transfer = AssetTransfer::factory()->create();
        $transferId = $transfer->id;

        $transfer->delete();

        $this->assertDatabaseMissing('asset_transfers', ['id' => $transferId]);
    }

    /** @test */
    public function it_maintains_relationship_integrity()
    {
        $asset = Asset::factory()->create();
        $fromUser = User::factory()->create();
        $toUser = User::factory()->create();
        $processedBy = User::factory()->create();

        $transfer = AssetTransfer::factory()->create([
            'asset_id' => $asset->id,
            'from_user_id' => $fromUser->id,
            'to_user_id' => $toUser->id,
            'processed_by' => $processedBy->id
        ]);

        $this->assertEquals($asset->id, $transfer->asset->id);
        $this->assertEquals($fromUser->id, $transfer->fromUser->id);
        $this->assertEquals($toUser->id, $transfer->toUser->id);
        $this->assertEquals($processedBy->id, $transfer->processedBy->id);
    }

    /** @test */
    public function it_has_timestamps()
    {
        $transfer = AssetTransfer::factory()->create();

        $this->assertNotNull($transfer->created_at);
        $this->assertNotNull($transfer->updated_at);
        $this->assertInstanceOf(Carbon::class, $transfer->created_at);
        $this->assertInstanceOf(Carbon::class, $transfer->updated_at);
    }
}