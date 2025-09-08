<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create permissions
        Permission::create(['name' => 'view-users']);
        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'edit-user']);
        Permission::create(['name' => 'delete-user']);
        Permission::create(['name' => 'reset-password']);
        
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);
        
        // Assign permissions to admin role
        $adminRole->givePermissionTo([
            'view-users', 'create-user', 'edit-user', 'delete-user', 'reset-password'
        ]);
        
        // Create users
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');
        
        $this->user = User::factory()->create();
        $this->user->assignRole('user');
    }

    /** @test */
    public function it_can_display_users_index_page()
    {
        $this->actingAs($this->adminUser);
        
        $response = $this->get(route('users.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('user-management.index');
    }

    /** @test */
    public function it_can_return_users_datatable_data()
    {
        $this->actingAs($this->adminUser);
        
        $response = $this->get(route('users.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data'
        ]);
    }

    /** @test */
    public function it_can_display_create_user_form()
    {
        $this->actingAs($this->adminUser);
        
        $response = $this->get(route('users.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('user-management.create');
        $response->assertViewHas('roles');
    }

    /** @test */
    public function it_can_store_a_new_user()
    {
        $this->actingAs($this->adminUser);
        
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '1234567890',
            'location' => 'New York',
            'about_me' => 'Software Developer',
            'roles' => ['user']
        ];
        
        $response = $this->post(route('users.store'), $userData);
        
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);
        
        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue($user->hasRole('user'));
    }

    /** @test */
    public function it_validates_required_fields_when_storing_user()
    {
        $this->actingAs($this->adminUser);
        
        $response = $this->post(route('users.store'), []);
        
        $response->assertSessionHasErrors(['name', 'email', 'password', 'roles']);
    }

    /** @test */
    public function it_validates_unique_email_when_storing_user()
    {
        $this->actingAs($this->adminUser);
        
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);
        
        $userData = [
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles' => ['user']
        ];
        
        $response = $this->post(route('users.store'), $userData);
        
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function it_can_display_user_details()
    {
        $this->actingAs($this->adminUser);
        
        $response = $this->get(route('users.show', $this->user));
        
        $response->assertStatus(200);
        $response->assertViewIs('user-management.show');
        $response->assertViewHas('user', $this->user);
    }

    /** @test */
    public function it_can_display_edit_user_form()
    {
        $this->actingAs($this->adminUser);
        
        $response = $this->get(route('users.edit', $this->user));
        
        $response->assertStatus(200);
        $response->assertViewIs('user-management.edit');
        $response->assertViewHas('user', $this->user);
        $response->assertViewHas('roles');
    }

    /** @test */
    public function it_can_update_user()
    {
        $this->actingAs($this->adminUser);
        
        $updateData = [
            'name' => 'Updated Name',
            'email' => $this->user->email,
            'phone' => '9876543210',
            'location' => 'Updated Location',
            'about_me' => 'Updated About Me',
            'roles' => ['admin']
        ];
        
        $response = $this->put(route('users.update', $this->user), $updateData);
        
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        
        $this->user->refresh();
        $this->assertEquals('Updated Name', $this->user->name);
        $this->assertEquals('9876543210', $this->user->phone);
        $this->assertTrue($this->user->hasRole('admin'));
    }

    /** @test */
    public function it_can_update_user_password()
    {
        $this->actingAs($this->adminUser);
        
        $updateData = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'roles' => ['user']
        ];
        
        $response = $this->put(route('users.update', $this->user), $updateData);
        
        $response->assertRedirect(route('users.index'));
        
        $this->user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->user->password));
    }

    /** @test */
    public function it_can_delete_user()
    {
        $this->actingAs($this->adminUser);
        
        $userToDelete = User::factory()->create();
        
        $response = $this->delete(route('users.destroy', $userToDelete));
        
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    /** @test */
    public function it_prevents_user_from_deleting_themselves()
    {
        $this->actingAs($this->adminUser);
        
        $response = $this->delete(route('users.destroy', $this->adminUser));
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        $this->assertDatabaseHas('users', ['id' => $this->adminUser->id]);
    }

    /** @test */
    public function it_can_display_reset_password_form()
    {
        $this->actingAs($this->adminUser);
        
        $response = $this->get(route('users.reset-password', $this->user));
        
        $response->assertStatus(200);
        $response->assertViewIs('user-management.reset-password');
        $response->assertViewHas('user', $this->user);
    }

    /** @test */
    public function it_can_update_user_password_via_reset()
    {
        $this->actingAs($this->adminUser);
        
        $passwordData = [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ];
        
        $response = $this->put(route('users.update-password', $this->user), $passwordData);
        
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        
        $this->user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->user->password));
    }

    /** @test */
    public function it_requires_permission_to_access_user_management()
    {
        $unauthorizedUser = User::factory()->create();
        
        $this->actingAs($unauthorizedUser);
        
        $response = $this->get(route('users.index'));
        $response->assertStatus(403);
        
        $response = $this->get(route('users.create'));
        $response->assertStatus(403);
        
        $response = $this->post(route('users.store'), []);
        $response->assertStatus(403);
    }
}