<?php

namespace Tests\Feature\Admin;

use App\Model\Admin\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
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

    public function testImageUpload()
    {
        Storage::fake('admin_img');

        $user = factory(AdminUser::class)->make(['id' => 1]);
        $response = $this->actingAs($user, 'admin')
            ->post('/admin/neditor/serve/uploadImage', ['file' => UploadedFile::fake()->image('avatar.jpg')]);

        $response->assertJson(['code' => 200]);
    }
}
