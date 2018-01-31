<?php

use Deskpro\API\GraphQL\Directive;
use PHPUnit\Framework\TestCase;

/**
 * Class DirectiveTest
 */
class DirectiveTest extends TestCase
{
    public function testType()
    {
        $fixture = new Directive('@include', 'if: $withTest');
        $this->assertSame($fixture, $fixture->setType('@skip'));
        $this->assertEquals('@skip', $fixture->getType());
    }

    public function testCondition()
    {
        $fixture = new Directive('@include', 'if: $withTest');
        $this->assertSame($fixture, $fixture->setCondition('if: $notWithTest'));
        $this->assertEquals('if: $notWithTest', $fixture->getCondition());
    }

    public function testFields()
    {
        $fixture = new Directive('@include', 'if: $withTest', [
            'id',
            'content'
        ]);
        $this->assertSame($fixture, $fixture->setFields(['field1']));
        $this->assertEquals(['field1'], $fixture->getFields());
    }
}