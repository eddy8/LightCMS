<?php

namespace Tests\Feature\Admin;

use App\Model\Admin\Category;
use App\Repository\Admin\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testLevelCategories()
    {
        $this->initData();

        $r = CategoryRepository::levelCategories();
        $this->assertCount(3, $r);
        $this->assertEquals([1,2,3], $r->pluck('id')->toArray());

        $r = CategoryRepository::levelCategories(1);
        $this->assertCount(2, $r);
        $this->assertEquals([4,5], $r->pluck('id')->toArray());

        $r = CategoryRepository::levelCategories(2);
        $this->assertCount(2, $r);
        $this->assertEquals([6,7], $r->pluck('id')->toArray());

        $this->assertCount(0, CategoryRepository::levelCategories(3));
    }

    public function testLeafCategories()
    {
        $this->initData();

        $r = CategoryRepository::leafCategories(1);
        $this->assertCount(2, $r);
        $this->assertEquals([6,7], $r->pluck('id')->toArray());

        $r = CategoryRepository::leafCategories(2);
        $this->assertCount(1, $r);
        $this->assertEquals([5], $r->pluck('id')->toArray());

        $this->assertCount(0, CategoryRepository::leafCategories(3));

        $r = CategoryRepository::leafCategories(4);
        $this->assertCount(2, $r);
        $this->assertEquals([6,7], $r->pluck('id')->toArray());

        $this->assertCount(0, CategoryRepository::leafCategories(5));
        $this->assertCount(0, CategoryRepository::leafCategories(6));

        $r = CategoryRepository::leafCategories();
        $this->assertCount(4, $r);
        $this->assertEquals([6,7,5,3], $r->pluck('id')->toArray());
    }

    public function testParentCategories()
    {
        $this->initData();

        $this->assertEquals([], CategoryRepository::parentCategories(1));
        $this->assertEquals([], CategoryRepository::parentCategories(2));
        $this->assertEquals([], CategoryRepository::parentCategories(3));
        $this->assertEquals([1], CategoryRepository::parentCategories(4));
        $this->assertEquals([2], CategoryRepository::parentCategories(5));
        $this->assertEquals([1,4], CategoryRepository::parentCategories(6));
        $this->assertEquals([1,4], CategoryRepository::parentCategories(7));
    }

    protected function initData()
    {
        /*
         * category tree
         *
         *     1    2    3
         *    /    /
         *   4    5
         *  / \
         * 6   7
         *
         */
        Category::factory()->count(3)->create(); // 0
        $m1 = Category::factory()->create(['pid' => 1]); // 1
        Category::factory()->create(['pid' => 2]); // 1
        Category::factory()->create(['pid' => $m1->id]); // 2
        Category::factory()->create(['pid' => $m1->id]); // 2
    }
}
