<?php

/**
 * Class GraphQLQueriesAreEqualConstraint
 */
class GraphQLQueriesAreEqualConstraint extends \PHPUnit_Framework_Constraint
{
    /**
     * @var string
     */
    protected $expected;

    /**
     * @param string $expected
     */
    public function __construct($expected)
    {
        parent::__construct();
        if (!is_string($expected)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                1,
                'string'
            );
        }
        $this->expected = $expected;
    }

    /**
     * @param mixed $other
     * @return bool
     */
    public function matches($other)
    {
        if (!is_string($other)) {
            return false;
        }
        
        return $this->stripWhitespace($this->expected) === $this->stripWhitespace($other);
    }
    
    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'equals the expected GraphQL query';
    }

    /**
     * @param string $str
     * @return string
     */
    private function stripWhitespace($str)
    {
        return preg_replace('/[\s\t\n\r]/', '', $str);
    }
}