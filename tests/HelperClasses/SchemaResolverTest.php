<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\Json\SchemaValidator\Schema\Resolver;
use PHPUnit\Framework\TestCase;

class SchemaResolverTest extends TestCase
{
    public function stepUp()
    {
    }

    public function testResolveRef()
    {
        $rootSchema = json_decode('{ "type": "array", "items": { "$ref": "#/definitions/positiveInteger" }, 
            "definitions": { "positiveInteger": { "type": "integer", "exclusiveMinimum": 0 } } }', true);
        $schema = json_decode('{ "$ref": "#/definitions/positiveInteger" }', true);

        $schemaResolver = new Resolver($schema, $rootSchema);
        $resolvedSchema = $schemaResolver->resolve();
        $expected = json_decode('{ "type": "integer", "exclusiveMinimum": 0 }', true);
        $this->assertEquals($expected, $resolvedSchema);

        $rootSchema = json_decode('{ "type": "array", "items": { "$ref": "#/definitions/positiveInteger" }, 
            "definitions": { "positiveInteger": { "type": "integer", "exclusiveMinimum": 0 } } }', true);
        $schema = json_decode('{ "$ref": "#" }', true);

        $schemaResolver = new Resolver($schema, $rootSchema);
        $resolvedSchema = $schemaResolver->resolve();
        $this->assertEquals($rootSchema, $resolvedSchema);
    }

    public function testResolveException()
    {
        $this->expectExceptionMessage('Cannot resolve schema');

        $rootSchema = json_decode('{}', true);
        $schema = json_decode('{ "$ref": "/incorrect/ref" }', true);

        $schemaResolver = new Resolver($schema, $rootSchema);
        $schemaResolver->resolve();
    }

    public function testLoadSchema()
    {
        $schemaResolver = new Resolver('http://json-schema.org/draft-06/schema#',
            'http://json-schema.org/draft-06/schema#');
        $resolvedSchema = $schemaResolver->resolve();
        $expected = json_decode('{"$schema": "http://json-schema.org/draft-06/schema#",
            "$id": "http://json-schema.org/draft-06/schema#","title": "Core schema meta-schema",
            "definitions": {"schemaArray": {"type": "array","minItems": 1,"items": { "$ref": "#" }},
            "nonNegativeInteger": {"type": "integer","minimum": 0},"nonNegativeIntegerDefault0": {"allOf": 
            [{ "$ref": "#/definitions/nonNegativeInteger" },{ "default": 0 }]},"simpleTypes": {"enum": 
            ["array","boolean","integer","null","number","object","string"]},"stringArray": {"type": "array","items": 
            { "type": "string" },"uniqueItems": true,"default": []}},"type": ["object", "boolean"],"properties": 
            {"$id": {"type": "string","format": "uri-reference"},"$schema": {"type": "string","format": "uri"},
            "$ref": {"type": "string","format": "uri-reference"},"title": {"type": "string"},
            "description": {"type": "string"},"default": {},"multipleOf": {"type": "number","exclusiveMinimum": 0},
            "maximum": {"type": "number"},"exclusiveMaximum": {"type": "number"},"minimum": {"type": "number"},
            "exclusiveMinimum": {"type": "number"},"maxLength": { "$ref": "#/definitions/nonNegativeInteger" },
            "minLength": { "$ref": "#/definitions/nonNegativeIntegerDefault0" },"pattern": {"type": "string","format": 
            "regex"},"additionalItems": { "$ref": "#" },"items": {"anyOf": [{ "$ref": "#" },{ "$ref": 
            "#/definitions/schemaArray" }],"default": {}},"maxItems": { "$ref": "#/definitions/nonNegativeInteger" },
            "minItems": { "$ref": "#/definitions/nonNegativeIntegerDefault0" },"uniqueItems": {"type": "boolean",
            "default": false},"contains": { "$ref": "#" },"maxProperties": { "$ref": "#/definitions/nonNegativeInteger"
            },"minProperties": { "$ref": "#/definitions/nonNegativeIntegerDefault0" },"required": { "$ref": 
            "#/definitions/stringArray" },"additionalProperties": { "$ref": "#" },"definitions": {"type": "object",
            "additionalProperties": { "$ref": "#" },"default": {}},"properties": {"type": "object",
            "additionalProperties": { "$ref": "#" },"default": {}},"patternProperties": {"type": "object",
            "additionalProperties": { "$ref": "#" },"default": {}},"dependencies": {"type": "object",
            "additionalProperties": {"anyOf": [{ "$ref": "#" },{ "$ref": "#/definitions/stringArray" }]}},
            "propertyNames": { "$ref": "#" },"const": {},"enum": {"type": "array","minItems": 1,"uniqueItems": true},
            "type": {"anyOf": [{ "$ref": "#/definitions/simpleTypes" },{"type": "array","items": { "$ref": 
            "#/definitions/simpleTypes" },"minItems": 1,"uniqueItems": true}]},"format": { "type": "string" },
            "allOf": { "$ref": "#/definitions/schemaArray" },"anyOf": { "$ref": "#/definitions/schemaArray" },
            "oneOf": { "$ref": "#/definitions/schemaArray" },"not": { "$ref": "#" }},"default": {}}', true);
        $this->assertEquals($expected, $resolvedSchema);
    }

    public function testLoadSchemaNested()
    {
        $schemaResolver = new Resolver('http://json-schema.org/draft-06/schema#/definitions/schemaArray',
            'http://json-schema.org/draft-06/schema#/definitions/schemaArray');
        $resolvedSchema = $schemaResolver->resolve();
        $expected = json_decode('{ "type": "array", "minItems": 1, "items": { "$ref": "#" }}', true);
        $this->assertEquals($expected, $resolvedSchema);
    }

    public function testLoadSchemaException()
    {
        $this->expectExceptionMessage('Cannot resolve schema');
        $schemaResolver = new Resolver('//json-schema.org/draft-06/schema#', '//json-schema.org/draft-06/schema#');
        $schemaResolver->resolve();
    }

    public function testLoadSchemaExceptionIncorrectUrl()
    {
        $this->expectExceptionMessage('Cannot load schema from http://foo.bar/');
        $schemaResolver = new Resolver('http://foo.bar/', 'http://foo.bar/');
        $schemaResolver->resolve();
    }
}
