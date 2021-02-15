<?php

use Knevelina\Prolite\Components\GraphicsBank;
use Knevelina\Prolite\Components\PageBank;
use Knevelina\Prolite\Components\TimerBank;
use Knevelina\Prolite\Display;
use Knevelina\Prolite\Models\Graphic;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Knevelina\Prolite\Display
 */
class DisplayTest extends TestCase
{
    public function validIDs(): array
    {
        return array_map(
            function (int $id) {
                return [$id];
            },
            range(0, 0xFF)
        );
    }

    public function invalidIDs(): array
    {
        return [
            [-1],
            [0x1FF],
            [-5]
        ];
    }

    /**
     * @test
     * @dataProvider validIDs
     * @param int $id
     */
    function it_has_an_id(int $id)
    {
        $display = new Display($id);

        $this->assertEquals($id, $display->getId());
    }

    /**
     * @test
     * @dataProvider invalidIDs
     * @param int $id
     */
    function it_refuses_invalid_ids(int $id)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('Invalid display ID %d', $id)));

        new Display($id);
    }

    /** @test */
    function it_has_a_page_bank()
    {
        $display = new Display(1);

        $this->assertInstanceOf(PageBank::class, $display->getPageBank());
        $this->assertEquals(1, $display->getPageBank()->getDisplayId());
    }

    /** @test */
    function it_has_a_graphics_bank()
    {
        $display = new Display(1);

        $this->assertInstanceOf(GraphicsBank::class, $display->getGraphicsBank());
        $this->assertEquals(1, $display->getGraphicsBank()->getDisplayId());
    }

    /** @test */
    function it_has_a_timer_bank()
    {
        $display = new Display(1);

        $this->assertInstanceOf(TimerBank::class, $display->getTimerBank());
        $this->assertEquals(1, $display->getTimerBank()->getDisplayId());
    }

    /** @test */
    function it_starts_with_zero_configuration_when_not_reset()
    {
        $display = new Display(1);

        $this->assertEmpty($display->getConfiguration(false));
    }

    /** @test */
    function it_resets_when_requested()
    {
        $display = new Display(1);

        $this->assertEquals("<ID01>\r\n<ID01><DP*>\r\n<ID01><DT*>\r\n<ID01><DG*>\r\n", $display->getConfiguration(true));
    }

    /** @test */
    function it_includes_the_page_configuration()
    {
        $display = new Display(1);

        $display->getPageBank()->setPage(0, 'Hello, world!');

        $this->assertEquals("<ID01><PA>Hello, world!\r\n", $display->getConfiguration(false));
    }

    /** @test */
    function it_includes_the_graphics_configuration()
    {
        $display = new Display(1);
        $graphic = new Graphic(str_repeat('R', Graphic::WIDTH * Graphic::HEIGHT));

        $display->getGraphicsBank()->setGraphic(0, $graphic);

        $this->assertEquals("<ID01><GA>".$graphic->getPixels()."\r\n", $display->getConfiguration(false));
    }

    /** @test */
    function it_displays_a_single_page()
    {
        $display = new Display(1);

        $display->getPageBank()->setPage(0, 'Test');

        $this->assertEquals("<ID01><PA>Test\r\n<ID01><RPA>\r\n", $display->getConfiguration(false, 0));
    }

    /**
     * @test
     * @dataProvider invalidPageIndices
     * @param int $pageIndex
     */
    function it_rejects_displaying_invalid_pages(int $pageIndex)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('%d is not a valid page.', $pageIndex)));

        $display = new Display(1);
        $display->getPageBank()->setPage(0, 'Hello, world!');
        $display->getConfiguration(false, $pageIndex);
    }

    /** @test */
    function it_sets_date_and_time()
    {
        $display = new Display(1);

        $display->setDateTime(2021, 3, 1, 2, 30, 8);

        $this->assertEquals("<T2103011023008>\r\n", $display->getConfiguration(false));
    }

    /** @test */
    function it_sets_the_current_date_and_time()
    {
        $display = new Display(1);

        $display->setDateTimeToNow();

        $this->assertMatchesRegularExpression("/^<T\d{13}>\r\n$/", $display->getConfiguration(false));
    }

    public function invalidPageIndices(): array
    {
        return [
            [-2],
            [-1],
            [1],
            [25],
            [26]
        ];
    }
}