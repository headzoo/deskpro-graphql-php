<?php
namespace Deskpro\API\GraphQL;

/**
 * Class TypeInt
 */
class TypeInt extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return 'Int';
    }
}