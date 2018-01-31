<?php
namespace Deskpro\API\GraphQL;

/**
 * Class TypeObject
 */
class TypeObject extends Type
{
    /**
     * @var Type
     */
    protected $className;

    /**
     * Constructor
     *
     * @param string $className
     * @param bool $nullable
     */
    public function __construct($className, $nullable = false)
    {
        parent::__construct($nullable);
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->className;
    }
}