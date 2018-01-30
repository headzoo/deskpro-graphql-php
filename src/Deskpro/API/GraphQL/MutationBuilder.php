<?php
namespace Deskpro\API\GraphQL;

/**
 * Class MutationBuilder
 */
class MutationBuilder extends AbstractBuilder implements MutationBuilderInterface
{
    /**
     * Describes the type of operation performed by this builder
     */
    const OPERATION_TYPE = 'mutation';
    
    /**
     * @return string
     */
    public function getMutation()
    {
        return $this->build();
    }

    /**
     * @return string
     */
    public function getOperationType()
    {
        return self::OPERATION_TYPE;
    }
}