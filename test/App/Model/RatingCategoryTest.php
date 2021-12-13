<?php declare(strict_types=1);

namespace App\Model;

use PHPUnit\Framework\TestCase;

class RatingCategoryTest extends TestCase
{
    private RatingCategory $ratingCategory;

    protected function setUp(): void
    {
        $this->ratingCategory = new RatingCategory();

        parent::setUp();
    }

    public function testPropertiesIsByInitializeNull()
    {
        $description = $this->ratingCategory->getDescription();

        $this->assertNull($description);
    }

    public function testCanGetAndSetId()
    {
        $ratingCategoryId = $this->ratingCategory->setId(1);
        $id = $ratingCategoryId->getId();

        $this->assertInstanceOf(RatingCategory::class, $ratingCategoryId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanGetAndSetTile()
    {
        $ratingCategoryTitle = $this->ratingCategory->setTitle('test');
        $title = $ratingCategoryTitle->getTitle();

        $this->assertInstanceOf(RatingCategory::class, $ratingCategoryTitle);
        $this->assertIsString($title);
        $this->assertSame('test', $title);
    }

    public function testCanGetAndSetDescription()
    {
        $ratingCategoryDescription = $this->ratingCategory->setDescription('test');
        $description = $ratingCategoryDescription->getDescription();

        $this->assertInstanceOf(RatingCategory::class, $ratingCategoryDescription);
        $this->assertIsString($description);
        $this->assertSame('test', $description);
    }
}
