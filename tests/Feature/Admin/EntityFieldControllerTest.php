<?php

namespace Tests\Feature\Admin;

use App\Model\Admin\AdminUser;
use App\Model\Admin\Entity;
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
    protected $filedName = 'title';

    public function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();

        $data = ['name' => '测试', 'table_name' => 'tests'];
        $this->entity = EntityRepository::add($data);
        $this->user = factory(AdminUser::class)->make(['id' => 1]);
    }

    public function testEntityFieldCanBeCreated()
    {
        $response = $this->createEntityField();
        $response->assertJson(['code' => 0]);
        $this->assertDatabaseHas(
            'entity_fields',
            [
                'entity_id' => $this->entity->id,
                'name' => 'title',
                'is_show' => EntityField::SHOW_DISABLE,
                'is_edit' => EntityField::EDIT_DISABLE,
                'is_required' => EntityField::REQUIRED_DISABLE,
                'is_show_inline' => EntityField::SHOW_NOT_INLINE,
            ]
        );
        $this->assertTrue(Schema::hasColumn($this->entity->table_name, $this->filedName));
    }

    public function testEntityFieldCanBeCreatedWithNotModifyDB()
    {
        $response = $this->createEntityField(false);
        $response->assertJson(['code' => 0]);
        $this->assertDatabaseHas(
            'entity_fields',
            [
                'entity_id' => $this->entity->id,
                'name' => 'title',
                'is_show' => EntityField::SHOW_DISABLE,
                'is_edit' => EntityField::EDIT_DISABLE,
                'is_required' => EntityField::REQUIRED_DISABLE,
                'is_show_inline' => EntityField::SHOW_NOT_INLINE,
            ]
        );
        $this->assertFalse(Schema::hasColumn($this->entity->table_name, $this->filedName));
    }

    public function testEntityContentCanBeCreatedAndEdited()
    {
        $this->createEntityField();
        $data = [
            'title' => '测试标题'
        ];
        $response = $this->actingAs($this->user, 'admin')
            ->post('/admin/entity/' . $this->entity->id . '/contents', $data);
        $response->assertJson(['code' => 0]);

        $data = [
            'title' => '测试修改标题'
        ];

        $response = $this->actingAs($this->user, 'admin')
            ->put('/admin/entity/' . $this->entity->id . '/contents/1', $data);
        $response->assertJson(['code' => 0]);
    }

    protected function createEntityField($modifyDB = true)
    {
        $data = [
            'entity_id' => $this->entity->id,
            'name' => $this->filedName,
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
        if ($modifyDB) {
            $data['is_modify_db'] = 1;
        }
        return $this->actingAs($this->user, 'admin')
            ->post('/admin/entityFields', $data);
    }

    public function tearDown()
    {
        Schema::dropIfExists('tests');
        Entity::query()->truncate();
        parent::tearDown();
    }
}
