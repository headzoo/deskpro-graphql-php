<?php
namespace Deskpro\API\GraphQL;

use GuzzleHttp\ClientInterface as HTTPClientInterface;

/**
 * Class GraphQLClient
 */
interface ClientInterface
{
    /**
     * @param string $operationName
     * @param array|string $args
     * @return QueryBuilderInterface
     */
    public function createQuery($operationName, $args = []);

    /**
     * @param string $operationName
     * @param array|string $args
     * @return MutationBuilderInterface
     */
    public function createMutation($operationName, $args = []);

    /**
     * @param QueryBuilderInterface|string $query
     * @param array $variables
     *
     * @return array
     *
     * @throws Exception\InvalidResponseException
     * @throws Exception\QueryErrorException
     */
    public function execute($query, array $variables = []);
    
    /**
     * Sets the person ID and authentication token
     *
     * @param int $personId The ID of the person being authenticated
     * @param string $token The authentication token
     *
     * @return $this
     */
    public function setAuthToken($personId, $token);

    /**
     * Sets the person ID and authentication key
     *
     * @param int $personId The ID of the person being authenticated
     * @param string $key The authentication key
     *
     * @return $this
     */
    public function setAuthKey($personId, $key);

    /**
     * Returns the base URL to the Deskpro instance
     *
     * @return string
     */
    public function getBaseUrl();

    /**
     * Sets the base URL to the Deskpro instance
     *
     * @param string $baseUrl The base URL to the Deskpro instance
     *
     * @return $this
     */
    public function setBaseUrl($baseUrl);

    /**
     * Returns the path, appended to baseUrl, for the GraphQL endpoint
     *
     * @return string
     */
    public function getGraphqlPath();

    /**
     * Sets the path, appended to baseUrl, for the GraphQL endpoint
     *
     * @param string $graphqlPath
     *
     * @return $this
     */
    public function setGraphqlPath($graphqlPath);

    /**
     * Returns the HTTP client used to make requests
     *
     * @return ClientInterface
     */
    public function getHTTPClient();

    /**
     * Sets the HTTP client used to make requests
     *
     * @param HTTPClientInterface $httpClient HTTP client used to make requests
     *
     * @return $this
     */
    public function setHTTPClient(HTTPClientInterface $httpClient);

    /**
     * Returns the headers sent with each request
     *
     * @return array
     */
    public function getDefaultHeaders();

    /**
     * Sets the headers sent with each request
     *
     * @param array $defaultHeaders The headers to send
     *
     * @return $this
     */
    public function setDefaultHeaders(array $defaultHeaders);
}