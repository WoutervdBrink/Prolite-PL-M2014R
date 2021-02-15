<?php

namespace Models;

use InvalidArgumentException;
use Knevelina\Prolite\Models\Timer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Knevelina\Prolite\Models\Timer
 */
class TimerTest extends TestCase
{
    /** @test */
    function it_stores_timer_information()
    {
        $timer = new Timer(1, 2, 3, [1]);

        $this->assertEquals('1', $timer->getDayOfWeek());
        $this->assertEquals('02', $timer->getHour());
        $this->assertEquals('03', $timer->getMinute());
        $this->assertEquals([1], $timer->getSequence());
    }

    /** @test */
    function it_accepts_globals()
    {
        $timer = new Timer(-1, -1, -1, [1]);

        $this->assertEquals('*', $timer->getDayOfWeek());
        $this->assertEquals('**', $timer->getHour());
        $this->assertEquals('**', $timer->getMinute());
    }

    /** @test */
    function it_formats_sequences()
    {
        $timer = new Timer(1, 2, 3, [0, 1, 2, 3]);

        $this->assertEquals('ABCD', $timer->getSequenceAsPageIDs());
    }

    /** @test */
    function it_accepts_sequences()
    {
        $sequence = [0];

        for ($i = 0; $i < 31; $i++) {
            $this->assertInstanceOf(Timer::class, new Timer(1, 2, 3, $sequence));

            $sequence[] = $i % 26;
        }
    }

    /**
     * @test
     * @dataProvider invalidDaysOfWeek
     * @param int $dayOfWeek
     */
    function it_rejects_invalid_days_of_week(int $dayOfWeek)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('Invalid timer day of week %d', $dayOfWeek)));

        new Timer($dayOfWeek, 0, 0, [1]);
    }

    /**
     * @test
     * @dataProvider invalidHoursAndMinutes
     * @param int $hour
     */
    function it_rejects_invalid_hours(int $hour)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('Invalid timer hour %d', $hour)));

        new Timer(0, $hour, 0, [1]);
    }

    /**
     * @test
     * @dataProvider invalidHoursAndMinutes
     * @param int $minute
     */
    function it_rejects_invalid_minutes(int $minute)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('Invalid timer minute %d', $minute)));

        new Timer(0, 0, $minute, [1]);
    }

    /**
     * @test
     * @dataProvider invalidSequences
     * @param array $sequence
     * @param string $message
     */
    function it_rejects_invalid_sequences(array $sequence, string $message): void
    {
        $this->expectExceptionObject(
            new InvalidArgumentException($message)
        );

        new Timer(0, 0, 0, $sequence);
    }

    public function invalidDaysOfWeek(): array
    {
        return [
            [-2],
            [7],
            [8]
        ];
    }

    public function invalidHoursAndMinutes(): array
    {
        return [
            [-2],
            [60],
            [61]
        ];
    }

    public function invalidSequences(): array
    {
        return [
            [[], 'Invalid timer sequence length 0'],
            [[ 0,  1,  2,  3,  4,  5,  6,  7,  8,  9,  10,  11,  12,  13,  14,  15,  16,  17,  18,  19,  20,  21,  22,  23,  24,  25,  0,  1,  2,  3,  4,  5,  6,], 'Invalid timer sequence length 33'],
            [['A'], 'Invalid timer sequence page A'],
            [[-1], 'Invalid timer sequence page -1']
        ];
    }
}