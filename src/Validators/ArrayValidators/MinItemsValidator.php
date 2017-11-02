<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\Json\SchemaValidator\Validators\ArrayValidators;


use MS\Json\SchemaValidator\Schema\Resolver;
use MS\Json\SchemaValidator\Validators\ValidatorInterface;

class MinItemsValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private $schema;
    /**
     * @var array
     */
    private $rootSchema;

    /**
     * MinItemsValidator constructor.
     *
     * @param array $schema
     * @param array $rootSchema
     * @throws \Exception
     */
    public function __construct(array $schema, array $rootSchema)
    {
        $this->schema = (new Resolver($schema, $rootSchema))->resolve();
        $this->rootSchema = $rootSchema;
    }

    /**
     * Validates subject against minItems
     *
     * @param $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        if (\is_integer($this->schema['minItems']) && ($this->schema['minItems'] >= 0)) {
            if (\count($subject) < $this->schema['minItems']) {
                return false;
            }
        }
        return true;
    }
}

