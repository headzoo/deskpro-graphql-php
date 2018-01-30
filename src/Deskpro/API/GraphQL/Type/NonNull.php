<?php
namespace Deskpro\API\GraphQL\Type;

/**
 * Class NonNull
 */
class NonNull extends GraphQLType
{
    /**
     * @var GraphQLType
     */
    protected $wrapped;

    /**
     * Constructor
     * 
     * @param GraphQLType $wrapped
     */
    public function __construct(GraphQLType $wrapped)
    {
        $this->wrapped = $wrapped;
    }

    /**
     * @return GraphQLType
     */
    public function getType()
    {
        return $this->wrapped;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $type = (string)$this->wrapped;
        $type .= '!';
        
        return $type;
    }
}