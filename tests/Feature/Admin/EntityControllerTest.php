<?php

namespace Tests\Feature\Admin;

use App\Model\Admin\AdminUser;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EntityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testEntityCanBeCreated()
    {
        $user = factory(AdminUser::class)->create();
        $data = ['name' => '测试', 'table_name' => 'tests'];
        $response = $this->actingAs($user, 'admin')
            ->post('/admin/entities', $data);
        $response->assertJson(['code' => 0]);

        $response = $this->actingAs($user, 'admin')
            ->post('/admin/entities', $data);
        $response->assertJson(['code' => 1]);
    }

    public function testEntityBeCreatedWhenTableHasExists()
    {
        $user = factory(AdminUser::class)->create();
        $data = ['name' => '测试', 'table_name' => 'tests'];
        Schema::create($data['table_name'], function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        $response = $this->actingAs($user, 'admin')
            ->post('/admin/entities', $data);
        $response->assertJson(['code' => 2]);
    }
}
