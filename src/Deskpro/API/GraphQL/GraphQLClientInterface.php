<?php
namespace Deskpro\API\GraphQL;

use GuzzleHttp\ClientInterface;

/**
 * Class GraphQLClient
 */
interface GraphQLClientInterface
{
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
    public function getHelpdeskUrl();

    /**
     * Sets the base URL to the Deskpro instance
     *
     * @param string $helpdeskUrl The base URL to the Deskpro instance
     *
     * @return $this
     */
    public function setHelpdeskUrl($helpdeskUrl);

    /**
     * Returns the HTTP client used to make requests
     *
     * @return ClientInterface
     */
    public function getHTTPClient();

    /**
     * Sets the HTTP client used to make requests
     *
     * @param ClientInterface $httpClient HTTP client used to make requests
     *
     * @return $this
     */
    public function setHTTPClient(ClientInterface $httpClient);

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

    /**
     * @param string $operationName
     * @param array $args
     * @return QueryBuilder
     */
    public function createQuery($operationName, array $args = []);

    /**
     * @param QueryBuilder|string $query
     * @param array $variables
     *
     * @return array
     *
     * @throws Exception\InvalidResponseException
     * @throws Exception\QueryErrorException
     */
    public function execute($query, array $variables = []);
}