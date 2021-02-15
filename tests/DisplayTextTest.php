<?php

use Knevelina\Prolite\DisplayText;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Knevelina\Prolite\DisplayText
 */
class DisplayTextTest extends TestCase
{
    public function normalized(): array
    {
        return [
            ['Helló world', 'Hello world'],
            ['áćéǵj́', 'acegj'],
            [chr(19), ''],
            [chr(128), '']
        ];
    }

    /**
     * @test
     * @dataProvider normalized
     * @param string $original
     * @param string $normalized
     */
    function it_normalizes_text(string $original, string $normalized)
    {
        $this->assertEquals($normalized, DisplayText::normalize($original));
    }

    /**
     * @test
     * @dataProvider formats
     * @param bool $flashing
     * @param bool $italic
     * @param bool $bold
     * @param string $format
     */
    function it_formats_text(bool $flashing, bool $italic, bool $bold, string $format): void
    {
        $this->assertEquals($format, DisplayText::format($flashing, $italic, $bold));
    }

    /**
     * @test
     * @dataProvider escapedTexts
     * @param string $original
     * @param string $escaped
     */
    function it_escapes_text(string $original, string $escaped)
    {
        $this->assertEquals($escaped, DisplayText::escape($original));
    }

    public function formats(): array
    {
        return [
            [false, false, false, DisplayText::FORMAT_NORMAL],
            [false, false, true, DisplayText::FORMAT_BOLD],
            [false, true, false, DisplayText::FORMAT_ITALIC],
            [false, true, true, DisplayText::FORMAT_BOLD_ITALIC],
            [true, false, false, DisplayText::FORMAT_FLASHING_NORMAL],
            [true, false, true, DisplayText::FORMAT_FLASHING_BOLD],
            [true, true, false, DisplayText::FORMAT_FLASHING_ITALIC],
            [true, true, true, DisplayText::FORMAT_FLASHING_BOLD_ITALIC],
        ];
    }

    public function escapedTexts(): array
    {
        return [
            ['<ID01>', ''],
            ['<IDX1>', '<IDX1>'],
            ['Hello <PA> world', 'Hello  world'],
            ['Hello <P1> world', 'Hello <P1> world'],
            ['Hello <PBC> world', 'Hello <PBC> world'],
            ['Hello <U#> world', 'Hello  world'],
            ['Hello <U_> world', 'Hello  world'],
            ['Hello <U> world', 'Hello <U> world'],
            ['Hello <BA> world', 'Hello <BA> world'],
            ['Hello <BAB> world', 'Hello <BAB> world'],
            ['Hello <B1> world', 'Hello <B1> world'],
            ['Hello <B> world', 'Hello <B> world'],
            ['Hello <CA> world', 'Hello <CA> world'],
            ['Hello <CAB> world', 'Hello <CAB> world'],
            ['Hello <C1> world', 'Hello <C1> world'],
            ['Hello <C> world', 'Hello <C> world'],
            ['Hello <SA> world', 'Hello <SA> world'],
            ['Hello <SAB> world', 'Hello <SAB> world'],
            ['Hello <S1> world', 'Hello <S1> world'],
            ['Hello <S> world', 'Hello <S> world'],
            ['Hello <FA> world', 'Hello <FA> world'],
            ['Hello <FAB> world', 'Hello <FAB> world'],
            ['Hello <F1> world', 'Hello <F1> world'],
            ['Hello <F> world', 'Hello <F> world'],
            ['Hello <TA>10203A world', 'Hello 10203A world'],
            ['Hello <TA>*0001A world', 'Hello *0001A world'],
            ['Hello <TA>***01A world', 'Hello ***01A world'],
            ['Hello <TA>*****A world', 'Hello *****A world'],
            ['Hello <TA>*00012 world', 'Hello *00012 world'],
            ['Hello <TA>102031 world', 'Hello 102031 world'],
            ['Hello <TA>*00011 world', 'Hello *00011 world'],
            ['Hello <TA>***011 world', 'Hello ***011 world'],
            ['Hello <TA>*****1 world', 'Hello *****1 world'],
            ['Hello <GA> world', 'Hello  world'],
            ['Hello <GAB> world', 'Hello <GAB> world'],
            ['Hello <G1> world', 'Hello <G1> world'],
            ['Hello <G> world', 'Hello <G> world'],
            ['Hello <DPA> world', 'Hello  world'],
            ['Hello <DP*> world', 'Hello  world'],
            ['Hello <DP**> world', 'Hello <DP**> world'],
            ['Hello <DPAB> world', 'Hello <DPAB> world'],
            ['Hello <DP1> world', 'Hello <DP1> world'],
            ['Hello <DP> world', 'Hello <DP> world'],
            ['Hello <DTA> world', 'Hello  world'],
            ['Hello <DT*> world', 'Hello  world'],
            ['Hello <DT**> world', 'Hello <DT**> world'],
            ['Hello <DTAB> world', 'Hello <DTAB> world'],
            ['Hello <DT1> world', 'Hello <DT1> world'],
            ['Hello <DT> world', 'Hello <DT> world'],
            ['Hello <DGA> world', 'Hello  world'],
            ['Hello <DG*> world', 'Hello  world'],
            ['Hello <DG**> world', 'Hello <DG**> world'],
            ['Hello <DGAB> world', 'Hello <DGAB> world'],
            ['Hello <DG1> world', 'Hello <DG1> world'],
            ['Hello <DG> world', 'Hello <DG> world'],
            ['Hello <D*> world', 'Hello  world'],
            ['Hello <D**> world', 'Hello <D**> world'],
            ['Hello <RPA> world', 'Hello  world'],
            ['Hello <RPAB> world', 'Hello <RPAB> world'],
            ['Hello <RP1> world', 'Hello <RP1> world'],
            ['Hello <RP> world', 'Hello <RP> world'],
            ['Hello <RP*> world', 'Hello  world'],
            ['Hello <RP**> world', 'Hello <RP**> world'],
            ['Hello <T0001023040506> world', 'Hello  world'],
            ['Hello <TA000000000000> world', 'Hello <TA000000000000> world']
        ];
    }
}