<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\Json\SchemaValidator;


use MS\Json\SchemaValidator\Schema\Resolver;
use MS\Json\SchemaValidator\Validators\NodeValidator;

class Validator
{
    /**
     * @var array
     */
    private $rootSchema;

    /**
     * Validator constructor.
     *
     * @param $schema
     * @throws \Exception
     */
    public function __construct($schema)
    {
        $this->rootSchema = (new Resolver($schema, $schema))->resolve();
    }

    /**
     * Validates subject
     *
     * @param $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        $nodeValidator = new NodeValidator($this->rootSchema, $this->rootSchema);
        return $nodeValidator->validate($subject);
    }
}
