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

class DependenciesValidator implements ValidatorInterface
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
     * DependenciesValidator constructor.
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
     * Validates subject against dependencies
     *
     * @param array $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        // This keyword's value MUST be an object. Each property specifies a dependency. Each dependency value MUST
        // be an array or a valid JSON Schema.
        if (\is_array($this->schema['dependencies']) && count($this->schema['dependencies']) > 0) {
            foreach ($this->schema['dependencies'] as $propertyName => $dependentProperties) {
                // If the dependency value is an array, each element in the array, if any, MUST be a string, and MUST be
                // unique. If the dependency key is a property in the instance, each of the items in the dependency value
                // must be a property that exists in the instance.
                if (\is_int(\array_keys($dependentProperties)[0])) {
                    if (!$this->validateClassicDependencies($subject, $propertyName, $dependentProperties)) {
                        return false;
                    }
                    // If the dependency value is a subschema, and the dependency key is a property in the instance, the entire
                    // instance must validate against the dependency value.
                } elseif (\is_string(\array_keys($dependentProperties)[0])) {
                    if (!$this->validateSchemaDependencies($subject, $propertyName, $dependentProperties)) {
                        return false;
                    }
                }

            }
        }
        return true;
    }

    /**
     * Checks classic dependencies
     *
     * @param array  $subject
     * @param string $propertyName
     * @param array  $dependentProperties
     * @return bool
     */
    private function validateClassicDependencies(array $subject, string $propertyName, array $dependentProperties): bool
    {
        if (\is_string($propertyName) && \is_array($dependentProperties)) {
            if (\array_key_exists($propertyName, $subject)) {
                foreach ($dependentProperties as $propertyName) {
                    if (\is_string($propertyName)
                        && !\array_key_exists($propertyName, $subject)
                    ) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Checks schema dependencies
     *
     * @param array  $subject
     * @param string $propertyName
     * @param array  $dependencySchema
     * @return bool
     */
    private function validateSchemaDependencies(array $subject, string $propertyName, array $dependencySchema): bool
    {
        if (\is_string($propertyName) && \is_array($dependencySchema)) {
            if (\array_key_exists($propertyName, $subject)) {
                // set 'type': 'object' in dependency schema to make sure it will be validated with ObjectValidator
                $dependencySchema['type'] = 'object';
                $nodeValidator = new NodeValidator($dependencySchema, $this->rootSchema);
                if (!$nodeValidator->validate($subject)) {
                    return false;
                }
            }
        }
        return true;
    }
}
