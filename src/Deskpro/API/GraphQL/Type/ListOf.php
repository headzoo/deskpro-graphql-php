<?php
namespace Deskpro\API\GraphQL\Type;

/**
 * Class ListOf
 */
class ListOf extends GraphQLType
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
        $type = sprintf('[%s]', $type);

        return $type;
    }
}