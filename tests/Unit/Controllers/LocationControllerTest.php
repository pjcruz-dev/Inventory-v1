<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\LocationController;
use App\Models\Location;
use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class LocationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create permissions
        Permission::create(['name' => 'view-locations']);
        Permission::create(['name' => 'create-location']);
        Permission::create(['name' => 'edit-location']);
        Permission::create(['name' => 'delete-location']);
        
        // Create role with permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(['view-locations', 'create-location', 'edit-location', 'delete-location']);
        
        // Create user and assign role
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
        
        $this->controller = new LocationController();
    }

    /** @test */
    public function it_displays_locations_index_page()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('locations.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('locations.index');
    }

    /** @test */
    public function it_returns_locations_datatable_data()
    {
        $this->actingAs($this->user);
        
        Location::factory()->count(3)->create();
        
        $response = $this->get(route('locations.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'address',
                    'assets_count',
                    'address_display',
                    'created_at_formatted',
                    'actions'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_displays_create_location_form()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('locations.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('locations.create');
    }

    /** @test */
    public function it_stores_new_location_successfully()
    {
        $this->actingAs($this->user);
        
        $locationData = [
            'name' => 'Test Location',
            'address' => '123 Test Street, Test City'
        ];
        
        $response = $this->post(route('locations.store'), $locationData);
        
        $response->assertRedirect(route('locations.index'));
        $response->assertSessionHas('success', 'Location created successfully.');
        
        $this->assertDatabaseHas('locations', $locationData);
    }

    /** @test */
    public function it_validates_location_creation_data()
    {
        $this->actingAs($this->user);
        
        // Test required name
        $response = $this->post(route('locations.store'), []);
        $response->assertSessionHasErrors(['name']);
        
        // Test unique name
        Location::factory()->create(['name' => 'Existing Location']);
        $response = $this->post(route('locations.store'), [
            'name' => 'Existing Location',
            'address' => 'Some address'
        ]);
        $response->assertSessionHasErrors(['name']);
        
        // Test name max length
        $response = $this->post(route('locations.store'), [
            'name' => str_repeat('a', 256),
            'address' => 'Some address'
        ]);
        $response->assertSessionHasErrors(['name']);
        
        // Test address max length
        $response = $this->post(route('locations.store'), [
            'name' => 'Valid Name',
            'address' => str_repeat('a', 501)
        ]);
        $response->assertSessionHasErrors(['address']);
    }

    /** @test */
    public function it_displays_location_details()
    {
        $this->actingAs($this->user);
        
        $location = Location::factory()->create();
        
        $response = $this->get(route('locations.show', $location));
        
        $response->assertStatus(200);
        $response->assertViewIs('locations.show');
        $response->assertViewHas('location', $location);
    }

    /** @test */
    public function it_displays_edit_location_form()
    {
        $this->actingAs($this->user);
        
        $location = Location::factory()->create();
        
        $response = $this->get(route('locations.edit', $location));
        
        $response->assertStatus(200);
        $response->assertViewIs('locations.edit');
        $response->assertViewHas('location', $location);
    }

    /** @test */
    public function it_updates_location_successfully()
    {
        $this->actingAs($this->user);
        
        $location = Location::factory()->create([
            'name' => 'Old Name',
            'address' => 'Old Address'
        ]);
        
        $updateData = [
            'name' => 'Updated Name',
            'address' => 'Updated Address'
        ];
        
        $response = $this->put(route('locations.update', $location), $updateData);
        
        $response->assertRedirect(route('locations.index'));
        $response->assertSessionHas('success', 'Location updated successfully.');
        
        $this->assertDatabaseHas('locations', array_merge(['id' => $location->id], $updateData));
    }

    /** @test */
    public function it_validates_location_update_data()
    {
        $this->actingAs($this->user);
        
        $location = Location::factory()->create(['name' => 'Original Name']);
        $otherLocation = Location::factory()->create(['name' => 'Other Location']);
        
        // Test required name
        $response = $this->put(route('locations.update', $location), ['name' => '']);
        $response->assertSessionHasErrors(['name']);
        
        // Test unique name (should fail when trying to use another location's name)
        $response = $this->put(route('locations.update', $location), [
            'name' => 'Other Location',
            'address' => 'Some address'
        ]);
        $response->assertSessionHasErrors(['name']);
        
        // Test that same name is allowed (no change)
        $response = $this->put(route('locations.update', $location), [
            'name' => 'Original Name',
            'address' => 'Updated address'
        ]);
        $response->assertRedirect(route('locations.index'));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function it_deletes_location_successfully_when_no_assets_assigned()
    {
        $this->actingAs($this->user);
        
        $location = Location::factory()->create();
        
        $response = $this->delete(route('locations.destroy', $location));
        
        $response->assertRedirect(route('locations.index'));
        $response->assertSessionHas('success', 'Location deleted successfully.');
        
        $this->assertDatabaseMissing('locations', ['id' => $location->id]);
    }

    /** @test */
    public function it_prevents_deletion_when_location_has_assets()
    {
        $this->actingAs($this->user);
        
        $location = Location::factory()->create();
        Asset::factory()->create(['location_id' => $location->id]);
        
        $response = $this->delete(route('locations.destroy', $location));
        
        $response->assertRedirect(route('locations.index'));
        $response->assertSessionHas('error', 'Cannot delete location that has assets assigned to it.');
        
        $this->assertDatabaseHas('locations', ['id' => $location->id]);
    }

    /** @test */
    public function it_requires_proper_permissions_for_actions()
    {
        // Create user without permissions
        $userWithoutPermissions = User::factory()->create();
        
        $location = Location::factory()->create();
        
        $this->actingAs($userWithoutPermissions);
        
        // Test view permission
        $response = $this->get(route('locations.index'));
        $response->assertStatus(403);
        
        // Test create permission
        $response = $this->get(route('locations.create'));
        $response->assertStatus(403);
        
        // Test store permission
        $response = $this->post(route('locations.store'), ['name' => 'Test']);
        $response->assertStatus(403);
        
        // Test edit permission
        $response = $this->get(route('locations.edit', $location));
        $response->assertStatus(403);
        
        // Test update permission
        $response = $this->put(route('locations.update', $location), ['name' => 'Updated']);
        $response->assertStatus(403);
        
        // Test delete permission
        $response = $this->delete(route('locations.destroy', $location));
        $response->assertStatus(403);
    }

    /** @test */
    public function it_loads_assets_relationship_in_show_method()
    {
        $this->actingAs($this->user);
        
        $location = Location::factory()->create();
        Asset::factory()->count(2)->create(['location_id' => $location->id]);
        
        $response = $this->get(route('locations.show', $location));
        
        $response->assertStatus(200);
        
        $viewLocation = $response->viewData('location');
        $this->assertTrue($viewLocation->relationLoaded('assets'));
        $this->assertCount(2, $viewLocation->assets);
    }

    /** @test */
    public function datatable_includes_assets_count()
    {
        $this->actingAs($this->user);
        
        $location = Location::factory()->create();
        Asset::factory()->count(3)->create(['location_id' => $location->id]);
        
        $response = $this->get(route('locations.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals(3, $data[0]['assets_count']);
    }

    /** @test */
    public function datatable_handles_null_address_display()
    {
        $this->actingAs($this->user);
        
        Location::factory()->create(['address' => null]);
        
        $response = $this->get(route('locations.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals('N/A', $data[0]['address_display']);
    }
}