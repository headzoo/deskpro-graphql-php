<?php
require_once('GraphQLQueriesAreEqualConstraint.php');

use PHPUnit\Framework\TestCase;

/**
 * Class TestCase
 */
class GraphQLTestCase extends TestCase
{
    /**
     * @param string $expected
     * @param string $actual
     * @param string $message
     */
    public static function assertGraphQLQueriesAreEqual($expected, $actual, $message = '')
    {
        self::assertThat($actual, new GraphQLQueriesAreEqualConstraint($expected), $message);
    }
}