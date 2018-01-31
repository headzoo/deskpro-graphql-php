<?php
namespace Deskpro\API\GraphQL;

/**
 * Interface BuilderInterface
 */
interface BuilderInterface
{
    /**
     * Returns a string describing the type of operation performed by the builder
     * 
     * @return string
     */
    public function getOperationType();
    
    /**
     * @param string $name
     * @param array $args
     * @param array $fields
     *
     * @return $this
     */
    public function field($name, $args = [], $fields = []);

    /**
     * @param array $args
     * @return array
     */
    public function execute(array $args = []);

    /**
     * @param string $condition
     * @param array $fields
     *
     * @return Directive
     */
    public function includeIf($condition, $fields = []);

    /**
     * @param string $condition
     * @param array $fields
     *
     * @return Directive
     */
    public function skipIf($condition, $fields = []);
    
    /**
     * @return string
     */
    public function getOperationName();

    /**
     * @param string $operationName
     *
     * @return $this
     */
    public function setOperationName($operationName);

    /**
     * @return array
     */
    public function getOperationArgs();

    /**
     * @param array|string $operationArgs
     *
     * @return $this
     */
    public function setOperationArgs($operationArgs);

    /**
     * @return string
     */
    public function __toString();
}