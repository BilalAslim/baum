<?php

namespace Baum\Tests\Basic;

use Baum\Tests\Basic\Models\BasicBaum;

class BasicBaumTest extends UnitAbstract
{
    public function testNoRecords()
    {
        //factory(BasicBaum::class, 50)->create();
        $this->assertEquals(BasicBaum::count(), 0);
    }

    public function testRoot()
    {
        $root = BasicBaum::create(['name' => 'Root category']);
        $this->assertEquals(BasicBaum::count(), 1);
    }

    public function testIsChild()
    {
        $parent = factory(BasicBaum::class)->create();

        $child1 = factory(BasicBaum::class)->create();
        $parent->addChild($child1);

        $this->assertTrue($child1->isChild());
        $this->assertFalse($parent->isChild());
    }

    public function testGetQualifiedDepthColumnName()
    {
        $this->assertTrue(factory(BasicBaum::class)->create()->getQualifiedDepthColumnName() == 'basic_baums.depth');
    }

    public function testParentChild()
    {
        $parent = factory(BasicBaum::class)->create();

        $child1 = factory(BasicBaum::class)->create();
        $child1->makeChildOf($parent);

        $child2 = $parent->children()->create(factory(BasicBaum::class)->raw());

        $other = factory(BasicBaum::class, 3)->states('root')->create();

        $this->assertTrue($parent->isRoot());
        $this->assertFalse($child1->isRoot());
        $this->assertFalse($child2->isRoot());

        $this->assertFalse($parent->isLeaf());
        $this->assertTrue($child1->isLeaf());
        $this->assertTrue($child2->isLeaf());

        $this->assertFalse($parent->isChildOf($child1));
        $this->assertFalse($parent->isChildOf($child2));
        $this->assertTrue($child1->isChildOf($parent));
        $this->assertTrue($child2->isChildOf($parent));

        // print_r(BasicBaum::getNestedList('name'));
    }

    public function testRootCount()
    {
        $rand = rand(2, 5);
        factory(BasicBaum::class, $rand)->states('root')->create();
        $this->assertEquals($rand, BasicBaum::roots()->count());
    }

    public function testGetRoot()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $child1->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $child2->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $child3->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $child4->children()->create(factory(BasicBaum::class)->raw());

        $this->assertEquals($root->name, $child1->getRoot()->name);
        $this->assertEquals($root->name, $child2->getRoot()->name);
        $this->assertEquals($root->name, $child3->getRoot()->name);
        $this->assertEquals($root->name, $child4->getRoot()->name);
        $this->assertEquals($root->name, $child5->getRoot()->name);
        $this->assertEquals($root->name, $root->getRoot()->name);

        //          $rand = rand(2,5);
//          factory(BasicBaum::class, $rand)->states('root')->create();
//          $this->assertEquals($rand, BasicBaum::roots()->count());
    }

    public function testGetAncestorsAndSelf()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $child1->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $child2->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $child3->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $child4->children()->create(factory(BasicBaum::class)->raw());

        $this->assertEquals(1, $root->getAncestorsAndSelf()->count());
        $this->assertEquals(2, $child1->getAncestorsAndSelf()->count());
        $this->assertEquals(3, $child2->getAncestorsAndSelf()->count());
        $this->assertEquals(4, $child3->getAncestorsAndSelf()->count());
        $this->assertEquals(5, $child4->getAncestorsAndSelf()->count());
        $this->assertEquals(6, $child5->getAncestorsAndSelf()->count());
    }

    public function testGetAncestorsAndSelfWithoutRoot()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $child1->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $child2->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $child3->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $child4->children()->create(factory(BasicBaum::class)->raw());

        $this->assertEquals(0, $root->getAncestorsAndSelfWithoutRoot()->count());
        $this->assertEquals(1, $child1->getAncestorsAndSelfWithoutRoot()->count());
        $this->assertEquals(2, $child2->getAncestorsAndSelfWithoutRoot()->count());
        $this->assertEquals(3, $child3->getAncestorsAndSelfWithoutRoot()->count());
        $this->assertEquals(4, $child4->getAncestorsAndSelfWithoutRoot()->count());
        $this->assertEquals(5, $child5->getAncestorsAndSelfWithoutRoot()->count());
    }

    public function testGetAncestors()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $child1->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $child2->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $child3->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $child4->children()->create(factory(BasicBaum::class)->raw());

        $this->assertEquals(0, $root->getAncestors()->count());
        $this->assertEquals(1, $child1->getAncestors()->count());
        $this->assertEquals(2, $child2->getAncestors()->count());
        $this->assertEquals(3, $child3->getAncestors()->count());
        $this->assertEquals(4, $child4->getAncestors()->count());
        $this->assertEquals(5, $child5->getAncestors()->count());
    }

    public function testGetAncestorsWithoutRoot()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $child1->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $child2->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $child3->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $child4->children()->create(factory(BasicBaum::class)->raw());

        $this->assertEquals(0, $root->getAncestorsWithoutRoot()->count());
        $this->assertEquals(0, $child1->getAncestorsWithoutRoot()->count());
        $this->assertEquals(1, $child2->getAncestorsWithoutRoot()->count());
        $this->assertEquals(2, $child3->getAncestorsWithoutRoot()->count());
        $this->assertEquals(3, $child4->getAncestorsWithoutRoot()->count());
        $this->assertEquals(4, $child5->getAncestorsWithoutRoot()->count());
    }

    public function testGetSiblingsAndSelf()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $child4->children()->create(factory(BasicBaum::class)->raw());

        $this->assertEquals(1, $root->getSiblingsAndSelf()->count());
        $this->assertEquals(4, $child1->getSiblingsAndSelf()->count());
        $this->assertEquals(4, $child2->getSiblingsAndSelf()->count());
        $this->assertEquals(4, $child3->getSiblingsAndSelf()->count());
        $this->assertEquals(4, $child4->getSiblingsAndSelf()->count());
        $this->assertEquals(1, $child5->getSiblingsAndSelf()->count());
    }

    public function testGetSiblings()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $child4->children()->create(factory(BasicBaum::class)->raw());

        $this->assertEquals(0, $root->getSiblings()->count());
        $this->assertEquals(3, $child1->getSiblings()->count());
        $this->assertEquals(3, $child2->getSiblings()->count());
        $this->assertEquals(3, $child3->getSiblings()->count());
        $this->assertEquals(3, $child4->getSiblings()->count());
        $this->assertEquals(0, $child5->getSiblings()->count());
    }

    public function testGetLeaves()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $root->children()->create(factory(BasicBaum::class)->raw());

        $data = BasicBaum::first();

        $this->assertEquals(5, $data->getLeaves()->count());
    }

    public function testGetDescendantsAndSelf()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $child1->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $child1->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $child2->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $child3->children()->create(factory(BasicBaum::class)->raw());

        $data = BasicBaum::first();

        $this->assertEquals(6, $data->getDescendantsAndSelf()->count());
    }

    public function testGetDescendants()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $child1->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $child1->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $child2->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $child3->children()->create(factory(BasicBaum::class)->raw());

        $data = BasicBaum::first();

        $this->assertEquals(5, $data->getDescendants()->count());
    }

    public function testGetImmediateDescendants()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $child1->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $child2->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $root->children()->create(factory(BasicBaum::class)->raw());

        $data = BasicBaum::first();

        $this->assertEquals(3, $data->getImmediateDescendants()->count());
    }

    public function testImmediateDescendants()
    {
        $root = factory(BasicBaum::class)->create();
        $child1 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child2 = $root->children()->create(factory(BasicBaum::class)->raw());
        $child3 = $child1->children()->create(factory(BasicBaum::class)->raw());
        $child4 = $child2->children()->create(factory(BasicBaum::class)->raw());
        $child5 = $root->children()->create(factory(BasicBaum::class)->raw());

        $this->assertEquals(3, $root->immediateDescendants()->count());
        $this->assertEquals(0, $root->immediateDescendants()->where('parent_id', '!=', $root->id)->count());
    }

    public function testMakeBaum()
    {
        $faker = \Faker\Factory::create();
        $model = $faker->domainWord . rand(1, 9999);

        $this->artisan('make:baum ' . $model)
            ->assertExitCode(0);

        $this->artisan('make:baum ' . $model . ' --force')
            ->assertExitCode(0);
    }
}
