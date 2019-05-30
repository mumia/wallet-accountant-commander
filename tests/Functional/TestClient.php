<?php

namespace WalletAccountant\Tests\Functional;

use function json_decode;
use function sprintf;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
use WalletAccountant\Common\Authenticator\JwtAuthenticator;

/**
 * TestClient
 */
class TestClient extends Client
{
    /**
     * @var string|null
     */
    private $authorizationToken = null;

    /**
     * @param string $uri
     * @param array  $options
     *
     * @return Crawler
     */
    public function get(string $uri, array $options = []): Crawler
    {
        return $this->sendRequest(Request::METHOD_GET, $uri, $options);
    }

    /**
     * @param string $uri
     * @param array  $parameters
     * @param array  $options
     *
     * @return Crawler
     */
    public function post(string $uri, array $parameters = [], array $options = []): Crawler
    {
        $options['parameters'] = $parameters;

        return $this->sendRequest(Request::METHOD_POST, $uri, $options);
    }

    /**
     * @param string $uri
     * @param array  $parameters
     * @param array  $options
     *
     * @return Crawler
     */
    public function put(string $uri, array $parameters = [], array $options = []): Crawler
    {
        $options['parameters'] = $parameters;

        return $this->sendRequest(Request::METHOD_PUT, $uri, $options);
    }

    /**
     * @param string $uri
     * @param array  $parameters
     * @param array  $options
     *
     * @return Crawler
     */
    public function patch(string $uri, array $parameters = [], array $options = []): Crawler
    {
        $options['parameters'] = $parameters;

        return $this->sendRequest(Request::METHOD_PATCH, $uri, $options);
    }

    /**
     * @param string $uri
     * @param array  $parameters
     * @param array  $options
     *
     * @return Crawler
     */
    public function delete(string $uri, array $parameters = [], array $options = []): Crawler
    {
        $options['parameters'] = $parameters;

        return $this->sendRequest(Request::METHOD_DELETE, $uri, $options);
    }

    /**
     * @return bool
     */
    public function isNoContent(): bool
    {
        return $this->getResponse()->getStatusCode() === Response::HTTP_NO_CONTENT;
    }

    /**
     * @return bool
     */
    public function isContentTypeJson(): bool
    {
        return $this->getResponse()->headers->contains('Content-Type', 'application/json');
    }

    /**
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->getResponse()->getStatusCode() === Response::HTTP_OK;
    }

    /**
     * @return bool
     */
    public function isNotFound(): bool
    {
        return $this->getResponse()->getStatusCode() === Response::HTTP_NOT_FOUND;
    }

    /**
     * @return bool
     */
    public function isBadRequest(): bool
    {
        return $this->getResponse()->getStatusCode() === Response::HTTP_BAD_REQUEST;
    }

    /**
     * @return bool
     */
    public function isForbidden(): bool
    {
        return $this->getResponse()->getStatusCode() === Response::HTTP_FORBIDDEN;
    }

    /**
     * @return bool
     */
    public function isCreated(): bool
    {
        return $this->getResponse()->getStatusCode() === Response::HTTP_CREATED;
    }

    /**
     * @return bool
     */
    public function isUnauthorized(): bool
    {
        return $this->getResponse()->getStatusCode() === Response::HTTP_UNAUTHORIZED;
    }

    /**
     * @return bool
     */
    public function isPaymentRequired(): bool
    {
        return $this->getResponse()->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED;
    }

    /**
     * @return bool
     */
    public function isOkAndJson(): bool
    {
        return $this->isOk() && $this->isContentTypeJson();
    }

    /**
     * @return bool
     */
    public function isNotFoundAndJson(): bool
    {
        return $this->isNotFound() && $this->isContentTypeJson();
    }

    /**
     * @return bool
     */
    public function isBadRequestAndJson(): bool
    {
        return $this->isBadRequest() && $this->isContentTypeJson();
    }

    /**
     * @return bool
     */
    public function isForbiddenAndJson(): bool
    {
        return $this->isForbidden() && $this->isContentTypeJson();
    }

    /**
     * @return bool
     */
    public function isCreatedAndJson(): bool
    {
        return $this->isCreated() && $this->isContentTypeJson();
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->getResponse()->getContent();
    }

    /**
     * @param Response $response
     */
    public function setAuthorizationTokenFromResponse(Response $response): void
    {
        $token = $response->getContent();
        $token = json_decode($token, true)['token'];

        $this->setAuthorizationToken($token);
    }

    /**
     * @param string $token
     */
    public function setAuthorizationToken(string $token): void
    {
        $this->authorizationToken = $token;
    }

    /**
     * @return bool
     */
    public function hasAuthorizationToken(): bool
    {
        return $this->authorizationToken !== null;
    }

    /**
     * @return string|null
     */
    public function getAuthorizationToken(): ?string
    {
        return $this->authorizationToken;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return Crawler
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function sendRequest($method, $uri, array $options = []): Crawler
    {
        $parameters = $options['parameters'] ?? [];
        $files = $options['files'] ?? [];
        $server = $options['server'] ?? [];
        $content = $options['content'] ?? '';
        $changeHistory  = $options['change_history'] ?? true;

        if ($this->hasAuthorizationToken()) {
            $headerKey = sprintf('HTTP_%s', JwtAuthenticator::AUTHORIZATION_HEADER_KEY);
            $server[$headerKey] = sprintf(
                '%s %s',
                JwtAuthenticator::AUTHORIZATION_TOKEN_PREFIX,
                $this->getAuthorizationToken()
            );
        }

        return $this->request(
            $method,
            $uri,
            $parameters,
            $files,
            array_merge($this->defaultServer(), $server),
            $content,
            $changeHistory
        );
    }

    /**
     * @return array
     */
    protected function defaultServer(): array
    {
        $remoteAddr = empty($this->getServerParameter('REMOTE_ADDR'))
            ? '10.10.10.10'
            : $this->getServerParameter('REMOTE_ADDR');

        return [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
            'REMOTE_ADDR' => $remoteAddr
        ];
    }
}
