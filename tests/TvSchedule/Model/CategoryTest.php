<?php

namespace App\Tests\TvSchedule\Model;

use App\TvSchedule\Model\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $category = new Category();
        $category->title = 'title';

        $this->assertEquals('"title"', json_encode($category));
    }
}
