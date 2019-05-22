<?php

namespace Jsvrcek\ICS\Tests\Utility;

use Jsvrcek\ICS\Utility\Formatter;

class FormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Jsvrcek\ICS\Formatter::getFormattedDateTime
     */
    public function testGetFormattedDateTime()
    {
        $ce = new Formatter();

        $dateTime = new \DateTime('1998-01-18 23:00:00');
        $expected = '19980118T230000';
        $actual = $ce->getFormattedDateTime($dateTime);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Jsvrcek\ICS\Formatter::getFormattedTimeOffset
     */
    public function testGetFormattedTimeOffset()
    {
        $ce = new Formatter();

        $offset = -18000;
        $expected = '-0500';
        $actual = $ce->getFormattedTimeOffset($offset);
        $this->assertEquals($expected, $actual);

        $offset = -14400;
        $expected = '-0400';
        $actual = $ce->getFormattedTimeOffset($offset);
        $this->assertEquals($expected, $actual);

        $offset = 14400;
        $expected = '+0400';
        $actual = $ce->getFormattedTimeOffset($offset);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Jsvrcek\ICS\Formatter::getFormattedUTCDateTime
     */
    public function testGetFormattedUTCDateTime()
    {
        $ce = new Formatter();

        $dateTime = new \DateTime('1998-01-18 23:00:00', new \DateTimeZone('America/New_York'));
        $expected = '19980119T040000Z';
        $actual = $ce->getFormattedUTCDateTime($dateTime);
        $this->assertEquals($expected, $actual);
        $ce = new Formatter();

        $dateTime = new \DateTime('1998-01-18 11:00:00', new \DateTimeZone('America/New_York'));
        $expected = '19980118T160000Z';
        $actual = $ce->getFormattedUTCDateTime($dateTime);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \Jsvrcek\ICS\Utility\Formatter::getFormattedDateTimeWithTimeZone
     */
    public function testGetFormattedLocalDateTimeWithTimeZone()
    {
        $ce = new Formatter();

        $dateTime = new \DateTime('1998-01-18 23:00:00', new \DateTimeZone('America/New_York'));
        $expected = 'TZID=America/New_York:19980118T230000';
        $actual = $ce->getFormattedDateTimeWithTimeZone($dateTime);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Jsvrcek\ICS\Formatter::getFormattedUri
     */
    public function testGetFormattedUri()
    {
        $ce = new Formatter();

        $expected = 'mailto:test@example.com';
        $actual = $ce->getFormattedUri('test@example.com');
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Jsvrcek\ICS\Formatter::getFormattedDateInterval
     */
    public function testGetFormattedDateInterval()
    {
        $ce = new Formatter();

        $tests = array(
            "PT15M",
            "PT1H",
            "P345D",
            "P1Y6M29DT4H34M23S"
        );

        foreach ($tests as $test) {
            $this->assertEquals(
                $test,
                $ce->getFormattedDateInterval(new \DateInterval($test)),
                $test
            );
        }
    }

    /**
     * @covers Jsvrcek\ICS\Formatter::getEscapedText
     */
    public function testgetEscapedText()
    {
        $ce = new Formatter();

        $strings = [
            [
                'original' => '14 Main St, Capital City',
                'expected' => '14 Main St\, Capital City',
                'name' => 'a comma'
            ],
            [
                'original' => 'A comma and a dot; Semi-colon',
                'expected' => 'A comma and a dot\; Semi-colon',
                'name' => 'a semi-colon'
            ],
            [
                'original' => 'Here is a comma, and; a semi-colon',
                'expected' => 'Here is a comma\, and\; a semi-colon',
                'name' => 'both comma and semi-colon'
            ],
            [
                'original' => 'This comma\, is pre-escaped',
                'expected' => 'This comma\, is pre-escaped',
                'name' => 'a pre-escaped comma'
            ],
            [
                'original' => 'Pre-escaped\; This Semi-colon is',
                'expected' => 'Pre-escaped\; This Semi-colon is',
                'name' => 'a pre-escaped semi-colon'
            ],
            [
                'original' => 'Pre-escaped\; This Semi-colon is\, and so was that comma',
                'expected' => 'Pre-escaped\; This Semi-colon is\, and so was that comma',
                'name' => 'both a pre-escaped comma and a pre-escaped semi-colon'
            ],
            [
                'original' => 'This comma\, was pre-escaped while this one, is not',
                'expected' => 'This comma\, was pre-escaped while this one\, is not',
                'name' => 'a pre-escaped comma and an unescaped comma'
            ],
            [
                'original' => 'First\; we pre-escape. Then; we forget to',
                'expected' => 'First\; we pre-escape. Then\; we forget to',
                'name' => 'a pre-escaped semi-colon and an unescaped semi-colon'
            ],
            [
                'original' => 'How many, ducks\; Is a question\, This; is not',
                'expected' => 'How many\, ducks\; Is a question\, This\; is not',
                'name' => 'both pre-escaped comma and semi-colon, and unescaped comma and semi-colon'
            ]
        ];

        foreach ($strings as $string) {
            $this->assertEquals(
                $string['expected'],
                $ce->getEscapedText($string['original']),
                'Failed on escaping string including ' . $string['name']
            );
        }
    }
}
