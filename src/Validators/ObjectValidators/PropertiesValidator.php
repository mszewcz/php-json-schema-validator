<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\Json\SchemaValidator\Validators\ObjectValidators;


use MS\Json\SchemaValidator\Schema\Resolver;
use MS\Json\SchemaValidator\Validators\NodeValidator;
use MS\Json\SchemaValidator\Validators\ValidatorInterface;

class PropertiesValidator implements ValidatorInterface
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
     * PropertiesValidator constructor.
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
     * Validates subject against properties
     *
     * @param array $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        if (\is_array($this->schema['properties'])) {
            foreach ($this->schema['properties'] as $propertyName => $propertySchema) {
                if (isset($subject[$propertyName])) {
                    $nodeValidator = new NodeValidator($propertySchema, $this->rootSchema);
                    if (!$nodeValidator->validate($subject[$propertyName])) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
}
