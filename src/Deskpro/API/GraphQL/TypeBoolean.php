<?php
namespace Deskpro\API\GraphQL;

/**
 * Class TypeBoolean
 */
class TypeBoolean extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return 'Boolean';
    }
}