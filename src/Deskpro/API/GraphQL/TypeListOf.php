<?php
namespace Deskpro\API\GraphQL;

/**
 * Class TypeListOf
 */
class TypeListOf extends Type
{
    /**
     * @var Type
     */
    protected $type;

    /**
     * Constructor
     *
     * @param Type $type
     * @param bool $nullable
     */
    public function __construct(Type $type, $nullable = false)
    {
        parent::__construct($nullable);
        $this->type = $type;
    }

    /**
     * @return Type
     */
    public function getWrappedType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        $value = $this->nullable($this->type);
        return '[' . $value . ']';
    }
}