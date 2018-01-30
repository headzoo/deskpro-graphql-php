<?php
namespace Deskpro\API\GraphQL;

/**
 * Class MutationBuilder
 */
class MutationBuilder extends AbstractBuilder implements MutationBuilderInterface
{
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
    protected function getTypeName()
    {
        return 'mutation';
    }
}