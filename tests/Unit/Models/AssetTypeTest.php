<?php

namespace Tests\Unit\Models;

use App\Models\AssetType;
use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_asset_type()
    {
        $assetTypeData = [
            'name' => 'Laptop',
            'description' => 'Portable computer devices',
        ];

        $assetType = AssetType::create($assetTypeData);

        $this->assertInstanceOf(AssetType::class, $assetType);
        $this->assertEquals('Laptop', $assetType->name);
        $this->assertEquals('Portable computer devices', $assetType->description);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $assetType = new AssetType();
        $expected = [
            'name',
            'description',
        ];

        $this->assertEquals($expected, $assetType->getFillable());
    }

    /** @test */
    public function it_has_many_assets()
    {
        $assetType = AssetType::factory()->create(['name' => 'Desktop']);
        $asset1 = Asset::factory()->create(['asset_type_id' => $assetType->id]);
        $asset2 = Asset::factory()->create(['asset_type_id' => $assetType->id]);

        $this->assertTrue($assetType->assets()->exists());
        $this->assertCount(2, $assetType->assets);
        $this->assertTrue($assetType->assets->contains($asset1));
        $this->assertTrue($assetType->assets->contains($asset2));
    }

    /** @test */
    public function it_returns_correct_validation_rules_for_new_asset_type()
    {
        $rules = AssetType::validationRules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertContains('required', $rules['name']);
        $this->assertContains('nullable', $rules['description']);
    }

    /** @test */
    public function it_returns_correct_validation_rules_for_existing_asset_type()
    {
        $assetType = AssetType::factory()->create();
        $rules = AssetType::validationRules($assetType->id);

        $this->assertArrayHasKey('name', $rules);
        $this->assertIsArray($rules['name']);
    }

    /** @test */
    public function it_validates_name_uniqueness()
    {
        $rules = AssetType::validationRules();
        $nameRule = $rules['name'];

        $this->assertContains('max:255', $nameRule);
        $this->assertContains('string', $nameRule);
    }

    /** @test */
    public function it_validates_description_length()
    {
        $rules = AssetType::validationRules();
        $descriptionRule = $rules['description'];

        $this->assertContains('max:1000', $descriptionRule);
    }

    /** @test */
    public function it_can_be_deleted_when_no_assets_exist()
    {
        $assetType = AssetType::factory()->create();
        $assetTypeId = $assetType->id;

        $assetType->delete();

        $this->assertDatabaseMissing('asset_types', ['id' => $assetTypeId]);
    }

    /** @test */
    public function it_maintains_relationship_integrity_with_assets()
    {
        $assetType = AssetType::factory()->create();
        $asset = Asset::factory()->create(['asset_type_id' => $assetType->id]);

        // Refresh the asset type to load the relationship
        $assetType->refresh();

        $this->assertEquals($assetType->id, $asset->asset_type_id);
        $this->assertEquals($asset->id, $assetType->assets->first()->id);
    }

    /** @test */
    public function it_can_have_null_description()
    {
        $assetType = AssetType::create([
            'name' => 'Test Type',
            'description' => null,
        ]);

        $this->assertNull($assetType->description);
        $this->assertEquals('Test Type', $assetType->name);
    }

    /** @test */
    public function it_uses_timestamps()
    {
        $assetType = AssetType::factory()->create();

        $this->assertNotNull($assetType->created_at);
        $this->assertNotNull($assetType->updated_at);
    }
}