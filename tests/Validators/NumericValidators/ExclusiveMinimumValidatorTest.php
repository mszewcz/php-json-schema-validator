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

class ExclusiveMinimumValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testExclusiveMaximum()
    {
        $schema = json_decode('{ "type": "number", "exclusiveMinimum": 0 }', true);
        $validator = new Validator($schema);

        $json = -1;
        $this->assertFalse($validator->validate($json));
        $json = 0;
        $this->assertFalse($validator->validate($json));
        $json = 100;
        $this->assertTrue($validator->validate($json));
        $json = 101;
        $this->assertTrue($validator->validate($json));
    }
}
