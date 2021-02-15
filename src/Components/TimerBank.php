<?php

namespace Knevelina\Prolite\Components;

use InvalidArgumentException;
use Knevelina\Prolite\Display;
use Knevelina\Prolite\Models\Timer;

class TimerBank
{
    /**
     * @var int
     */
    private $displayId;

    /**
     * @var array
     */
    private $timers;

    /**
     * Construct a new timer bank.
     *
     * @param int $displayId
     */
    public function __construct(int $displayId)
    {
        Display::verifyDisplayId($displayId);

        $this->displayId = $displayId;

        $this->timers = [];
    }

    /**
     * Query whether the timer bank has a timer for a given index.
     *
     * @param int $index
     * @return bool
     */
    public function hasTimer(int $index): bool
    {
        return isset($this->timers[self::getTimerID($index)]);
    }

    /**
     * Get the timer ID for a given index.
     *
     * @param int $index
     * @return string
     */
    private static function getTimerID(int $index): string
    {
        if ($index < 0 || $index > 9) {
            throw new InvalidArgumentException(sprintf('Invalid timer index %d', $index));
        }

        return chr(65 + $index);
    }

    /**
     * Get the timer for a given index.
     *
     * @param int $index
     * @return Timer|null
     */
    public function getTimer(int $index)
    {
        return $this->timers[self::getTimerID($index)] ?? null;
    }

    /**
     * Set the timer for a given index.
     *
     * @param int $index
     * @param Timer $timer
     */
    public function setTimer(int $index, Timer $timer)
    {
        $this->timers[self::getTimerID($index)] = $timer;
    }

    /**
     * Get the display ID this timer bank belongs to.
     *
     * @return int
     */
    public function getDisplayId(): int
    {
        return $this->displayId;
    }

    /**
     * Get the configuration line(s) needed to define the timers in this timer bank.
     *
     * @return string
     */
    public function getConfiguration(): string
    {
        $config = '';

        /**
         * @var string $key
         * @var Timer $timer
         */
        foreach ($this->timers as $key => $timer) {
            $config .= sprintf(
                "<ID%02d><T%s>%s%s%s%s\r\n",
                $this->displayId,
                $key,
                $timer->getDayOfWeek(),
                $timer->getHour(),
                $timer->getMinute(),
                $timer->getSequenceAsPageIDs()
            );
        }

        return $config;
    }
}