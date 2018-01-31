<?php

use Deskpro\API\GraphQL\Type;
use PHPUnit\Framework\TestCase;

/**
 * Class TypeTest
 */
class TypeTest extends TestCase
{
    /**
     * @dataProvider providesTest
     *
     * @param string $actual
     * @param string $expected
     */
    public function testID($actual, $expected)
    {
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function providesTest()
    {
        return [
            [Type::id(), 'ID'],
            [Type::id(false), 'ID!'],
            [Type::listOf(Type::id()), '[ID]'],
            [Type::listOf(Type::id(), false), '[ID]!'],

            [Type::int(), 'Int'],
            [Type::int(false), 'Int!'],
            [Type::listOf(Type::int()), '[Int]'],
            [Type::listOf(Type::int(), false), '[Int]!'],

            [Type::float(), 'Float'],
            [Type::float(false), 'Float!'],
            [Type::listOf(Type::float()), '[Float]'],
            [Type::listOf(Type::float(), false), '[Float]!'],

            [Type::string(), 'String'],
            [Type::string(false), 'String!'],
            [Type::listOf(Type::string()), '[String]'],
            [Type::listOf(Type::string(), false), '[String]!'],

            [Type::boolean(), 'Boolean'],
            [Type::boolean(false), 'Boolean!'],
            [Type::listOf(Type::boolean()), '[Boolean]'],
            [Type::listOf(Type::boolean(), false), '[Boolean]!'],
        ];
    }
}