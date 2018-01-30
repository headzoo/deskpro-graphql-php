<?php
namespace Deskpro\API\GraphQL\Type;

/**
 * Class GraphQLType
 */
abstract class GraphQLType
{
    /**
     * @return string
     */
    public abstract function __toString();
    
    /**
     * @param GraphQLType $wrapped
     * @return NonNull
     */
    public static function nonNull(GraphQLType $wrapped)
    {
        return new NonNull($wrapped);
    }

    /**
     * @param GraphQLType $wrapped
     * @return ListOf
     */
    public static function listOf(GraphQLType $wrapped)
    {
        return new ListOf($wrapped);
    }
    
    /**
     * @return ID
     */
    public static function id()
    {
        return new ID();
    }
    
    /**
     * @return Integer
     */
    public static function int()
    {
        return new Integer();
    }
}