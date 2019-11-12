<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Model\Admin\AdminUser;
use App\Model\Admin\Menu;
use Tests\TestCase;

class MenuControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->user = factory(AdminUser::class)->make(['id' => 1]);
    }

    public function testMenusCanBeListed()
    {
        factory(Menu::class, 3)->create();
        $response = $this->actingAs($this->user, 'admin')->get('/admin/menus/list');
        $content = $response->original;

        $response->assertJson(['code' => 0]);
        $this->assertEquals(3, $content['count']);
    }

    public function testMenuDiscovery()
    {
        $response = $this->actingAs($this->user, 'admin')
            ->post('/admin/menus/discovery');

        $response->assertJson(['code' => 0]);
        $this->assertDatabaseHas(
            'menus',
            [
                'route' => 'admin::menu.discovery',
            ]
        );
    }
}
