<?php

namespace Components;

use InvalidArgumentException;
use Knevelina\Prolite\Components\PageBank;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Knevelina\Prolite\Components\PageBank
 */
class PageBankTest extends TestCase
{
    /** @test */
    function it_has_a_display_id()
    {
        $bank = new PageBank(1);

        $this->assertEquals(1, $bank->getDisplayId());
    }

    /**
     * @test
     * @dataProvider invalidDisplayIDs
     */
    function it_rejects_invalid_display_IDs(int $id)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('Invalid display ID %d', $id)));

        new PageBank($id);
    }

    /** @test */
    function it_has_pages()
    {
        $bank = new PageBank(1);

        $this->assertFalse($bank->hasPage(0));

        $bank->setPage(0, 'Hello, world!');

        $this->assertEquals('Hello, world!', $bank->getPage(0));
        $this->assertTrue($bank->hasPage(0));
    }

    /** @test */
    function it_generates_config_for_pages()
    {
        $bank = new PageBank(1);

        $bank->setPage(0, 'Hello, world!');

        $this->assertEquals("<ID01><PA>Hello, world!\r\n", $bank->getConfiguration());
    }

    /** @test */
    function it_rejects_invalid_page_lengths()
    {
        $bank = new PageBank(1);

        $this->expectExceptionObject(
            new InvalidArgumentException('Page content for page A is too long (maximum 1000 characters)')
        );

        $bank->setPage(0, str_repeat('X', 1001));
    }

    /**
     * @test
     * @dataProvider invalidPageIndices
     * @param int $index
     */
    function it_rejects_invalid_page_indices(int $index)
    {
        $bank = new PageBank(1);

        $this->expectExceptionObject(new InvalidArgumentException(sprintf('%d is not a valid page.', $index)));

        $bank->setPage($index, 'Hello, world!');
    }

    /** @test */
    function it_generates_config_for_defined_pages()
    {
        $bank = new PageBank(1);

        $bank->setPage(0, 'Hello, world');

        $this->assertEquals("<ID01><PA>Hello, world\r\n", $bank->getConfiguration());
    }

    /** @test */
    function it_does_not_generate_config_when_no_pages_are_defined()
    {
        $bank = new PageBank(1);

        $this->assertEmpty($bank->getConfiguration());
    }

    public function invalidPageIndices(): array
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