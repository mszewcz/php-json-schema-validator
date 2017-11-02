<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace Validators\NumericValidators;


use MS\Json\SchemaValidator\Validator;
use PHPUnit\Framework\TestCase;

class MaximumValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testExclusiveMaximum()
    {
        $schema = json_decode('{ "type": "number", "maximum": 100 }', true);
        $validator = new Validator($schema);

        $json = 10;
        $this->assertTrue($validator->validate($json));
        $json = 90;
        $this->assertTrue($validator->validate($json));
        $json = 100;
        $this->assertTrue($validator->validate($json));
        $json = 101;
        $this->assertFalse($validator->validate($json));
    }
}
