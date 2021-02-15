<?php

namespace Knevelina\Prolite\Models;

use InvalidArgumentException;

class Graphic
{
    /**
     * The width of a graphic.
     */
    const WIDTH = 18;

    /**
     * The height of a graphic.
     */
    const HEIGHT = 7;

    const RED = 'R';
    const GREEN = 'G';
    const YELLOW = 'Y';
    const BLACK = 'B';

    /**
     * The pixels in this graphic.
     *
     * The pixels are defined as a string of characters representing the rows of pixels in the graphic.
     *
     * @var string
     */
    private $pixels;

    /**
     * Construct a new graphic.
     *
     * @param string $pixels
     */
    public function __construct(string $pixels)
    {
        if (strlen($pixels) !== self::WIDTH * self::HEIGHT) {
            throw new InvalidArgumentException(sprintf('Invalid graphic size %d', strlen($pixels)));
        }

        for ($i = 0; $i < self::WIDTH * self::HEIGHT; $i++) {
            $pixel = $pixels[$i];

            if (!in_array($pixel, [self::RED, self::GREEN, self::YELLOW, self::BLACK])) {
                throw new InvalidArgumentException(sprintf('Invalid pixel type %s', $pixel));
            }
        }

        $this->pixels = $pixels;
    }

    /**
     * Get the pixels in this graphic.
     *
     * @return string
     */
    public function getPixels(): string
    {
        return $this->pixels;
    }

    /**
     * Get the pixel in this graphic at a certain location.
     *
     * @param int $x
     * @param int $y
     * @return string
     */
    public function getPixel(int $x, int $y): string
    {
        if ($x < 0 || $x >= self::WIDTH || $y < 0 || $y >= self::HEIGHT) {
            throw new InvalidArgumentException(sprintf('Invalid coordinates %d, %d', $x, $y));
        }
        return $this->pixels[self::WIDTH * $y + $x];
    }
}