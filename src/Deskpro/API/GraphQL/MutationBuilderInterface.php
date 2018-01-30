<?php
namespace Deskpro\API\GraphQL;

/**
 * Interface MutationBuilderInterface
 */
interface MutationBuilderInterface extends BuilderInterface
{
    /**
     * @return string
     */
    public function getMutation();
}