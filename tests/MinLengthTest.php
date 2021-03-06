<?php

/**
 * MinLengthTest.
 *
 * PHP Version 7.2.11-3
 *
 * @package   Verum-PHP
 * @license   MIT https://github.com/SandroMiguel/verum-php/blob/master/LICENSE
 * @author    Sandro Miguel Marques <sandromiguel@sandromiguel.com>
 * @copyright 2020 Sandro
 * @since     Verum-PHP 1.0.0
 * @version   1.1.1 (25/06/2020)
 * @link      https://github.com/SandroMiguel/verum-php
 */

declare(strict_types=1);

namespace Verum\Tests;

use PHPUnit\Framework\TestCase;
use Verum\Exceptions\ValidatorException;
use Verum\Rules\RuleFactory;
use Verum\Validator;

/**
 * Class MinLengthTest | tests/MinLengthTest.php | Test for MinLength
 */
class MinLengthTest extends TestCase
{
    /**
     * Validate.
     *
     * @param mixed $fieldValue Field Value to validate.
     * @param array $ruleValues Rule values.
     *
     * @return bool Returns TRUE if it passes the validation, FALSE otherwise.
     */
    private function validate($fieldValue, array $ruleValues): bool
    {
        $fieldName = 'some_field_name';
        $fieldLabel = 'Some Field Name';
        $ruleName = 'min_length';
        $validator = new Validator(
            [
                $fieldName => $fieldValue,
            ],
            [
                $fieldName => [
                    'label' => $fieldLabel,
                    'rules' => [$ruleName => $ruleValues],
                ],
            ]
        );
        $rule = RuleFactory::loadRule($validator, $fieldValue, $ruleValues, $fieldLabel, $ruleName, '');

        return $rule->validate();
    }

    /**
     * If the Rule Values are not defined, an exception should be thrown.
     *
     * @return void
     */
    public function testValidateWithoutRuleValues(): void
    {
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage(
            'Invalid argument; Argument name: $ruleValues; Argument value: null; Rule "min_length": the rule value is mandatory'
        );
        $this->validate(['some text'], []);
    }

    /**
     * A Null (null) value should violate the rule.
     *
     * @return void
     */
    public function testValidateNull(): void
    {
        $this->assertFalse($this->validate(null, [5]));
    }

    /**
     * The String ('text with 23 characters') value should violate the rule with min length of 30 characters.
     *
     * @return void
     */
    public function testValidateLongText(): void
    {
        $this->assertFalse($this->validate('text with 23 characters', [30]));
    }

    /**
     * The String ('text with 23 characters') value should pass the rule with min length of 20 characters.
     *
     * @return void
     */
    public function testPassValidateShortText(): void
    {
        $this->assertTrue($this->validate('text with 23 characters', [20]));
    }
}
