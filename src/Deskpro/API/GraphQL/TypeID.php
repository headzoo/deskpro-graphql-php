<?php
namespace Deskpro\API\GraphQL;

/**
 * Class TypeID
 */
class TypeID extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return 'ID';
    }
}