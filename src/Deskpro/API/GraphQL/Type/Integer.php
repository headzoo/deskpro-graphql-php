<?php
namespace Deskpro\API\GraphQL\Type;

/**
 * Class Integer
 */
class Integer extends GraphQLType
{
    /**
     * @return string
     */
    public function __toString()
    {
        return 'Int';
    }
}