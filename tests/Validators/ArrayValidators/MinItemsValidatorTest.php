<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\Json\SchemaValidator\Validator;
use PHPUnit\Framework\TestCase;

class MinItemsValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testItemsLength()
    {
        $schema = json_decode('{ "type": "array", "minItems": 2 }', true);
        $validator = new Validator($schema);

        $json = json_decode('[]', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('[1]', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('[1,2]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1,2,3]', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('[1,2,3,4]', true);
        $this->assertTrue($validator->validate($json));
    }
}
