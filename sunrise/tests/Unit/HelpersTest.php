<?php namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function test_phone_format()
    {
        $this->assertEquals('8504961162', phone_format('+1 (850) 496 - 1162'));
        $this->assertEquals('8036699798', phone_format('(803) 669 - 9798'));
        $this->assertEquals('7077616379', phone_format('+17077616379'));
        $this->assertEquals('1077616379', phone_format('+11077616379'));

        $this->assertEquals('+1 (803) 669-9798', phone_format_us('(803) 669 - 9798'));
        $this->assertEquals('+1 (707) 761-6379', phone_format_us('+17077616379'));
        $this->assertEquals('+1 (850) 496-1162', phone_format_us('+1 (850) 496 - 1162'));
        $this->assertEquals('+1 (150) 496-1162', phone_format_us('+1 (150) 496 - 1162'));
    }
}
