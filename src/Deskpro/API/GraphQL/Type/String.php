<?php
namespace Deskpro\API\GraphQL\Type;

/**
 * Class String
 */
class String extends GraphQLType
{
    /**
     * @return string
     */
    public function __toString()
    {
        return 'String';
    }
}