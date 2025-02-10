<?php

namespace Tests\Unit;

use App\Enum\Category;
use App\Repositories\Attributes;
use PHPUnit\Framework\TestCase;

class AttributeTest extends TestCase
{

    public function test_skill_women(): void
    {
        $attribute = new Attributes(rand(1,100), Category::WOMEN);

        $this->assertLessThanOrEqual(100, $attribute->level);
        $this->assertEquals(0, $attribute->getPower());
        $this->assertEquals(0, $attribute->getSpeed());
        $this->assertGreaterThanOrEqual(70, $attribute->getReaction());
        $this->assertGreaterThanOrEqual(70,$attribute->getPower() + $attribute->getSpeed() + $attribute->getReaction());
        $this->assertLessThanOrEqual(100,$attribute->getPower() + $attribute->getSpeed() + $attribute->getReaction());
    }

    public function test_skill_men(): void
    {
        $attribute = new Attributes(rand(1,50), Category::MEN);

        $this->assertLessThanOrEqual(100, $attribute->level);
        $this->assertGreaterThanOrEqual(30, $attribute->getPower());
        $this->assertGreaterThanOrEqual(30, $attribute->getSpeed());
        $this->assertEquals(0, $attribute->getReaction());
        $this->assertGreaterThanOrEqual(30,$attribute->getPower() + $attribute->getSpeed() + $attribute->getReaction());
        $this->assertLessThanOrEqual(100,$attribute->getPower() + $attribute->getSpeed() + $attribute->getReaction());
    }
}
