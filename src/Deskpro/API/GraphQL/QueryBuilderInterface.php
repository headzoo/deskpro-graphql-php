<?php
namespace Deskpro\API\GraphQL;

/**
 * Class QueryBuilder
 */
interface QueryBuilderInterface extends BuilderInterface
{
    /**
     * @return string
     */
    public function getQuery();
}