<?php

namespace Knevelina\Prolite\Components;

use InvalidArgumentException;
use Knevelina\Prolite\Display;
use Knevelina\Prolite\Models\Graphic;

class GraphicsBank
{
    /**
     * The sign's default graphics.
     */
    const DEFAULT = [
        'BBRRRRRRRRRRRRRRRBBRRRRBBGBBBGBBRRRRBBBBBBYYYYYYYBBBBBBBBBBYYYGGGYYYBBBBBBBBYYYGBBBGYYYBBBBBBBYYYYGGGYYYYBBBBBBBYYYYYYYYYYYBBB',
        'BBBBBGGBBBBBBBBBGBBBBGBBBBBBBBBBBBGBBBGBBBBBBBBBBBBGBBBRRRRRRBRRRRRRGBBBBRBBBBRGRBBBBRBBBBBBRBBRBBBRBBRBBBBBBBBRRBBBBBRRBBBBBB',
        'BBBBBBBRRRRRBBBBBBBBBBBBBBBRBBBBBBBBBBBBBBBYYYYYBBBRRBBBBYYYYYYYYYYYYRRBBBYYYYYYYYYYYYYRRBBBYYBBBYYYYYBBBRRBBBYYBBBBBBBBBBBBBB',
        'BBBBBBBBBBBBBBGGGGBBBBBBBBBBBBBGGBBBBBBBYYYRRRRRRRRRBBBRRYGGYRRRRRRGGGGGBBBBYYYRRRRRRRRRBBBBBBBBBBBBBBBGGBBBBBBBBBBBBBBBBBGGGG',
        'BBRRBBBBBBBBBBBRRBBGGBRBBBBBBBBBRBGGBBBBBRYYYYYYYRBBBBBBBBYYBBBYBBBYYBBBBBBBBYYYYYYYYYBBBBBBBBBBGGBBBGGBBBBBBBBGGGGBBBBBGGGGBB',
        'BBBBBBBBBBBBBBBBBBBBBBBBBBBBBYYYYYYBBBRRRRRRRRYYYYYYYYBRRRRRRRRRYYYYYBBYBBRBRRBRBRYYYYYYYYBBBBBBBBBBBYYYYYYBBBBBBBBBBBBBBBBBBB',
        'BBBBBRRRBBBRRRBBBBBBBBRRRGGGGGRRRBBBBBBRRRGGGRGGGRRRBBBBRRRBGGGGGGGBRRRBBBBRBBGGGRGGGBBRBBBBBBBBGGGGGGGBBBBBBBBBBBGGGGGGGBBBBB',
        'BBYYYYYYYYYBBBBBBBBBBBBBGBBBBBBBBBBBBBBBRRRRRBBBBBBBGBBBBRBBBBRRRRRRRGGGBBBRBBBRRRBBBBBBGBBYBBRRRRBBBBBBBBBBBBYYYYYYYBBBBBBBBB',
        'BBBBBBBBYYYYYYBBBBBBBBBBBYBYBBBYYBBBBBBBBBYBBYBBBYBYBBBBBGGGYYYYYYYYGGBBBBGGGGGGGGGGGGGGGBBBGGRRGGGGGGGRRGBBBBBBRRBBBBBBBRRBBB',
        'BBBBBBBBBBGGGGGBBBBRRRRRRRRGGGGGGBBBBBBBBBBBBGGGGGGBBBBBBBBBBBRRRRRRRRRBBBBBBBRRRRRRRRRRRBBBBBRYYYYYYYYYYYRBBBBBBBYYYYYYYYYBBB',
        'BBBBBRRRRRRRRRBBBBBBBBRGGRRRRRRRRBBBBBBRGGGGRRRRRRRRBBBBRGGBBGYRRRRRRRRBBBBGGBBGYYYYYYYYBBBBBGGGGGYYYYYYYYBBBBBGGGGGYYYYYYYYBB',
        'BBBBBBBBBBRBBBBBBBBBBBBBBBBRRRBBBGBBBBRRRBBBYYYYYBGBGBBBBBRRRYYYYYYYBBGBBBBBBRRYYYYYYYBBGBBBBBBBRYYYYYYYGGBBBBBBBBBBYYYYYBBBBB',
        'BBBBRBRBRBBRRBBBBBBBBBRBRBRBRRRBBBBBBBBBBRRRBBRRRBBBBBBBBBBBRBBBRRRBBBBBBBBBBBYBBBBYYBBBBBBBBBBBYBBBBYYBBBBBBBBBBBYBBBBYYBBBBB',
        'BBBBBGGBBBBBBBBBBBBBBBGBGGBBBBBBBBBBBBGGGGGBBRRRRRBBBBBBBBRRBBRRRYYYYRBBBBBRRBBRRRYYYYYYYYBBRRRRRRRRYYYYYYRBBBBRRRRRRRRRRRRRBB',
        'BBBBGGGGBBBBBBBBBBBBBBBYBBBBBBBBBBBBBBBBYBBBBRRRRRRRBBBBBGGGBBBGGGGGGGBBBBGGGGBBBBGGGGGGBBBBGRRGGGGGGGRRGBBBBBBRRBBBBBBBRRBBBB',
        'BBBBRRRRRBBBBBBBBBBBBBBBRBBBBBRRRBBBBBBBBBRRRRRRRRBBBBBBBBGGBBBRBBBGGBBBBBBGYYGBBBRBGYYGBBBBBGYYGBBBBRGYYGBBBBBBGGBBBBBBBGGBBB',
        'BBBBBBBBBRBBBBBBBBBBBBBBBBRRRBBBBBBBBBBBBYYBBYBBYYBBBBBBBBYYBYYYYYBYYBBBBBBBYBYBYYYBYBYBBBBBBBBYYYBYBYYYBBBBBBBBBBYYYYYYYBBBBB',
        'BBBBBBBBBBBBBBBBBBBBBBBGGBRRBRRBBBBBBBBBGGGRRRRRRRBBBBBBBBGGGRRRRRRRBBBBBBBBBGGGRRRRRBBBBBBBBBBBGGGRRRBBBBBBBBBBBBBGBBRBBBBBBB',
        'BBBBBBBBBBBBBRBBBBBBBBBBBBBBBBBRRBBBBBYYYYYYYYYYYRRRBBBBBYGGGGGGGGGRRRRBBBYYYYYYYYYYYRRRBBBBBBBBBBBBBBBRRBBBBBBBBBBBBBBBBRBBBB',
        'BBBBBRBBBBBBBBBBBBBBBBRRBBBBBBBBBBBBBBBRRRYYYYYYYYYYYBBBRRRRGGGGGGGGGYBBBBBRRRYYYYYYYYYYYBBBBBRRBBBBBBBBBBBBBBBBBRBBBBBBBBBBBB',
        'BBBBBBBBBYBBBBBBBBBBBBBBBBYYYBBBBBBBBBBBBBBYYYBBBBBBBBBBBBRBYYYBBBBBBBBBBBBBRRYYBBBBBBBBBBBBBBRRRBBBBBBBBBBBBBBBRRRRBBBBBBBBBB',
        'BBBBRRRRBBBBBBBBBBBBBBRRRBBBBBBBBBBBBBBBRRYYBBBBBBBBBBBBBBRBYYYBBBBBBBBBBBBBBBBYYYBBBBBBBBBBBBBBBBYYYBBBBBBBBBBBBBBBBYBBBBBBBB',
        'BBBRBBBBBBBRBBBBBBBBBRGYYYGYYRRRBBBBBBBRYYGYYYYRBBRBBBBBBRYYYYYGYRBBRBBBBBBRYGYYYYYRBBRBBBBBBRYYYYGYYRRRBBBBBBBBRRRRRRRBBBBBBB',
        'BBBBBBBBBBBBGBBBBBBBBBBBBBBBBBGBBBBBBBBBBBBBBBBBGBBBBBBBBBBBBBBBBGBBBBBBBBBBBBBYYYYYBBBBBBBBBBBBRBBBBRBBBBBBBBBBBBRBBBBRBBBBBB',
        'BBBBBBBBBBBBBBBRBBBBBBBBBBBBBBBBRRRBBBBBBBBBBBBBBRRRGBBBBBBBBBBBBBRRBGGBBBBBBBBBRRRRRBBGBBBBBBBBRRRRRRBBBGBBBBBBRRRRRRBBBBBGBB',
        'BBBBBYYYYYYYYYBBBBBBBBBBYRRRRRYBBBBBBBBBBBBYRRRYBBBBBBBBBBBBBBYRYBBBBBBBBBBBBBBBBYBBBBBBBBBBBBBBBBBYBBBBBBBBBBBBBBBYYYYYBBBBBB',
    ];

    /**
     * @var array Lazy cache of {@link Graphic} instances for the default graphics.
     */
    private static $defaultGraphics;

    /**
     * @var int The display ID this graphics bank belongs to.
     */
    private $displayId;

    /**
     * @var array The graphics in this graphics bank.
     */
    private $graphics;

    /**
     * Construct a new GraphicsBank.
     *
     * @param int $displayId
     */
    public function __construct(int $displayId)
    {
        Display::verifyDisplayId($displayId);

        $this->displayId = $displayId;
        $this->graphics = [];
    }

    /**
     * Set the graphic of a certain index.
     *
     * @param int $index
     * @param Graphic $graphic
     */
    public function setGraphic(int $index, Graphic $graphic)
    {
        $this->graphics[self::getGraphicID($index)] = $graphic;
    }

    /**
     * Get the graphic ID for a certain graphic index.
     *
     * @param int $index
     * @return string
     */
    private static function getGraphicID(int $index): string
    {
        if ($index < 0 || $index > 25) {
            throw new InvalidArgumentException(sprintf('%d is not a valid graphic index.', $index));
        }

        return chr(65 + $index);
    }

    /**
     * Get the display ID this graphics bank belongs to.
     *
     * @return int
     */
    public function getDisplayId(): int
    {
        return $this->displayId;
    }

    /**
     * Get the graphic for a given index.
     *
     * Returns the default graphic for the given index if it has not been defined.
     *
     * @param int $index
     * @return Graphic
     */
    public function getGraphic(int $index): Graphic
    {
        if (!$this->hasGraphic($index)) {
            return self::getDefaultGraphic($index);
        }

        return $this->graphics[self::getGraphicID($index)];
    }

    /**
     * Query whether the graphics bank has a user-defined graphic for a given index.
     *
     * @param int $index
     * @return bool
     */
    public function hasGraphic(int $index): bool
    {
        return isset($this->graphics[self::getGraphicID($index)]);
    }

    /**
     * Get the default graphic for a given index as a {@link Graphic} instance.
     *
     * @param int $index
     * @return Graphic
     */
    public static function getDefaultGraphic(int $index): Graphic
    {
        if (!isset(self::$defaultGraphics[$index])) {
            if (!isset(self::DEFAULT[$index])) {
                throw new InvalidArgumentException(sprintf('%d is not a valid graphic index.', $index));
            }

            self::$defaultGraphics[$index] = new Graphic(self::DEFAULT[$index]);
        }

        return self::$defaultGraphics[$index];
    }

    /**
     * Get the configuration line(s) needed to define the graphics in this graphics bank.
     *
     * @return string
     */
    public function getConfiguration(): string
    {
        $config = '';

        /**
         * @var string $key
         * @var Graphic $graphic
         */
        foreach ($this->graphics as $key => $graphic) {
            $config .= sprintf("<ID%02X><G%s>%s\r\n", $this->displayId, $key, $graphic->getPixels());
        }

        return $config;
    }
}