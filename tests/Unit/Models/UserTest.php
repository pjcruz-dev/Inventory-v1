<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Asset;
use App\Models\AssetTransfer;
use App\Models\AuditTrail;
use App\Models\PrintLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;


class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

    }

    /** @test */
    public function it_can_create_a_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'phone' => '1234567890',
            'location' => 'New York',
            'about_me' => 'Software Developer',
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $user = new User();
        $expected = [
            'name',
            'email',
            'password',
            'phone',
            'location',
            'about_me',
        ];

        $this->assertEquals($expected, $user->getFillable());
    }

    /** @test */
    public function it_hides_sensitive_attributes()
    {
        $user = User::factory()->create();
        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    /** @test */
    public function it_casts_email_verified_at_to_datetime()
    {
        $user = User::factory()->create([
            'email_verified_at' => '2023-01-01 12:00:00'
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $user->email_verified_at);
    }

    /** @test */
    public function it_has_assigned_assets_relationship()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create(['assigned_to_user_id' => $user->id]);

        $this->assertTrue($user->assignedAssets()->exists());
        $this->assertEquals($asset->id, $user->assignedAssets->first()->id);
    }

    /** @test */
    public function it_has_created_assets_relationship()
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create(['created_by' => $user->id]);

        $this->assertTrue($user->createdAssets()->exists());
        $this->assertEquals($asset->id, $user->createdAssets->first()->id);
    }

    /** @test */
    public function it_has_transfers_from_relationship()
    {
        $user = User::factory()->create();
        $transfer = AssetTransfer::factory()->create(['from_user_id' => $user->id]);

        $this->assertTrue($user->transfersFrom()->exists());
        $this->assertEquals($transfer->id, $user->transfersFrom->first()->id);
    }

    /** @test */
    public function it_has_transfers_to_relationship()
    {
        $user = User::factory()->create();
        $transfer = AssetTransfer::factory()->create(['to_user_id' => $user->id]);

        $this->assertTrue($user->transfersTo()->exists());
        $this->assertEquals($transfer->id, $user->transfersTo->first()->id);
    }

    /** @test */
    public function it_has_processed_transfers_relationship()
    {
        $user = User::factory()->create();
        $transfer = AssetTransfer::factory()->create(['processed_by' => $user->id]);

        $this->assertTrue($user->processedTransfers()->exists());
        $this->assertEquals($transfer->id, $user->processedTransfers->first()->id);
    }

    /** @test */
    public function it_has_print_logs_relationship()
    {
        $user = User::factory()->create();
        $printLog = PrintLog::factory()->create(['printed_by' => $user->id]);

        $this->assertTrue($user->printLogs()->exists());
        $this->assertEquals($printLog->id, $user->printLogs->first()->id);
    }

    /** @test */
    public function it_has_audit_trails_relationship()
    {
        $user = User::factory()->create();
        $auditTrail = AuditTrail::factory()->create(['performed_by' => $user->id]);

        $this->assertTrue($user->auditTrails()->exists());
        $this->assertEquals($auditTrail->id, $user->auditTrails->first()->id);
    }

    /** @test */
    public function it_returns_correct_validation_rules_for_new_user()
    {
        $rules = User::validationRules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);
        $this->assertContains('required', $rules['name']);
        $this->assertContains('required', $rules['email']);
        $this->assertContains('required', $rules['password']);
    }

    /** @test */
    public function it_returns_correct_validation_rules_for_existing_user()
    {
        $user = User::factory()->create();
        $rules = User::validationRules($user->id);

        $this->assertArrayHasKey('password', $rules);
        $this->assertContains('nullable', $rules['password']);
    }

    /** @test */

}