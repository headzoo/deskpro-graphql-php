<?php

use Deskpro\API\GraphQL\Fragment;
use PHPUnit\Framework\TestCase;

/**
 * Class FragmentTest
 */
class FragmentTest extends TestCase
{
    public function testName()
    {
        $fixture = new Fragment('test_fragment', 'Test');
        $this->assertSame($fixture, $fixture->setName('testing'));
        $this->assertEquals('testing', $fixture->getName());
    }
    
    public function testOnType()
    {
        $fixture = new Fragment('test_fragment', 'Test');
        $this->assertSame($fixture, $fixture->setOnType('Testing'));
        $this->assertEquals('Testing', $fixture->getOnType());
    }
    
    public function testFields()
    {
        $fixture = new Fragment('test_fragment', 'Test', 'field1 field2');
        $this->assertSame($fixture, $fixture->setFields(['field1']));
        $this->assertEquals(['field1'], $fixture->getFields());
    }
}