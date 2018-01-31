<?php
namespace Deskpro\API\GraphQL;

/**
 * Class TypeFloat
 */
class TypeFloat extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return 'Float';
    }
}