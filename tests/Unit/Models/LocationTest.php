<?php

namespace Tests\Unit\Models;

use App\Models\Location;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created()
    {
        $location = Location::create([
            'name' => 'Main Office',
            'address' => '123 Business St, City, State 12345'
        ]);

        $this->assertInstanceOf(Location::class, $location);
        $this->assertEquals('Main Office', $location->name);
        $this->assertEquals('123 Business St, City, State 12345', $location->address);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = ['name', 'address'];
        $location = new Location();

        $this->assertEquals($fillable, $location->getFillable());
    }

    /** @test */
    public function it_casts_timestamps_to_datetime()
    {
        $location = new Location();
        $casts = $location->getCasts();

        $this->assertArrayHasKey('created_at', $casts);
        $this->assertArrayHasKey('updated_at', $casts);
        $this->assertEquals('datetime', $casts['created_at']);
        $this->assertEquals('datetime', $casts['updated_at']);
    }

    /** @test */
    public function it_has_many_assets()
    {
        $location = Location::factory()->create();
        $asset1 = Asset::factory()->create(['location_id' => $location->id]);
        $asset2 = Asset::factory()->create(['location_id' => $location->id]);

        $this->assertInstanceOf(Collection::class, $location->assets);
        $this->assertCount(2, $location->assets);
        $this->assertTrue($location->assets->contains($asset1));
        $this->assertTrue($location->assets->contains($asset2));
    }

    /** @test */
    public function it_has_many_users()
    {
        $location = Location::factory()->create();
        $user1 = User::factory()->create(['location_id' => $location->id]);
        $user2 = User::factory()->create(['location_id' => $location->id]);

        $this->assertInstanceOf(Collection::class, $location->users);
        $this->assertCount(2, $location->users);
        $this->assertTrue($location->users->contains($user1));
        $this->assertTrue($location->users->contains($user2));
    }

    /** @test */
    public function it_has_validation_rules()
    {
        $rules = Location::validationRules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('address', $rules);
        
        $this->assertContains('required', $rules['name']);
        $this->assertContains('string', $rules['name']);
        $this->assertContains('max:255', $rules['name']);
        $this->assertContains('unique:locations,name', $rules['name']);
        
        $this->assertContains('nullable', $rules['address']);
        $this->assertContains('string', $rules['address']);
        $this->assertContains('max:500', $rules['address']);
    }

    /** @test */
    public function it_validates_name_uniqueness()
    {
        Location::factory()->create(['name' => 'Main Office']);
        
        $rules = Location::validationRules();
        
        $this->assertContains('unique:locations,name', $rules['name']);
    }

    /** @test */
    public function it_allows_null_address()
    {
        $location = Location::create([
            'name' => 'Remote Location',
            'address' => null
        ]);

        $this->assertInstanceOf(Location::class, $location);
        $this->assertNull($location->address);
    }

    /** @test */
    public function it_can_be_deleted_when_no_assets_or_users_exist()
    {
        $location = Location::factory()->create();
        $locationId = $location->id;

        $location->delete();

        $this->assertDatabaseMissing('locations', ['id' => $locationId]);
    }

    /** @test */
    public function it_maintains_relationship_integrity_with_assets()
    {
        $location = Location::factory()->create();
        $asset = Asset::factory()->create(['location_id' => $location->id]);

        $this->assertEquals($location->id, $asset->location_id);
        $this->assertTrue($location->assets->contains($asset));
    }

    /** @test */
    public function it_maintains_relationship_integrity_with_users()
    {
        $location = Location::factory()->create();
        $user = User::factory()->create(['location_id' => $location->id]);

        $this->assertEquals($location->id, $user->location_id);
        $this->assertTrue($location->users->contains($user));
    }

    /** @test */
    public function it_has_timestamps()
    {
        $location = Location::factory()->create();

        $this->assertNotNull($location->created_at);
        $this->assertNotNull($location->updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $location->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $location->updated_at);
    }

    /** @test */
    public function it_can_update_attributes()
    {
        $location = Location::factory()->create([
            'name' => 'Old Name',
            'address' => 'Old Address'
        ]);

        $location->update([
            'name' => 'New Name',
            'address' => 'New Address'
        ]);

        $this->assertEquals('New Name', $location->name);
        $this->assertEquals('New Address', $location->address);
    }

    /** @test */
    public function it_can_find_by_name()
    {
        $location = Location::factory()->create(['name' => 'Unique Location']);

        $found = Location::where('name', 'Unique Location')->first();

        $this->assertInstanceOf(Location::class, $found);
        $this->assertEquals($location->id, $found->id);
    }
}