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

class RequiredValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testRequired()
    {
        $schema = json_decode('{ "type": "object", "properties": { "name": { "type": "string" }, 
            "email": { "type": "string" }, "address": { "type": "string" }, "telephone": { "type": "string" } }, 
            "required": ["name", "email"] }', true);
        $validator = new Validator($schema);

        $json = json_decode('{ "name": "William Shakespeare", "email": "bill@stratford-upon-avon.co.uk" }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "name": "William Shakespeare", "email": "bill@stratford-upon-avon.co.uk", 
            "address": "Henley Street, Stratford-upon-Avon, Warwickshire, England", 
            "authorship": "in question" }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "name": "William Shakespeare", "address": "Henley Street, Stratford-upon-Avon, 
            Warwickshire, England", }', true);
        $this->assertFalse($validator->validate($json));
    }
}
