<?php
namespace Deskpro\API\GraphQL;

/**
 * Class Type
 */
class Type
{
    const ID = 'ID';
    const INT = 'Int';
    const FLOAT = 'Float';
    const STRING = 'String';
    const BOOLEAN = 'Boolean';
    
    /**
     * @param bool $nullable
     *
     * @return string
     */
    public static function id($nullable = true)
    {
        return self::nullable(self::ID, $nullable);
    }

    /**
     * @param bool $nullable
     *
     * @return string
     */
    public static function int($nullable = true)
    {
        return self::nullable(self::INT, $nullable);
    }

    /**
     * @param bool $nullable
     *
     * @return string
     */
    public static function float($nullable = true)
    {
        return self::nullable(self::FLOAT, $nullable);
    }

    /**
     * @param bool $nullable
     *
     * @return string
     */
    public static function string($nullable = true)
    {
        return self::nullable(self::STRING, $nullable);
    }

    /**
     * @param bool $nullable
     *
     * @return string
     */
    public static function boolean($nullable = true)
    {
        return self::nullable(self::BOOLEAN, $nullable);
    }

    /**
     * @param string $type
     * @param bool $nullable
     *
     * @return string
     */
    public static function listOf($type, $nullable = true)
    {
        return self::nullable('[' . $type . ']', $nullable);
    }

    /**
     * @param string $type
     * @param bool $nullable
     *
     * @return string
     */
    protected static function nullable($type, $nullable)
    {
        return $type . (!$nullable ? '!' : '');
    }
}