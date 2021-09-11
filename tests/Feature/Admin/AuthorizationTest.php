<?php

namespace Tests\Feature\Admin;

use App\Model\Admin\AdminUser;
use App\Model\Admin\Entity;
use App\Model\Admin\Menu;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $superUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->superUser = factory(AdminUser::class)->create(['id' => 1]);
        $this->user = factory(AdminUser::class)->create(['id' => 2]);
    }

    public function testUserVistEntityListPage()
    {
        factory(Entity::class, 1)->create();
        $testUrl = '/admin/entities';

        $response = $this->actingAs($this->superUser, 'admin')->get($testUrl);
        $response->assertStatus(200);

        $response = $this->actingAs($this->user, 'admin')->get($testUrl);
        $response->assertStatus(401);

        // 授权后可访问
        $response = $this->actingAs($this->superUser, 'admin')->post(
            '/admin/menus',
            [
                'name' => '模型列表',
                'route' => 'admin::entity.index',
                'url' => '/admin/entities'
            ]
        );
        $response->assertStatus(200);
        $response = $this->actingAs($this->superUser, 'admin')->post('/admin/roles', ['name' => 'entity']);
        $response->assertStatus(200);
        $response = $this->actingAs($this->superUser, 'admin')->put(
            '/admin/roles/1/permission',
            ['permission' => [1 => '模型列表']]
        );
        $response->assertStatus(200);
        $response = $this->actingAs($this->superUser, 'admin')->put(
            '/admin/admin_user/' . $this->user->id . '/role',
            ['role' => [1 => 'entity']]
        );
        $response->assertStatus(200);
        $response = $this->actingAs($this->user, 'admin')->get($testUrl);
        $response->assertStatus(200);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
