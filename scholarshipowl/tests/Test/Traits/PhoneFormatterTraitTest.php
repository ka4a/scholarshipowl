<?php namespace Test\Services;

use App\Testing\TestCase;
use App\Traits\PhoneFormatter;

class PhoneFormatterTraitTest extends TestCase
{
    /** @var PhoneFormatter|\PHPUnit_Framework_MockObject_MockObject */
    protected $phoneFormatterMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->phoneFormatterMock = $this->getMockForTrait(PhoneFormatter::class);
    }

    public function testToPhoneFormat()
    {
        $this->assertTrue('+913526537842' === $this->phoneFormatterMock->toPhoneFormat('+913526537842', false));
        $this->assertTrue('(123) 123 - 1234' === $this->phoneFormatterMock->toPhoneFormat('+11231231234'));
        $this->assertTrue('(123) 123 - 1234' === $this->phoneFormatterMock->toPhoneFormat('+11231231234'));

        $formattedPhone = $this->phoneFormatterMock->unifyPhoneFormat('+1(123) 123 - 1234');
        $this->assertTrue('(123) 123 - 1234' === $this->phoneFormatterMock->toPhoneFormat($formattedPhone));
    }


    public function testUnifyPhoneFormat()
    {
        $this->assertTrue('+11231231234' === $this->phoneFormatterMock->unifyPhoneFormat('+((123) 123 - 1234')); // broken phones
        $this->assertTrue('+11231231234' === $this->phoneFormatterMock->unifyPhoneFormat('+(123) 123 - 1234')); // broken phones
        $this->assertTrue('+11231231234' === $this->phoneFormatterMock->unifyPhoneFormat('+1(123) 123 - 1234'));
        $this->assertTrue('+16088438065' === $this->phoneFormatterMock->unifyPhoneFormat('(608) 843 - 8065'));
        $this->assertTrue('+16088438065' === $this->phoneFormatterMock->unifyPhoneFormat('+1(608) 843 - 8065'));
        $this->assertTrue('+18594963246' === $this->phoneFormatterMock->unifyPhoneFormat('+18594963246'));
        $this->assertTrue('+913526537842' === $this->phoneFormatterMock->unifyPhoneFormat('+913526537842'));
        $this->assertTrue('+212623118217' === $this->phoneFormatterMock->unifyPhoneFormat('+212623118217'));
        $this->assertTrue('+212623118217' === $this->phoneFormatterMock->unifyPhoneFormat('212623118217'));
        $this->assertTrue('+380930133734' === $this->phoneFormatterMock->unifyPhoneFormat('380930133734'));
    }
}
