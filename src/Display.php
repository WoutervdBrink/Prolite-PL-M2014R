<?php

namespace Knevelina\Prolite;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Knevelina\Prolite\Components\GraphicsBank;
use Knevelina\Prolite\Components\PageBank;
use Knevelina\Prolite\Components\TimerBank;

class Display
{
    /**
     * @var int The ID of this display.
     */
    private $id;

    /**
     * @var PageBank The page bank of this display.
     */
    private $pageBank;

    /**
     * @var GraphicsBank The graphics bank of this display.
     */
    private $graphicsBank;

    /**
     * @var TimerBank The timer bank of this display.
     */
    private $timerBank;

    /**
     * @var DateTimeImmutable The current date and time.
     */
    private $dateTime;

    /**
     * Construct a new display.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        self::verifyDisplayId($id);

        $this->id = $id;

        $this->pageBank = new PageBank($id);
        $this->graphicsBank = new GraphicsBank($id);
        $this->timerBank = new TimerBank($id);
    }

    /**
     * Verify that a given ID is a valid display ID.
     *
     * A valid display ID is a byte.
     *
     * @param int $id
     */
    public static function verifyDisplayId(int $id)
    {
        if ($id < 0 || $id > 0xff) {
            throw new InvalidArgumentException(sprintf('Invalid display ID %d', $id));
        }
    }

    /**
     * Sets the current date and time to the date and time at the call of this method.
     *
     * This is not the default behavior - you must explicitly call this method to set the date and time on the sign.
     */
    public function setDateTimeToNow()
    {
        $this->dateTime = new DateTimeImmutable();
    }

    /**
     * Set the date and time of the sign to a custom date and time.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @param int $second
     */
    public function setDateTime(int $year, int $month, int $day, int $hour, int $minute, int $second)
    {
        $this->dateTime = DateTimeImmutable::createFromFormat(
            DateTimeInterface::ATOM,
            sprintf(
                '%04d-%02d-%02dT%02d:%02d:%02dP',
                $year,
                $month,
                $day,
                $hour,
                $minute,
                $second
            )
        );
    }

    /**
     * Get the configuration line(s) needed to configure this display.
     *
     * When <code>reset</code> is <code>true</code>, some lines are included to empty the display's RAM.
     *
     * When <code>displayPage</code> is a valid page index, the page is displayed. This disables timers!
     *
     * @param bool $reset
     * @param int $displayPage
     * @return string
     */
    public function getConfiguration(bool $reset = true, int $displayPage = null): string
    {
        $header = sprintf('<ID%02X>', $this->id);
        $eol = "\r\n";
        $config = '';

        if ($reset) {
            $config .= $header . $eol;

            // Delete all pages
            $config .= $header . '<DP*>' . $eol;

            // Delete all timers
            $config .= $header . '<DT*>' . $eol;

            // Delete all graphics
            $config .= $header . '<DG*>' . $eol;
        }

        $config .= $this->graphicsBank->getConfiguration();

        $config .= $this->pageBank->getConfiguration();

        $config .= $this->timerBank->getConfiguration();

        if (isset($this->dateTime) && !is_null($this->dateTime)) {
            $config .= sprintf(
                    '<T%s>',
                    $this->dateTime->format('ymdwHis')
                ) . $eol;
        }

        if (!is_null($displayPage)) {
            if (!$this->pageBank->hasPage($displayPage)) {
                throw new InvalidArgumentException(sprintf('%d is not a valid page.', $displayPage));
            }
            $config .= $header . '<RP' . (chr(65 + $displayPage)) . '>' . $eol;
        }

        return $config;
    }

    /**
     * Get the ID of this display.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the graphics bank of this display.
     *
     * @return GraphicsBank
     */
    public function getGraphicsBank(): GraphicsBank
    {
        return $this->graphicsBank;
    }

    /**
     * Get the page bank of this display.
     *
     * @return PageBank
     */
    public function getPageBank(): PageBank
    {
        return $this->pageBank;
    }

    /**
     * Get the timer bank of this display.
     *
     * @return TimerBank
     */
    public function getTimerBank(): TimerBank
    {
        return $this->timerBank;
    }
}