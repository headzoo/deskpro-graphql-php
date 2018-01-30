<?php
namespace Deskpro\API\GraphQL;

/**
 * Class QueryBuilder
 */
interface QueryBuilderInterface
{
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
     * @return string
     */
    public function getQuery();

    /**
     * @return string
     */
    public function __toString();
}