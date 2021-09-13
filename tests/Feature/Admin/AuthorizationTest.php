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
        factory(Entity::class, 2)->create();
        $testUrl = '/admin/entities/1/edit';
        $testUrl2 = '/admin/entities/2/edit';

        // 超管可直接访问
        $response = $this->actingAs($this->superUser, 'admin')->get($testUrl);
        $response->assertStatus(200);
        // 普通用户无权限
        $response = $this->actingAs($this->user, 'admin')->get($testUrl);
        $response->assertStatus(401);

        // 授权后可访问
        $response = $this->actingAs($this->superUser, 'admin')->post(
            '/admin/menus',
            [
                'name' => '编辑模型页面',
                'route' => 'admin::entity.edit',
            ]
        );
        $response->assertStatus(200);
        $response = $this->actingAs($this->superUser, 'admin')->post('/admin/roles', ['name' => 'entity']);
        $response->assertStatus(200);
        $response = $this->actingAs($this->superUser, 'admin')->put(
            '/admin/roles/1/permission',
            ['permission' => [1 => '编辑模型页面']]
        );
        $response->assertStatus(200);
        $response = $this->actingAs($this->superUser, 'admin')->put(
            '/admin/admin_user/' . $this->user->id . '/role',
            ['role' => [1 => 'entity']]
        );
        $response->assertStatus(200);
        $response = $this->actingAs($this->user, 'admin')->get($testUrl);
        $response->assertStatus(200);

        // 带参数路由
        $response = $this->actingAs($this->superUser, 'admin')->post(
            '/admin/menus',
            [
                'route_params' => 'id:1',
                'name' => 'ID=1编辑模型页面',
                'route' => 'admin::entity.edit',
            ]
        );
        $response->assertStatus(200);
        $response = $this->actingAs($this->superUser, 'admin')->post(
            '/admin/menus',
            [
                'route_params' => 'id:2',
                'name' => 'ID=2编辑模型页面',
                'route' => 'admin::entity.edit',
            ]
        );
        $response->assertStatus(200);
        // 参数ID为1、为2都可访问
        $response = $this->actingAs($this->user, 'admin')->get($testUrl);
        $response->assertStatus(200);
        $response = $this->actingAs($this->user, 'admin')->get($testUrl2);
        $response->assertStatus(200);

        // 参数ID为1可访问，为2不可访问
        $response = $this->actingAs($this->superUser, 'admin')->put(
            '/admin/roles/1/permission',
            ['permission' => [2 => 'ID=1编辑模型页面']]
        );
        $response->assertStatus(200);
        $response = $this->actingAs($this->user, 'admin')->get($testUrl);
        $response->assertStatus(200);
        $response = $this->actingAs($this->user, 'admin')->get($testUrl2);
        $response->assertStatus(401);

        // 参数ID为2可访问，为1不可访问
        $response = $this->actingAs($this->superUser, 'admin')->put(
            '/admin/roles/1/permission',
            ['permission' => [3 => 'ID=2编辑模型页面']]
        );
        $response->assertStatus(200);
        $response = $this->actingAs($this->user, 'admin')->get($testUrl2);
        $response->assertStatus(200);
        $response = $this->actingAs($this->user, 'admin')->get($testUrl);
        $response->assertStatus(401);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
