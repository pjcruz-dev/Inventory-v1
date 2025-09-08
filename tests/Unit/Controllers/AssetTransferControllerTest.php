<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\AssetTransferController;
use App\Models\AssetTransfer;
use App\Models\Asset;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Mockery;

class AssetTransferControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $controller;
    protected $auditService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create user for testing
        $this->user = User::factory()->create();

        
        // Mock AuditService
        $this->auditService = Mockery::mock(AuditService::class);
        $this->app->instance(AuditService::class, $this->auditService);
        
        $this->controller = new AssetTransferController($this->auditService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_displays_asset_transfers_index_page()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('asset-transfers.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('asset-transfers.index');
        $response->assertViewHas('assets');
        $response->assertViewHas('statuses');
    }

    /** @test */
    public function it_returns_asset_transfers_datatable_data()
    {
        $this->actingAs($this->user);
        
        $asset = Asset::factory()->create();
        AssetTransfer::factory()->count(3)->create(['asset_id' => $asset->id]);
        
        $response = $this->get(route('asset-transfers.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'asset_id',
                    'asset_tag',
                    'asset_name',
                    'from_user',
                    'to_user',
                    'transfer_reason',
                    'transfer_date',
                    'status',
                    'status_badge',
                    'actions'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_displays_create_asset_transfer_form()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('asset-transfers.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('asset-transfers.create');
        $response->assertViewHas('assets');
        $response->assertViewHas('users');
        $response->assertViewHas('statuses');
    }

    /** @test */
    public function it_stores_new_asset_transfer_successfully()
    {
        $this->actingAs($this->user);
        
        $asset = Asset::factory()->create();
        $fromUser = User::factory()->create();
        $toUser = User::factory()->create();
        
        $this->auditService->shouldReceive('logCreated')->once();
        $this->auditService->shouldReceive('logTransferred')->once();
        
        $transferData = [
            'asset_id' => $asset->id,
            'from_location' => 'Office A',
            'to_location' => 'Office B',
            'from_user_id' => $fromUser->id,
            'to_user_id' => $toUser->id,
            'transfer_reason' => 'Employee relocation',
            'status' => 'pending'
        ];
        
        $response = $this->post(route('asset-transfers.store'), $transferData);
        
        $response->assertRedirect(route('asset-transfers.index'));
        $response->assertSessionHas('success', 'Asset transfer created successfully.');
        
        $this->assertDatabaseHas('asset_transfers', [
            'asset_id' => $asset->id,
            'from_user_id' => $fromUser->id,
            'to_user_id' => $toUser->id,
            'transfer_reason' => 'Employee relocation',
            'status' => 'pending',
            'processed_by' => $this->user->id
        ]);
    }

    /** @test */
    public function it_validates_asset_transfer_creation_data()
    {
        $this->actingAs($this->user);
        
        // Test required asset_id
        $response = $this->post(route('asset-transfers.store'), []);
        $response->assertSessionHasErrors(['asset_id', 'status']);
        
        // Test invalid asset_id
        $response = $this->post(route('asset-transfers.store'), [
            'asset_id' => 999999,
            'status' => 'pending'
        ]);
        $response->assertSessionHasErrors(['asset_id']);
        
        // Test invalid status
        $asset = Asset::factory()->create();
        $response = $this->post(route('asset-transfers.store'), [
            'asset_id' => $asset->id,
            'status' => 'invalid_status'
        ]);
        $response->assertSessionHasErrors(['status']);
        
        // Test invalid user IDs
        $response = $this->post(route('asset-transfers.store'), [
            'asset_id' => $asset->id,
            'from_user_id' => 999999,
            'to_user_id' => 999999,
            'status' => 'pending'
        ]);
        $response->assertSessionHasErrors(['from_user_id', 'to_user_id']);
    }

    /** @test */
    public function it_completes_transfer_and_updates_asset_when_status_is_completed()
    {
        $this->actingAs($this->user);
        
        $asset = Asset::factory()->create([
            'status' => 'available',
            'assigned_to_user_id' => null,
            'location' => 'Office A'
        ]);
        $toUser = User::factory()->create();
        
        $this->auditService->shouldReceive('logCreated')->once();
        $this->auditService->shouldReceive('logTransferred')->once();
        
        $transferData = [
            'asset_id' => $asset->id,
            'to_location' => 'Office B',
            'to_user_id' => $toUser->id,
            'transfer_reason' => 'Assignment',
            'status' => 'completed'
        ];
        
        $response = $this->post(route('asset-transfers.store'), $transferData);
        
        $response->assertRedirect(route('asset-transfers.index'));
        
        // Check that asset was updated
        $asset->refresh();
        $this->assertEquals('Office B', $asset->location);
        $this->assertEquals($toUser->id, $asset->assigned_to_user_id);
        $this->assertEquals('assigned', $asset->status);
    }

    /** @test */
    public function it_displays_asset_transfer_details()
    {
        $this->actingAs($this->user);
        
        $transfer = AssetTransfer::factory()->create();
        
        $response = $this->get(route('asset-transfers.show', $transfer));
        
        $response->assertStatus(200);
        $response->assertViewIs('asset-transfers.show');
        $response->assertViewHas('assetTransfer');
    }

    /** @test */
    public function it_displays_edit_form_for_pending_transfers_only()
    {
        $this->actingAs($this->user);
        
        $pendingTransfer = AssetTransfer::factory()->create(['status' => 'pending']);
        $completedTransfer = AssetTransfer::factory()->create(['status' => 'completed']);
        
        // Should allow editing pending transfer
        $response = $this->get(route('asset-transfers.edit', $pendingTransfer));
        $response->assertStatus(200);
        $response->assertViewIs('asset-transfers.edit');
        
        // Should not allow editing completed transfer
        $response = $this->get(route('asset-transfers.edit', $completedTransfer));
        $response->assertRedirect(route('asset-transfers.show', $completedTransfer));
        $response->assertSessionHas('error', 'Only pending transfers can be edited.');
    }

    /** @test */
    public function it_updates_pending_asset_transfer_successfully()
    {
        $this->actingAs($this->user);
        
        $transfer = AssetTransfer::factory()->create([
            'status' => 'pending',
            'transfer_reason' => 'Original reason'
        ]);
        
        $this->auditService->shouldReceive('logUpdated')->once();
        
        $updateData = [
            'from_location' => 'Updated From Location',
            'to_location' => 'Updated To Location',
            'transfer_reason' => 'Updated reason',
            'status' => 'completed'
        ];
        
        $response = $this->put(route('asset-transfers.update', $transfer), $updateData);
        
        $response->assertRedirect(route('asset-transfers.index'));
        $response->assertSessionHas('success', 'Asset transfer updated successfully.');
        
        $this->assertDatabaseHas('asset_transfers', array_merge(['id' => $transfer->id], $updateData));
    }

    /** @test */
    public function it_prevents_updating_non_pending_transfers()
    {
        $this->actingAs($this->user);
        
        $completedTransfer = AssetTransfer::factory()->create(['status' => 'completed']);
        
        $response = $this->put(route('asset-transfers.update', $completedTransfer), [
            'transfer_reason' => 'Updated reason',
            'status' => 'cancelled'
        ]);
        
        $response->assertRedirect(route('asset-transfers.show', $completedTransfer));
        $response->assertSessionHas('error', 'Only pending transfers can be updated.');
    }

    /** @test */
    public function it_deletes_asset_transfer_successfully()
    {
        $this->actingAs($this->user);
        
        $transfer = AssetTransfer::factory()->create();
        
        $this->auditService->shouldReceive('logDeleted')->once();
        
        $response = $this->delete(route('asset-transfers.destroy', $transfer));
        
        $response->assertRedirect(route('asset-transfers.index'));
        $response->assertSessionHas('success', 'Asset transfer deleted successfully.');
        
        $this->assertDatabaseMissing('asset_transfers', ['id' => $transfer->id]);
    }



    /** @test */
    public function it_handles_json_responses_for_ajax_requests()
    {
        $this->actingAs($this->user);
        
        $asset = Asset::factory()->create();
        
        $this->auditService->shouldReceive('logCreated')->once();
        $this->auditService->shouldReceive('logTransferred')->once();
        
        $transferData = [
            'asset_id' => $asset->id,
            'transfer_reason' => 'Test transfer',
            'status' => 'pending'
        ];
        
        $response = $this->postJson(route('asset-transfers.store'), $transferData);
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Asset transfer created successfully.',
            'redirect' => route('asset-transfers.index')
        ]);
    }

    /** @test */
    public function it_handles_database_transaction_rollback_on_error()
    {
        $this->actingAs($this->user);
        
        // Mock audit service to throw exception
        $this->auditService->shouldReceive('logCreated')->andThrow(new \Exception('Audit failed'));
        
        $asset = Asset::factory()->create();
        
        $transferData = [
            'asset_id' => $asset->id,
            'transfer_reason' => 'Test transfer',
            'status' => 'pending'
        ];
        
        $response = $this->post(route('asset-transfers.store'), $transferData);
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        // Ensure no transfer was created due to rollback
        $this->assertDatabaseMissing('asset_transfers', [
            'asset_id' => $asset->id,
            'transfer_reason' => 'Test transfer'
        ]);
    }

    /** @test */
    public function datatable_displays_correct_status_badges()
    {
        $this->actingAs($this->user);
        
        $asset = Asset::factory()->create();
        AssetTransfer::factory()->create(['asset_id' => $asset->id, 'status' => 'pending']);
        AssetTransfer::factory()->create(['asset_id' => $asset->id, 'status' => 'completed']);
        AssetTransfer::factory()->create(['asset_id' => $asset->id, 'status' => 'cancelled']);
        
        $response = $this->get(route('asset-transfers.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertStringContainsString('badge bg-warning', $data[0]['status_badge']);
        $this->assertStringContainsString('badge bg-success', $data[1]['status_badge']);
        $this->assertStringContainsString('badge bg-danger', $data[2]['status_badge']);
    }

    /** @test */
    public function it_loads_required_relationships_in_show_method()
    {
        $this->actingAs($this->user);
        
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
        
        $response = $this->get(route('asset-transfers.show', $transfer));
        
        $response->assertStatus(200);
        
        $viewTransfer = $response->viewData('assetTransfer');
        $this->assertTrue($viewTransfer->relationLoaded('asset'));
        $this->assertTrue($viewTransfer->relationLoaded('fromUser'));
        $this->assertTrue($viewTransfer->relationLoaded('toUser'));
        $this->assertTrue($viewTransfer->relationLoaded('processedBy'));
    }
}