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

class PatternValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testPattern()
    {
        $schema = json_decode('{ "type": "string", "pattern": "^(\\\\([0-9]{3}\\\\))?[0-9]{3}-[0-9]{4}$" }', true);
        $validator = new Validator($schema);

        $json = '555-1212';
        $this->assertTrue($validator->validate($json));
        $json = '(888)555-1212';
        $this->assertTrue($validator->validate($json));
        $json = '(888)555-1212 ext. 532';
        $this->assertFalse($validator->validate($json));
        $json = '(800)FLOWERS';
        $this->assertFalse($validator->validate($json));
    }
}
