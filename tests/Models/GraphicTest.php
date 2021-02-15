<?php

namespace Models;

use InvalidArgumentException;
use Knevelina\Prolite\Models\Graphic;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Knevelina\Prolite\Models\Graphic
 */
class GraphicTest extends TestCase
{
    /** @test */
    function it_has_pixels()
    {
        $pixels = str_repeat('B', Graphic::WIDTH * Graphic::HEIGHT);

        $graphic = new Graphic($pixels);

        $this->assertEquals($pixels, $graphic->getPixels());
    }

    /** @test */
    function it_gets_pixels()
    {
        $pixels = str_repeat('B', 18 * 7);
        $pixels[0] = 'R';
        $pixels[1] = 'G';
        $pixels[18] = 'Y';

        $graphic = new Graphic($pixels);

        $this->assertEquals('R', $graphic->getPixel(0, 0));
        $this->assertEquals('G', $graphic->getPixel(1, 0));
        $this->assertEquals('Y', $graphic->getPixel(0, 1));
    }

    /**
     * @test
     * @dataProvider invalidDimensions
     * @param int $width
     * @param int $height
     */
    function it_rejects_invalid_dimensions(int $width, int $height)
    {
        $this->expectExceptionObject(
            new InvalidArgumentException(sprintf('Invalid graphic size %d', $width * $height))
        );

        new Graphic(str_repeat('B', $width * $height));
    }

    /** @test */
    function it_rejects_invalid_pixels()
    {
        $this->expectExceptionObject(new InvalidArgumentException('Invalid pixel type X'));

        new Graphic(str_repeat('B', Graphic::WIDTH * Graphic::HEIGHT - 1) . 'X');
    }

    /**
     * @test
     * @dataProvider invalidCoordinates
     * @param int $x
     * @param int $y
     */
    function it_rejects_invalid_coordinates(int $x, int $y): void
    {
        $graphic = new Graphic(str_repeat('B', Graphic::WIDTH * Graphic::HEIGHT));

        $this->expectExceptionObject(new InvalidArgumentException(sprintf('Invalid coordinates %d, %d', $x, $y)));

        $graphic->getPixel($x, $y);
    }

    public function invalidDimensions(): array
    {
        return [
            [18, 6],
            [18, 8],
            [17, 7],
            [19, 7]
        ];
    }

    public function invalidCoordinates(): array
    {
        return [
            [-1, -1],
            [0, -1],
            [-1, 0],
            [18, 0],
            [0, 7]
        ];
    }
}