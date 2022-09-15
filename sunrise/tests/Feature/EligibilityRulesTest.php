<?php namespace Tests\Feature;

use App\Entities\Field;
use App\Rules\EligibilityBetween;
use App\Rules\EligibilityEquals;
use App\Rules\EligibilityGt;
use App\Rules\EligibilityGte;
use App\Rules\EligibilityIn;
use App\Rules\EligibilityLt;
use App\Rules\EligibilityLte;
use App\Rules\EligibilityNot;
use App\Rules\EligibilityNotIn;
use Tests\TestCase;

class EligibilityRulesTest extends TestCase
{
    public function test_eligibility_not_in()
    {
        $rule = new EligibilityNotIn('3,5,7');
        $this->assertTrue($rule->passes(Field::NAME, 2));
        $this->assertTrue($rule->passes(Field::NAME, 4));
        $this->assertTrue($rule->passes(Field::NAME, 6));
        $this->assertTrue($rule->passes(Field::NAME, 8));
        $this->assertFalse($rule->passes(Field::NAME, 3));
        $this->assertFalse($rule->passes(Field::NAME, 5));
        $this->assertFalse($rule->passes(Field::NAME, 7));
        $this->assertEquals('The :attribute must be not one of 3, 5, 7.', $rule->message());
    }

    public function test_eligibility_in()
    {
        $rule = new EligibilityIn('3,5,7');
        $this->assertTrue($rule->passes(Field::NAME, 3));
        $this->assertTrue($rule->passes(Field::NAME, 5));
        $this->assertTrue($rule->passes(Field::NAME, 7));
        $this->assertFalse($rule->passes(Field::NAME, 4));
        $this->assertFalse($rule->passes(Field::NAME, 6));
        $this->assertEquals('The :attribute must be one of 3, 5, 7.', $rule->message());
    }

    public function test_eligibility_between()
    {
        $rule = new EligibilityBetween('3,5');
        $this->assertTrue($rule->passes(Field::NAME, 3));
        $this->assertTrue($rule->passes(Field::NAME, 4));
        $this->assertTrue($rule->passes(Field::NAME, 5));
        $this->assertFalse($rule->passes(Field::NAME, 6));
        $this->assertFalse($rule->passes(Field::NAME, 2));
        $this->assertEquals('The :attribute must be between 3 and 5.', $rule->message());
    }

    public function test_eligibility_lte()
    {
        $rule = new EligibilityLte(5);
        $this->assertTrue($rule->passes(Field::NAME, 5));
        $this->assertTrue($rule->passes(Field::NAME, 4));
        $this->assertFalse($rule->passes(Field::NAME, 6));
        $this->assertEquals('The :attribute must be less or equal 5.', $rule->message());
    }

    public function test_eligibility_lt()
    {
        $rule = new EligibilityLt(5);
        $this->assertTrue($rule->passes(Field::NAME, 4));
        $this->assertFalse($rule->passes(Field::NAME, 5));
        $this->assertEquals('The :attribute must be less than 5.', $rule->message());
    }

    public function test_eligibility_gte()
    {
        $rule = new EligibilityGte(5);
        $this->assertTrue($rule->passes(Field::NAME, 5));
        $this->assertTrue($rule->passes(Field::NAME, 6));
        $this->assertFalse($rule->passes(Field::NAME, 4));
        $this->assertEquals('The :attribute must be bigger or equal 5.', $rule->message());
    }

    public function test_eligibility_gt()
    {
        $rule = new EligibilityGt(5);
        $this->assertTrue($rule->passes(Field::NAME, 6));
        $this->assertFalse($rule->passes(Field::NAME, 5));
        $this->assertEquals('The :attribute must be bigger than 5.', $rule->message());
    }

    public function test_eligibility_not()
    {
        $rule = new EligibilityNot('3,2');
        $this->assertTrue($rule->passes(Field::NAME, '3'));
        $this->assertFalse($rule->passes(Field::NAME, '3,2'));
        $this->assertEquals('The :attribute must be not equal 3,2.', $rule->message());
    }

    public function test_eligibility_equals()
    {
        $rule = new EligibilityEquals(3);
        $this->assertTrue($rule->passes(Field::NAME, 3));
        $this->assertFalse($rule->passes(Field::NAME, 2));
        $this->assertEquals('The :attribute must be equal 3.', $rule->message());
    }
}
