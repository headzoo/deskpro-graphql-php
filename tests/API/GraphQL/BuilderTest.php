<?php
require_once('GraphQLTestCase.php');

use Deskpro\API\GraphQL\ClientInterface;
use Deskpro\API\GraphQL\AbstractBuilder;

/**
 * Class BuilderTest
 */
class BuilderTest extends GraphQLTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    public $clientMock;
    
    /**
     * @var BuilderTestFixture
     */
    public $fixture;

    /**
     * Run before each test
     */
    public function setUp()
    {
        $this->clientMock = $this->getMockBuilder(ClientInterface::class)->getMock();
        $this->fixture = new BuilderTestFixture($this->clientMock, 'GetNews', '$id: ID!');
    }
    
    public function testGetOperationType()
    {
        $this->assertEquals('query', $this->fixture->getOperationType());
    }

    public function testGetOperationName()
    {
        $this->assertEquals('GetNews', $this->fixture->getOperationName());
    }

    public function testGetOperationArgs()
    {
        $this->assertEquals('$id: ID!', $this->fixture->getOperationArgs());
    }
    
    public function testToString()
    {
        $expected = '
            query GetNews ($id: ID!) {
            
            }
        ';

        $this->assertGraphQLQueriesAreEqual($expected, (string)$this->fixture);
        
        // cache check
        $this->assertGraphQLQueriesAreEqual($expected, (string)$this->fixture);
    }

    /**
     * @expectedException \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testOperationNameThrowsInvalid()
    {
        $this->fixture->setOperationName('invalid name');
    }

    /**
     * @expectedException \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testFieldThrowsInvalidName()
    {
        $this->fixture->field('invalid name');
    }

    /**
     * @expectedException \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testFieldThrowsInvalidAlias()
    {
        $this->fixture->field('*bad: content_get_news');
    }
}

/**
 * Class BuilderTestFixture
 */
class BuilderTestFixture extends AbstractBuilder
{
    /**
     * @return string
     */
    public function getOperationType()
    {
        return 'query';
    }
}