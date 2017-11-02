<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace Validators\ObjectValidators;


use MS\Json\SchemaValidator\Validator;
use PHPUnit\Framework\TestCase;

class DependenciesValidatorTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testClassicDependencies()
    {
        $schema = json_decode('{ "type": "object", "properties": { "name": { "type": "string" }, 
            "credit_card": { "type": "number" }, "billing_address": { "type": "string" } }, 
            "required": ["name"], "dependencies": { "credit_card": ["billing_address"] } }', true);
        $validator = new Validator($schema);

        $json = json_decode('{ "name": "John Doe",  "credit_card": 5555555555555555, 
            "billing_address": "555 Debtor\'s Lane" }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "name": "John Doe", "credit_card": 5555555555555555 }', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('{ "name": "John Doe" }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "name": "John Doe", "billing_address": "555 Debtor\'s Lane" }', true);
        $this->assertTrue($validator->validate($json));

        $schema = json_decode('{ "type": "object", "properties": { "name": { "type": "string" }, 
            "credit_card": { "type": "number" }, "billing_address": { "type": "string" } }, 
            "required": ["name"], "dependencies": { "credit_card": ["billing_address"], 
            "billing_address": ["credit_card"] } }', true);
        $validator = new Validator($schema);

        $json = json_decode('{ "name": "John Doe", "credit_card": 5555555555555555 }', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('{ "name": "John Doe", "billing_address": "555 Debtor\'s Lane" }', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('{ "name": "John Doe", "credit_card": 5555555555555555, 
            "billing_address": "555 Debtor\'s Lane" }', true);
        $this->assertTrue($validator->validate($json));
    }

    public function testSchemaDependencies()
    {
        $schema = json_decode('{ "type": "object", "properties": { "name": { "type": "string" }, 
            "credit_card": { "type": "number" } }, "required": ["name"], "dependencies": { "credit_card": { 
            "properties": { "billing_address": { "type": "string" } }, "required": ["billing_address"] } } }', true);
        $validator = new Validator($schema);

        $json = json_decode('{ "name": "John Doe", "credit_card": 5555555555555555, 
            "billing_address": "555 Debtor\'s Lane" }', true);
        $this->assertTrue($validator->validate($json));
        $json = json_decode('{ "name": "John Doe", "credit_card": 5555555555555555 }', true);
        $this->assertFalse($validator->validate($json));
        $json = json_decode('{ "name": "John Doe", "billing_address": "555 Debtor\'s Lane" }', true);
        $this->assertTrue($validator->validate($json));
    }
}
