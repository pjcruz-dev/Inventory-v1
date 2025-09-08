<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\AssetController;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\User;
use App\Models\AssetTransfer;
use App\Services\AuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Mockery;

class AssetControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $assetType;
    protected $auditService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Basic test setup without roles/permissions
        
        // Create user
        $this->user = User::factory()->create();

        
        // Create asset type
        $this->assetType = AssetType::factory()->create();
        
        // Mock audit service
        $this->auditService = Mockery::mock(AuditService::class);
        $this->app->instance(AuditService::class, $this->auditService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_display_assets_index_page()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('assets.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('assets.index');
        $response->assertViewHas('assetTypes');
        $response->assertViewHas('statuses');
    }

    /** @test */
    public function it_can_return_assets_datatable_data()
    {
        $this->actingAs($this->user);
        
        Asset::factory()->create([
            'asset_type_id' => $this->assetType->id,
            'created_by' => $this->user->id
        ]);
        
        $response = $this->get(route('assets.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data'
        ]);
    }

    /** @test */
    public function it_can_display_create_asset_form()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('assets.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('assets.create');
        $response->assertViewHas('assetTypes');
        $response->assertViewHas('users');
        $response->assertViewHas('statuses');
    }

    /** @test */
    public function it_can_store_a_new_asset()
    {
        $this->actingAs($this->user);
        
        $this->auditService->shouldReceive('logCreated')->once();
        
        $assetData = [
            'asset_tag' => 'ASSET001',
            'serial_no' => 'SN123456',
            'asset_type_id' => $this->assetType->id,
            'model' => 'Dell Laptop',
            'manufacturer' => 'Dell',
            'purchase_date' => '2023-01-01',
            'warranty_until' => '2025-01-01',
            'cost' => 1500.00,
            'status' => 'available',
            'location' => 'Office A',
        ];
        
        $response = $this->post(route('assets.store'), $assetData);
        
        $response->assertRedirect(route('assets.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('assets', [
            'asset_tag' => 'ASSET001',
            'serial_no' => 'SN123456',
            'created_by' => $this->user->id
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing_asset()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('assets.store'), []);
        
        $response->assertSessionHasErrors(['asset_tag', 'asset_type_id', 'status']);
    }

    /** @test */
    public function it_validates_unique_asset_tag_when_storing()
    {
        $this->actingAs($this->user);
        
        Asset::factory()->create([
            'asset_tag' => 'EXISTING001',
            'asset_type_id' => $this->assetType->id,
            'created_by' => $this->user->id
        ]);
        
        $assetData = [
            'asset_tag' => 'EXISTING001',
            'asset_type_id' => $this->assetType->id,
            'status' => 'available'
        ];
        
        $response = $this->post(route('assets.store'), $assetData);
        
        $response->assertSessionHasErrors(['asset_tag']);
    }

    /** @test */
    public function it_removes_assigned_user_when_status_is_not_assigned()
    {
        $this->actingAs($this->user);
        
        $this->auditService->shouldReceive('logCreated')->once();
        
        $assignedUser = User::factory()->create();
        
        $assetData = [
            'asset_tag' => 'ASSET002',
            'asset_type_id' => $this->assetType->id,
            'status' => 'available',
            'assigned_to_user_id' => $assignedUser->id
        ];
        
        $response = $this->post(route('assets.store'), $assetData);
        
        $asset = Asset::where('asset_tag', 'ASSET002')->first();
        $this->assertNull($asset->assigned_to_user_id);
    }

    /** @test */
    public function it_can_display_asset_details()
    {
        $this->actingAs($this->user);
        
        $asset = Asset::factory()->create([
            'asset_type_id' => $this->assetType->id,
            'created_by' => $this->user->id
        ]);
        
        $response = $this->get(route('assets.show', $asset));
        
        $response->assertStatus(200);
        $response->assertViewIs('assets.show');
        $response->assertViewHas('asset');
    }

    /** @test */
    public function it_can_display_edit_asset_form()
    {
        $this->actingAs($this->user);
        
        $asset = Asset::factory()->create([
            'asset_type_id' => $this->assetType->id,
            'created_by' => $this->user->id
        ]);
        
        $response = $this->get(route('assets.edit', $asset));
        
        $response->assertStatus(200);
        $response->assertViewIs('assets.edit');
        $response->assertViewHas('asset', $asset);
        $response->assertViewHas('assetTypes');
        $response->assertViewHas('users');
        $response->assertViewHas('statuses');
    }

    /** @test */
    public function it_can_update_asset()
    {
        $this->actingAs($this->user);
        
        $this->auditService->shouldReceive('logUpdated')->once();
        
        $asset = Asset::factory()->create([
            'asset_type_id' => $this->assetType->id,
            'created_by' => $this->user->id,
            'asset_tag' => 'ORIGINAL001'
        ]);
        
        $updateData = [
            'asset_tag' => 'UPDATED001',
            'asset_type_id' => $this->assetType->id,
            'status' => 'in_repair',
            'model' => 'Updated Model'
        ];
        
        $response = $this->put(route('assets.update', $asset), $updateData);
        
        $response->assertRedirect(route('assets.index'));
        $response->assertSessionHas('success');
        
        $asset->refresh();
        $this->assertEquals('UPDATED001', $asset->asset_tag);
        $this->assertEquals('in_repair', $asset->status);
        $this->assertEquals('Updated Model', $asset->model);
    }

    /** @test */
    public function it_creates_transfer_when_assigned_user_changes()
    {
        $this->actingAs($this->user);
        
        $this->auditService->shouldReceive('logUpdated')->once();
        $this->auditService->shouldReceive('logTransferred')->once();
        
        $oldUser = User::factory()->create();
        $newUser = User::factory()->create();
        
        $asset = Asset::factory()->create([
            'asset_type_id' => $this->assetType->id,
            'created_by' => $this->user->id,
            'assigned_to_user_id' => $oldUser->id,
            'status' => 'assigned'
        ]);
        
        $updateData = [
            'asset_tag' => $asset->asset_tag,
            'asset_type_id' => $this->assetType->id,
            'status' => 'assigned',
            'assigned_to_user_id' => $newUser->id
        ];
        
        $response = $this->put(route('assets.update', $asset), $updateData);
        
        $response->assertRedirect(route('assets.index'));
        
        $this->assertDatabaseHas('asset_transfers', [
            'asset_id' => $asset->id,
            'from_user_id' => $oldUser->id,
            'to_user_id' => $newUser->id,
            'status' => 'completed'
        ]);
    }

    /** @test */
    public function it_can_delete_asset_without_peripherals()
    {
        $this->actingAs($this->user);
        
        $this->auditService->shouldReceive('logDeleted')->once();
        
        $asset = Asset::factory()->create([
            'asset_type_id' => $this->assetType->id,
            'created_by' => $this->user->id
        ]);
        
        $response = $this->delete(route('assets.destroy', $asset));
        
        $response->assertRedirect(route('assets.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
    }

    /** @test */
    public function it_prevents_deletion_of_asset_with_peripherals()
    {
        $this->actingAs($this->user);
        
        $asset = Asset::factory()->create([
            'asset_type_id' => $this->assetType->id,
            'created_by' => $this->user->id
        ]);
        
        // Create a peripheral for the asset
        $asset->peripherals()->create([
            'name' => 'Test Peripheral',
            'type' => 'Mouse'
        ]);
        
        $response = $this->delete(route('assets.destroy', $asset));
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        $this->assertDatabaseHas('assets', ['id' => $asset->id]);
    }

    /** @test */
    public function it_allows_access_to_asset_management()
    {
        $unauthorizedUser = User::factory()->create();
        
        $this->actingAs($unauthorizedUser);
        
        $response = $this->get(route('assets.index'));
        $response->assertStatus(403);
        
        $response = $this->get(route('assets.create'));
        $response->assertStatus(403);
        
        $response = $this->post(route('assets.store'), []);
        $response->assertStatus(403);
    }
}