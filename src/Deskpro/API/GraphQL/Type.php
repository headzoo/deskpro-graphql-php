<?php
namespace Deskpro\API\GraphQL;

/**
 * Class Type
 */
abstract class Type
{
    /**
     * @var bool
     */
    protected $nullable;

    /**
     * @param bool $nullable
     *
     * @return TypeID
     */
    public static function id($nullable = true)
    {
        return new TypeID($nullable);
    }

    /**
     * @param bool $nullable
     *
     * @return TypeInt
     */
    public static function int($nullable = true)
    {
        return new TypeInt($nullable);
    }

    /**
     * @param bool $nullable
     *
     * @return TypeFloat
     */
    public static function float($nullable = true)
    {
        return new TypeFloat($nullable);
    }

    /**
     * @param bool $nullable
     *
     * @return TypeString
     */
    public static function string($nullable = true)
    {
        return new TypeString($nullable);
    }

    /**
     * @param bool $nullable
     *
     * @return TypeBoolean
     */
    public static function boolean($nullable = true)
    {
        return new TypeBoolean($nullable);
    }

    /**
     * @param string $className
     * @param bool $nullable
     *
     * @return TypeObject
     */
    public static function object($className, $nullable = true)
    {
        return new TypeObject($className, $nullable);
    }

    /**
     * @param Type $type
     * @param bool $nullable
     *
     * @return string
     */
    public static function listOf($type, $nullable = true)
    {
        return new TypeListOf($type, $nullable);
    }

    /**
     * Constructor
     *
     * @param bool $nullable
     */
    public function __construct($nullable = false)
    {
        $this->nullable = $nullable;
    }

    /**
     * @return string
     */
    public abstract function getValue();

    /**
     * @return bool
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @param bool $nullable
     *
     * @return $this
     */
    public function setNullable($nullable)
    {
        $this->nullable = $nullable;
        
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->nullable($this);
    }

    /**
     * @param Type $type
     *
     * @return string
     */
    protected function nullable(Type $type)
    {
        return $type->isNullable() ? $type->getValue() : $type->getValue() . '!';
    }
}