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

class ConstValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testConst()
    {
        $schema = json_decode('{ "const": "CONSTANT" }', true);
        $validator = new Validator($schema);

        $json = "CONSTANT";
        $this->assertTrue($validator->validate($json));
        $json = 42;
        $this->assertFalse($validator->validate($json));

        $schema = json_decode('{ "const": null }', true);
        $validator = new Validator($schema);

        $json = null;
        $this->assertTrue($validator->validate($json));
        $json = "string";
        $this->assertFalse($validator->validate($json));
    }
}
