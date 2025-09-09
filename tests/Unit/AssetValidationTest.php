<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class AssetValidationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_asset_can_be_created_with_valid_data()
    {
        // Create asset type manually
        $assetType = AssetType::create([
            'name' => 'Test Type',
            'description' => 'Test Description'
        ]);
        
        $asset = Asset::create([
            'asset_tag' => 'TAG001',
            'serial_number' => 'VALID123',
            'asset_type_id' => $assetType->id,
            'status' => 'available'
        ]);

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('TAG001', $asset->asset_tag);
        $this->assertEquals('VALID123', $asset->serial_number);
        $this->assertEquals('available', $asset->status);
    }

    public function test_asset_relationships_work()
    {
        // Create asset type manually
        $assetType = AssetType::create([
            'name' => 'Laptop',
            'description' => 'Laptop computers'
        ]);
        
        $asset = Asset::create([
            'asset_tag' => 'TAG002',
            'serial_number' => 'DELL123',
            'asset_type_id' => $assetType->id,
            'status' => 'available'
        ]);

        $this->assertEquals('Laptop', $asset->assetType->name);
    }

    public function test_asset_status_values()
    {
        // Create asset type manually
        $assetType = AssetType::create([
            'name' => 'Test Type',
            'description' => 'Test Description'
        ]);
        
        $validStatuses = ['available', 'assigned', 'maintenance', 'retired'];
        
        foreach ($validStatuses as $status) {
            $asset = Asset::create([
                'asset_tag' => 'TAG' . strtoupper($status),
                'serial_number' => 'TEST' . strtoupper($status),
                'asset_type_id' => $assetType->id,
                'status' => $status
            ]);
            
            $this->assertEquals($status, $asset->status);
        }
    }
}
