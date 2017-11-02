<?php
/**
 * JSON schema validator
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\Json\SchemaValidator\Schema;


use MS\Json\Utils\Utils;

class Resolver
{
    private $schema;
    private $rootSchema;
    private $utils;

    /**
     * Resolver constructor.
     *
     * @param $schema
     * @param $rootSchema
     */
    public function __construct($schema, $rootSchema)
    {
        $this->schema = $schema;
        $this->rootSchema = $rootSchema;
        $this->utils = new Utils();
    }

    /**
     * Resolves schema
     *
     * @return array
     * @throws \Exception
     */
    public function resolve(): array
    {
        if (\is_array($this->schema)) {
            if (isset($this->schema['$ref'])) {
                $ref = $this->schema['$ref'];

                if ($ref === '#') {
                    return $this->rootSchema;
                }
                if (\preg_match('/^#\/[a-z0-9_-]+(\/[a-z0-9_-]+)?$/i', $ref)) {
                    $schema = $this->rootSchema;
                    $path = \explode('/', substr($ref, 2));

                    foreach ($path as $item) {
                        if (isset($schema[$item])) {
                            $schema = $schema[$item];
                        }
                    }
                    return $schema;
                }
                throw new \Exception('Cannot resolve schema');
            }
            return $this->schema;
        }
        if (\filter_var(
            $this->schema,
            \FILTER_VALIDATE_URL, \FILTER_FLAG_SCHEME_REQUIRED | \FILTER_FLAG_HOST_REQUIRED
        )) {
            return $this->loadSchema($this->schema);
        }
        throw new \Exception('Cannot resolve schema');
    }

    /**
     * Loads schema
     *
     * @param string $schemaUrl
     * @return array
     * @throws \Exception
     */
    private function loadSchema(string $schemaUrl): array
    {
        $options = [
            'http' => ['method' => 'GET'],
        ];
        $context = \stream_context_create($options);
        $schema = @\file_get_contents($schemaUrl, false, $context);

        if ($schema === false) {
            $message = \sprintf('Cannot load schema from %s', $schemaUrl);
            throw new \Exception($message);
        }
        $schema = $this->utils->decode($schema);

        // resolve nested schema reference if needed
        if (stripos($schemaUrl, '#') !== false) {
            $schemaUrlParts = \explode('#', $schemaUrl);
            if (isset($schemaUrlParts[1]) && $schemaUrlParts[1] !== '') {
                $schemaRef = ['$ref' => \sprintf('#%s', $schemaUrlParts[1])];
                $resolver = new Resolver($schemaRef, $schema);
                $schema = $resolver->resolve();
            }
        }
        return $schema;
    }
}
