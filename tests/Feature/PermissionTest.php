<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_super_admin_has_all_permissions(): void
    {
        $user = User::factory()->create();
        $role = Role::where('name', 'Super Admin')->first();
        $user->assignRole($role);

        $this->assertTrue($user->hasPermissionTo('view_posts'));
        $this->assertTrue($user->hasPermissionTo('manage_settings'));
        $this->assertTrue($user->hasPermissionTo('manage_users'));
        $this->assertTrue($user->hasRole('Super Admin'));
    }

    public function test_editor_role_exists(): void
    {
        $role = Role::where('name', 'Editor')->first();
        $this->assertNotNull($role);
        $this->assertTrue($role->hasPermissionTo('edit_posts'));
        $this->assertFalse($role->hasPermissionTo('manage_users'));
    }
}
