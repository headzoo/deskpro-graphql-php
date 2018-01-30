<?php
namespace Deskpro\API\GraphQL\Type;

/**
 * Class Float
 */
class Float extends GraphQLType
{
    /**
     * @return string
     */
    public function __toString()
    {
        return 'Float';
    }
}