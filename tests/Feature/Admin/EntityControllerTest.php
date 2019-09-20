<?php

namespace Tests\Feature\Admin;

use App\Model\Admin\AdminUser;
use App\Model\Admin\Entity;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EntityControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();

        $this->user = factory(AdminUser::class)->make(['id' => 1]);
    }

    public function testEntityCanBeListed()
    {
        factory(Entity::class, 1)->create();
        $response = $this->actingAs($this->user, 'admin')->get('/admin/entities/list');
        $response->assertStatus(200);
        $content = $response->original;

        $this->assertEquals(1, $content['count']);
    }

    public function testEntityCanBeCreated()
    {
        $data = ['name' => '测试', 'table_name' => 'tests'];
        $response = $this->actingAs($this->user, 'admin')
            ->post('/admin/entities', $data);
        $response->assertJson(['code' => 0]);

        $response = $this->actingAs($this->user, 'admin')
            ->post('/admin/entities', $data);
        $response->assertJson(['code' => 1]);
    }

    public function testEntityBeCreatedWhenTableHasExists()
    {
        $data = ['name' => '测试', 'table_name' => 'tests'];
        Schema::create($data['table_name'], function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        $response = $this->actingAs($this->user, 'admin')
            ->post('/admin/entities', $data);
        $response->assertJson(['code' => 2]);
    }

    public function tearDown()
    {
        Schema::dropIfExists('tests');
        Entity::query()->truncate();
        parent::tearDown();
    }
}
