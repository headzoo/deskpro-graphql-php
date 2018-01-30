<?php
namespace Deskpro\API\GraphQL;

/**
 * Class QueryBuilder
 */
class QueryBuilder extends AbstractBuilder implements QueryBuilderInterface
{
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
    protected function getOperationType()
    {
        return 'query';
    }
}