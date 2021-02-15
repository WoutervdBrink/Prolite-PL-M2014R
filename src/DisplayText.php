<?php

namespace Knevelina\Prolite;

use Transliterator;

class DisplayText
{
    const COLOR_BRIGHT_GREEN = '<CL>';
    const COLOR_BRIGHT_LIME = '<CK>';
    const COLOR_BRIGHT_ORANGE = '<CE>';
    const COLOR_BRIGHT_RED = '<CC>';
    const COLOR_BRIGHT_YELLOW = '<CH>';
    const COLOR_DIM_GREEN = '<CN>';
    const COLOR_DIM_LIME = '<CJ>';
    const COLOR_DIM_RED = '<CA>';
    const COLOR_GREEN = '<CM>';
    const COLOR_GREEN_ON_RED = '<CU>';
    const COLOR_GREEN_ON_RED_3D = '<CY>';
    const COLOR_GREEN_RED_3D = '<CS>';
    const COLOR_GREEN_YELLOW_3D = '<CT>';
    const COLOR_LIGHT_YELLOW = '<CF>';
    const COLOR_LIME = '<CI>';
    const COLOR_LIME_ON_RED_3D = '<CX>';
    const COLOR_ORANGE = '<CD>';
    const COLOR_ORANGE_ON_GREEN_3D = '<CW>';
    const COLOR_RAINBOW = '<CP>';
    const COLOR_RED = '<CB>';
    const COLOR_RED_GREEN_3D = '<CQ>';
    const COLOR_RED_ON_GREEN = '<CV>';
    const COLOR_RED_ON_GREEN_3D = '<CZ>';
    const COLOR_RED_YELLOW_3D = '<CR>';
    const COLOR_YELLOW = '<CG>';
    const COLOR_YELLOW_GREEN_RED = '<CO>';

    /** @var string Random color and presentation */
    const DISPLAY_AUTO = '<FA>';

    /** @var string Open from center */
    const DISPLAY_OPEN = '<FB>';

    /** @var string Covers text */
    const DISPLAY_COVER = '<FC>';

    /** @var string Instantly replaces text */
    const DISPLAY_APPEAR = '<FD>';

    /** @var string Rolling colors */
    const DISPLAY_CYCLING = '<FE>';

    /** @var string Blank screen right to left */
    const DISPLAY_CLOSE_RL = '<FF>';

    /** @var string Blank screen left to right */
    const DISPLAY_CLOSE_LR = '<FG>';

    /** @var string Blank screen outer to center */
    const DISPLAY_CLOSE_OC = '<FH>';

    /** @var string Scroll up from bottom */
    const DISPLAY_SCROLL_UP = '<FI>';

    /** @var string Scroll down from top */
    const DISPLAY_SCROLL_DOWN = '<FJ>';

    /** @var string Two layers slide together to form text */
    const DISPLAY_OVERLAP = '<FK>';

    /** @var string Falling dots form text */
    const DISPLAY_STACKING = '<FL>';

    /** @var string Pac-Man */
    const DISPLAY_PACMAN = '<FM>';

    /** @var string Random creature walking */
    const DISPLAY_RANDOM_CREATURE_WALKING = '<FN>';

    /** @var string Display beeps */
    const DISPLAY_BEEP = '<FO>';

    /** @var string Short delay of motion */
    const DISPLAY_PAUSE = '<FP>';

    /** @var string Blank screen until the next timer activates */
    const DISPLAY_BLANK = '<FQ>';

    /** @var string Random dots appear forming text */
    const DISPLAY_RANDOM = '<FR>';

    /** @var string Roll message right to left (default) */
    const DISPLAY_SHIFT = '<FS>';

    /** @var string Show time an date; no choice of formatting */
    const DISPLAY_TIME_AND_DATE = '<FT>';

    /** @var string Change text color each time */
    const DISPLAY_MAGIC = '<FU>';

    /** @var string Cursive "Thank you" */
    const DISPLAY_THANK_YOU = '<FV>';

    /** @var string Cursive "Welcome" */
    const DISPLAY_WELCOME = '<FW>';

    const DISPLAY_SPEED_1 = '<FX>';
    const DISPLAY_SPEED_2 = '<FY>';
    const DISPLAY_SPEED_3 = '<FZ>';

    const FORMAT_NORMAL = '<SA>';
    const FORMAT_BOLD = '<SB>';
    const FORMAT_ITALIC = '<SC>';
    const FORMAT_BOLD_ITALIC = '<SD>';
    const FORMAT_FLASHING_NORMAL = '<SE>';
    const FORMAT_FLASHING_BOLD = '<SF>';
    const FORMAT_FLASHING_ITALIC = '<SG>';
    const FORMAT_FLASHING_BOLD_ITALIC = '<SH>';

    /**
     * Escape a string to make it safe to use as a page's contents.
     *
     * Tries its best to retain as much text as possible. Unfortunately we cannot escape characters and thus we cannot
     * retain literal commands.
     *
     * This function is a bit too agressive. However, the firmware has proven to be quite unstable and including control
     * sequences in unexpected places causes undocumented behavior, sometimes even resulting in the sign refusing to
     * consume and process more input and requiring a physical hard reset.
     *
     * @param string $string
     * @return string
     */
    public static function escape(string $string): string
    {
        return preg_replace('/<(ID[0-9A-F]{2}|(R?P|DP|D?T|D?G)[A-Z*]|U.|D\\*|T[0-9]{13}|Q([+\-]))>/', '', $string);
    }

    /**
     * Create a formatting token.
     *
     * Include this token somewhere in a page's contents to set the format of the text from that token on.
     *
     * @param bool $flashing
     * @param bool $italic
     * @param bool $bold
     * @return string
     */
    public static function format(bool $flashing, bool $italic, bool $bold): string
    {
        return '<S' . chr(65 + ($flashing ? 4 : 0) + ($italic ? 2 : 0) + ($bold ? 1 : 0)) . '>';
    }

    /**
     * Normalize a string to be written to the sign.
     *
     * Replaces diactrics with their ASCII companions and removes any characters the sign does not understand and/or
     * accept.
     *
     * @param string $string
     * @return string
     */
    public static function normalize(string $string): string
    {
        $transliterator = Transliterator::createFromRules(
            ':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;',
            Transliterator::FORWARD
        );

        // Change diactrics to normal ASCII letters
        $string = $transliterator->transliterate($string);

        // Remove anything non-print
        return filter_var($string, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    }
}