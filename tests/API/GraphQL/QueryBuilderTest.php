<?php
require_once('GraphQLTestCase.php');

use Deskpro\API\GraphQL\ClientInterface;
use Deskpro\API\GraphQL\Directive;
use Deskpro\API\GraphQL\Fragment;
use Deskpro\API\GraphQL\QueryBuilder;

/**
 * Class QueryBuilderTest
 */
class QueryBuilderTest extends GraphQLTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    public $clientMock;
    
    /**
     * Run before each test
     */
    public function setUp()
    {
        $this->clientMock = $this->getMockBuilder(ClientInterface::class)->getMock();
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testOperationArgsAsString()
    {
        $expected = '
            query GetNews ($id: ID!) {

            }
        ';

        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testOperationArgsAsArray()
    {
        $expected = '
            query GetNews ($id: ID!) {
            
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', [
            '$id: ID!'
        ]);
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testOperationArgsAsAssocArray()
    {
        $expected = '
            query GetNews ($id: ID!) {
            
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', [
            '$id' => 'ID!'
        ]);
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testOperationArgsWithoutDollarSign()
    {
        $expected = '
            query GetNews ($id: ID!) {

            }
        ';

        $fixture = new QueryBuilder($this->clientMock, 'GetNews', 'id: ID!');
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testFieldArgsAsString()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId)
            }
        ';

        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', 'id: $newsId');
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testFieldArgsAsArray()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId)
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', ['id: $newsId']);
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testFieldArgsAsAssocArray()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId)
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', ['id' => '$newsId']);
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testFieldsAsString()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId) {
                    title
                    content
                }
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', 'id: $newsId', 'title, content');
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testFieldsAsArray()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId) {
                    title
                    content
                }
            }
        ';

        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', 'id: $newsId', [
            'title',
            'content'
        ]);
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testGetQuerySingleField()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId) {
                    title
                    content
                }
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', 'id: $newsId', [
            'title',
            'content'
        ]);
        
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testGetQueryMultipleFields()
    {
        $expected = '
            query GetNews ($newsId: ID!, $articleId: ID!) {
                content_get_news(id: $newsId) {
                    title
                    content
                }
            
                content_get_articles(id: $articleId) {
                    title
                    content {
                        id
                    }
                }
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', [
            '$newsId'    => 'ID!',
            '$articleId' => 'ID!',
        ]);
        $fixture
            ->field('content_get_news', 'id: $newsId', [
                'title',
                'content'
            ])->field('content_get_articles', 'id: $articleId', [
                'title',
                'content' => [
                    'id'
                ]
            ]);
        
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testFieldAliasAsString()
    {
        $expected = '
            query GetNews ($id: ID!) {
                news1: content_get_news(id: $newsId)
            }
        ';

        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('news1: content_get_news', 'id: $newsId');
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testFieldAliasAsArray()
    {
        $expected = '
            query GetNews ($id: ID!) {
                news1: content_get_news(id: $newsId)
            }
        ';

        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field(['news1', 'content_get_news'], 'id: $newsId');
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testExecute()
    {
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $this->clientMock->method('execute')
            ->with($this->equalTo($fixture))
            ->willReturn([]);
        $fixture->execute(['id' => 1]);
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testFragment()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId) {
                    ...test_fragment
                }
            }
            
            fragment test_fragment on Testing {
                    title
                    content
            }
        ';

        $fragment = new Fragment('test_fragment', 'Testing', [
            'title',
            'content'
        ]);

        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', 'id: $newsId', $fragment);

        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testDirective()
    {
        $expected = '
            query GetNews ($id: ID!, $withCategories: Boolean!) {
                content_get_articles(id: $id) {
                    title
                    categories @include(if: $withCategories) {
                        id
                        content
                    }
                }
            }
        ';

        $fixture = new QueryBuilder(
                $this->clientMock,
                'GetNews',
                '$id: ID!, $withCategories: Boolean!'
        );
        $fixture->field('content_get_articles', 'id: $id', [
            'title',
            'categories' => new Directive('@include', 'if: $withCategories', [
                'id',
                'content'
            ])
        ]);

        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }

    /**
     * @throws \Deskpro\API\GraphQL\Exception\QueryBuilderException
     */
    public function testDirectiveShortcut()
    {
        $expected = '
            query GetNews ($id: ID!, $withCategories: Boolean!) {
                content_get_articles(id: $id) {
                    title
                    categories @include(if: $withCategories) {
                        id
                        content
                    }
                }
            }
        ';

        $fixture = new QueryBuilder(
            $this->clientMock,
            'GetNews',
            '$id: ID!, $withCategories: Boolean!'
        );
        $fixture->field('content_get_articles', 'id: $id', [
            'title',
            'categories' => $fixture->includeIf('$withCategories', [
                'id',
                'content'
            ])
        ]);

        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
}