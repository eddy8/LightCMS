<?php

namespace Tests\Feature\Admin;

use App\Model\Admin\AdminUser;
use App\Model\Admin\Entity;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EntityControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
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

    public function testEntityCanBeCreatedAndEdited()
    {
        $data = ['name' => '测试', 'table_name' => 'tests', 'is_modify_db' => 1];
        $response = $this->actingAs($this->user, 'admin')
            ->post('/admin/entities', $data);
        $response->assertJson(['code' => 0]);

        $data['name'] = '测试修改';
        $response = $this->actingAs($this->user, 'admin')
            ->put('/admin/entities/1', $data);
        $response->assertJson(['code' => 0]);

        $response = $this->actingAs($this->user, 'admin')
            ->post('/admin/entities', $data);
        $response->assertJson(['code' => 1]);
    }

    public function testEntityBeCreatedWhenTableHasExists()
    {
        $data = ['name' => '测试', 'table_name' => 'tests', 'is_modify_db' => 1];
        Schema::create($data['table_name'], function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        $response = $this->actingAs($this->user, 'admin')
            ->post('/admin/entities', $data);
        $response->assertJson(['code' => 2]);
    }

    public function testEntityCanBeDeleted()
    {
        $entity = factory(Entity::class)->create();
        $response = $this->actingAs($this->user, 'admin')->delete('/admin/entities/1');
        $response->assertStatus(200);
        $response->assertJson(['code' => 1]);

        $user = factory(AdminUser::class)->create(['id' => 1, 'password' => bcrypt('password')]);
        Schema::create($entity->table_name, function ($table) {
            $table->increments('id');
        });
        $response = $this->actingAs($user, 'admin')->delete('/admin/entities/1', ['password' => 'wrong password']);
        $response->assertJson(['code' => 2]);
        $response = $this->actingAs($user, 'admin')->delete('/admin/entities/1', ['password' => 'password']);
        $response->assertJson(['code' => 0]);
        $this->assertDatabaseMissing('entities', ['table_name' => $entity->table_name]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->expectExceptionMessage('no such table');
        DB::table($entity->table_name)->find(1);
    }

    public function tearDown(): void
    {
        Schema::dropIfExists('tests');
        Entity::query()->truncate();
        parent::tearDown();
    }
}
