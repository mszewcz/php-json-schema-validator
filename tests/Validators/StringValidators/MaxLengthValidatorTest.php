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

class MaxLengthValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testLength()
    {
        $schema = json_decode('{ "type": "string", "maxLength": 3 }', true);
        $validator = new Validator($schema);

        $json = 'Ą';
        $this->assertTrue($validator->validate($json));
        $json = 'ĄĆ';
        $this->assertTrue($validator->validate($json));
        $json = 'ĄĆĘ';
        $this->assertTrue($validator->validate($json));
        $json = 'ĄĆĘŁ';
        $this->assertFalse($validator->validate($json));
    }
}
