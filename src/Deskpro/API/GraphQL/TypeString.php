<?php
namespace Deskpro\API\GraphQL;

/**
 * Class TypeString
 */
class TypeString extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return 'String';
    }
}