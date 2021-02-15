<?php

namespace Components;

use InvalidArgumentException;
use Knevelina\Prolite\Components\GraphicsBank;
use Knevelina\Prolite\Models\Graphic;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Knevelina\Prolite\Components\GraphicsBank
 */
class GraphicsBankTest extends TestCase
{
    /** @test */
    function it_has_a_display_id()
    {
        $bank = new GraphicsBank(1);

        $this->assertEquals(1, $bank->getDisplayId());
    }

    /**
     * @test
     * @dataProvider invalidDisplayIDs
     * @param int $id
     */
    function it_rejects_invalid_display_IDs(int $id)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('Invalid display ID %d', $id)));

        new GraphicsBank($id);
    }

    /** @test */
    function it_begins_with_no_graphics()
    {
        $bank = new GraphicsBank(1);

        for ($ndex = 0; $ndex < 25; $ndex++) {
            $this->assertFalse($bank->hasGraphic($ndex));
        }
    }

    /** @test */
    function it_has_graphics(): void
    {
        $bank = new GraphicsBank(1);
        $graphic = new Graphic(str_repeat('R', Graphic::WIDTH * Graphic::HEIGHT));

        $bank->setGraphic(0, $graphic);

        $this->assertEquals($graphic, $bank->getGraphic(0));
    }

    /**
     * @test
     * @dataProvider invalidGraphicIndices
     * @param int $index
     */
    function it_rejects_setting_invalid_graphic_indices(int $index)
    {
        $bank = new GraphicsBank(1);
        $graphic = new Graphic(str_repeat('R', Graphic::WIDTH * Graphic::HEIGHT));

        $this->expectExceptionObject(new InvalidArgumentException(sprintf('%d is not a valid graphic index.', $index)));

        $bank->setGraphic($index, $graphic);
    }

    /**
     * @test
     * @dataProvider invalidGraphicIndices
     * @param int $index
     */
    function it_rejects_getting_invalid_graphic_indices(int $index)
    {
        $bank = new GraphicsBank(1);

        $this->expectExceptionObject(new InvalidArgumentException(sprintf('%d is not a valid graphic index.', $index)));

        $bank->getGraphic($index);
    }

    /**
     * @test
     * @dataProvider invalidGraphicIndices
     * @param int $index
     */
    function it_rejects_getting_invalid_default_graphics(int $index)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('%d is not a valid graphic index.', $index)));

        GraphicsBank::getDefaultGraphic($index);
    }

    /** @test */
    function it_returns_the_default_graphics_when_no_graphic_has_been_set()
    {
        $bank = new GraphicsBank(1);
        $graphic = new Graphic(str_repeat('R', Graphic::WIDTH * Graphic::HEIGHT));

        for ($i = 0; $i < 25; $i++) {
            $this->assertInstanceOf(Graphic::class, $bank->getGraphic($i));
            $this->assertNotEquals($graphic, $bank->getGraphic($i));
        }
    }

    /** @test */
    function it_generates_config_for_defined_graphics()
    {
        $bank = new GraphicsBank(1);
        $graphic = new Graphic(str_repeat('R', Graphic::WIDTH * Graphic::HEIGHT));

        $bank->setGraphic(0, $graphic);

        $this->assertEquals('<ID01><GA>' . $graphic->getPixels() . "\r\n", $bank->getConfiguration());
    }

    /** @test */
    function it_does_not_generate_config_for_undefined_graphics()
    {
        $bank = new GraphicsBank(1);

        $this->assertEmpty($bank->getConfiguration());
    }

    public function invalidGraphicIndices(): array
    {
        return [
            [-3],
            [-1],
            [26],
            [27],
            [30]
        ];
    }

    public function invalidDisplayIDs(): array
    {
        return [
            [-1],
            [0x1FF],
            [-5]
        ];
    }
}