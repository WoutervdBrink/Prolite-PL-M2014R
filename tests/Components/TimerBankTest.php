<?php

namespace Components;

use InvalidArgumentException;
use Knevelina\Prolite\Components\TimerBank;
use Knevelina\Prolite\Models\Timer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Knevelina\Prolite\Components\TimerBank
 */
class TimerBankTest extends TestCase
{
    /** @test */
    function it_has_a_display_id()
    {
        $bank = new TimerBank(1);

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

        new TimerBank($id);
    }

    /** @test */
    function it_begins_with_no_timers()
    {
        $bank = new TimerBank(1);

        for ($ndex = 0; $ndex < 10; $ndex++) {
            $this->assertFalse($bank->hasTimer($ndex));
        }
    }

    /** @test */
    function it_has_timers()
    {
        $bank = new TimerBank(1);
        $timer = new Timer(1, 2, 3, [1]);

        $bank->setTimer(0, $timer);

        $this->assertTrue($bank->hasTimer(0));
        $this->assertEquals($timer, $bank->getTimer(0));
    }

    /**
     * @test
     * @dataProvider invalidTimerIndices
     * @param int $index
     */
    function it_rejects_getting_invalid_timers(int $index)
    {
        $bank = new TimerBank(1);

        $this->expectExceptionObject(new InvalidArgumentException(sprintf('Invalid timer index %d', $index)));

        $bank->getTimer($index);
    }

    /**
     * @test
     * @dataProvider invalidTimerIndices
     * @param int $index
     */
    function it_rejects_setting_invalid_timers(int $index)
    {
        $bank = new TimerBank(1);
        $timer = new Timer(1, 2, 3, [1]);

        $this->expectExceptionObject(new InvalidArgumentException(sprintf('Invalid timer index %d', $index)));

        $bank->setTimer($index, $timer);
    }

    /** @test */
    function it_does_not_generate_config_for_undefined_timers()
    {
        $bank = new TimerBank(1);

        $this->assertEmpty($bank->getConfiguration());
    }

    /** @test */
    function it_generates_config_for_timers()
    {
        $bank = new TimerBank(1);
        $timer = new Timer(1, 2, 3, [0, 1, 2, 3, 4, 3, 2, 1, 0]);

        $bank->setTimer(0, $timer);

        $this->assertEquals("<ID01><TA>10203ABCDEDCBA\r\n", $bank->getConfiguration());
    }

    public function invalidDisplayIDs(): array
    {
        return [
            [-1],
            [0x1FF],
            [-5]
        ];
    }

    public function invalidTimerIndices(): array
    {
        return [
            [-1],
            [10],
            [11]
        ];
    }
}