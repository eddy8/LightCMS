<?php

namespace Tests\Feature\Admin;

use App\Model\Admin\AdminUser;
use App\Model\Admin\EntityField;
use App\Repository\Admin\EntityRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EntityFieldControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $entity;
    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();

        $data = ['name' => '测试', 'table_name' => 'tests'];
        $this->entity = EntityRepository::add($data);
        $this->user = factory(AdminUser::class)->create();
    }

    public function testEntityFieldCanBeCreated()
    {
        $data = [
            'entity_id' => $this->entity->id,
            'name' => 'title',
            'type' => 'string',
            'form_name' => '标题',
            'form_type' => 'input',
            'order' => 77,
            'field_length' => '',
            'field_total' => '',
            'field_scale' => '',
            'comment' => '',
            'default_value' => ''
        ];
        $response = $this->actingAs($this->user, 'admin')
            ->post('/admin/entityFields', $data);
        $response->assertJson(['code' => 0]);
        $this->assertDatabaseHas('entity_fields', ['entity_id' => $this->entity->id, 'name' => 'title']);
        $this->assertTrue(Schema::hasColumn($this->entity->table_name, $data['name']));
    }
}
