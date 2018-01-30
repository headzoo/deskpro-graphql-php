<?php
namespace Deskpro\API\GraphQL\Type;

/**
 * Class Boolean
 */
class Boolean extends GraphQLType
{
    /**
     * @return string
     */
    public function __toString()
    {
        return 'Boolean';
    }
}