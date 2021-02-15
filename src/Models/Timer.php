<?php

namespace Knevelina\Prolite\Models;

use InvalidArgumentException;

class Timer
{
    /**
     * @var string The day of the week on which this timer activates.
     */
    private $dayOfWeek;

    /**
     * @var string The hour at which this timer activates.
     */
    private $hour;

    /**
     * @var string The minute at which this timer activates.
     */
    private $minute;

    /**
     * @var array The sequence of pages activated by this timer.
     */
    private $sequence;

    /**
     * Construct a new Timer.
     *
     * When negative 1 (<code>-1</code) is supplied as a timer's day of week, hour or minute, the timer will activate
     * every day of the week, every hour or every minute respectively.
     * @param int $dayOfWeek
     * @param int $hour
     * @param int $minute
     * @param array $sequence
     */
    public function __construct(int $dayOfWeek, int $hour, int $minute, array $sequence)
    {
        if ($dayOfWeek < -1 || $dayOfWeek > 6) {
            throw new InvalidArgumentException(sprintf('Invalid timer day of week %d', $dayOfWeek));
        }

        if ($hour < -1 || $hour > 59) {
            throw new InvalidArgumentException(sprintf('Invalid timer hour %d', $hour));
        }

        if ($minute < -1 || $minute > 59) {
            throw new InvalidArgumentException(sprintf('Invalid timer minute %d', $minute));
        }

        if (count($sequence) < 1 || count($sequence) > 32) {
            throw new InvalidArgumentException(sprintf('Invalid timer sequence length %d', count($sequence)));
        }

        foreach ($sequence as $page) {
            if (!is_int($page) || $page < 0 || $page > 25) {
                throw new InvalidArgumentException(sprintf('Invalid timer sequence page %s', $page));
            }
        }

        $this->dayOfWeek = $dayOfWeek === -1 ? '*' : strval($dayOfWeek);
        $this->hour = $hour === -1 ? '**' : sprintf('%02d', $hour);
        $this->minute = $minute === -1 ? '**' : sprintf('%02d', $minute);
        $this->sequence = array_map(function ($val) { return intval($val); }, $sequence);
    }

    /**
     * Get the day of the week on which this timer activates.
     *
     * @return string
     */
    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }

    /**
     * Get the hour at which this timer activates.
     *
     * @return string
     */
    public function getHour(): string
    {
        return $this->hour;
    }

    /**
     * Get the minute at which this timer activates.
     *
     * @return string
     */
    public function getMinute(): string
    {
        return $this->minute;
    }

    /**
     * Get the sequence of pages activated by this timer.
     *
     * @return array
     */
    public function getSequence(): array
    {
        return $this->sequence;
    }

    /**
     * Get the sequence of pages activated by this timer, as a string of page IDs.
     *
     * @return string
     */
    public function getSequenceAsPageIDs(): string
    {
        return implode('', array_map(function (int $index): string {
            return chr(65 + $index);
        }, $this->sequence));
    }
}