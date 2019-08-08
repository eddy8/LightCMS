<?php

namespace Tests\Feature\Admin;

use App\Model\Admin\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testUsersCanBeListed()
    {
        $users = factory(AdminUser::class, 3)->create();
        $response = $this->actingAs($users[0], 'admin')->get('/admin/admin_users/list');
        $response->assertStatus(200);
        $content = $response->original;
        $this->assertEquals(3, $content['count']);
    }
}
