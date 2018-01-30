<?php
namespace Deskpro\API\GraphQL;

/**
 * Class QueryBuilder
 */
class QueryBuilder extends AbstractBuilder implements QueryBuilderInterface
{
    /**
     * Describes the type of operation performed by this builder
     */
    const OPERATION_TYPE = 'query';
    
    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        return $this->build();
    }

    /**
     * {@inheritdoc}
     */
    public function getOperationType()
    {
        return self::OPERATION_TYPE;
    }
}