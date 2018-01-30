<?php
namespace Deskpro\API\GraphQL\Type;

/**
 * Class ID
 */
class ID extends GraphQLType
{
    /**
     * @return string
     */
    public function __toString()
    {
        return 'ID';
    }
}