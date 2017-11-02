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

class AdditionalPropertiesValidator implements ValidatorInterface
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
     * AdditionalPropertiesValidator constructor.
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
     * Validates subject against additionalProperties
     *
     * @param array $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        // Validation with "additionalProperties" applies only to the child values of instance names that do
        // not match any names in "properties", and do not match any regular expression in "patternProperties".
        $subject = $this->removePropertiesFromSubject($subject, $this->schema);
        $subject = $this->removePatternPropertiesFromSubject($subject, $this->schema);
        // The value of "additionalProperties" MUST be a valid JSON Schema (including boolean false)
        if (\is_bool($this->schema['additionalProperties']) && $this->schema['additionalProperties'] === false) {
            if (count($subject) > 0) {
                return false;
            }
        } elseif (\is_array($this->schema['additionalProperties'])) {
            foreach ($subject as $propertyValue) {
                $nodeValidator = new NodeValidator($this->schema['additionalProperties'], $this->rootSchema);
                if (!$nodeValidator->validate($propertyValue)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Removes properties defined in 'properties' from subject
     *
     * @param array $subject
     * @param array $schema
     * @return array
     */
    private function removePropertiesFromSubject(array $subject, array $schema): array
    {
        if (isset($schema['properties']) && \is_array($schema['properties'])) {
            $propertyNames = \array_keys($schema['properties']);
            foreach ($propertyNames as $propertyName) {
                if (isset($subject[$propertyName])) {
                    unset($subject[$propertyName]);
                }
            }
        }
        return $subject;
    }

    /**
     * Removes properties defined in 'patternProperties' from subject
     *
     * @param array $subject
     * @param array $schema
     * @return array
     */
    private function removePatternPropertiesFromSubject(array $subject, array $schema): array
    {
        if (isset($schema['patternProperties']) && \is_array($schema['patternProperties'])) {
            $patterns = \array_keys($schema['patternProperties']);
            $propertyNames = \array_keys($subject);

            foreach ($patterns as $pattern) {
                foreach ($propertyNames as $propertyName) {
                    if (\preg_match(sprintf('/%s/', $pattern), $propertyName)) {
                        unset($subject[$propertyName]);
                    }
                }
            }
        }
        return $subject;
    }
}
