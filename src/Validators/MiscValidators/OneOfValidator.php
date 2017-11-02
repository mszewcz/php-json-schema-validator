<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\Json\SchemaValidator\Validators\MiscValidators;


use MS\Json\SchemaValidator\Schema\Resolver;
use MS\Json\SchemaValidator\Validators\NodeValidator;
use MS\Json\SchemaValidator\Validators\ValidatorInterface;

class OneOfValidator implements ValidatorInterface
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
     * OneOfValidator constructor.
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
     * Validates subject against oneOf
     *
     * @param $subject
     * @return bool
     */
    public function validate($subject): bool
    {
        $validatedCount = 0;
        foreach ($this->schema['oneOf'] as $schema) {
            $nodeValidator = new NodeValidator($schema, $this->rootSchema);
            if ($nodeValidator->validate($subject)) {
                $validatedCount++;
            }
        }
        return $validatedCount === 1;
    }
}
