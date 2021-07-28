<?php


namespace Seatplus\TelegramChannel\Services;


use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

class Telegram
{
    protected HttpClient $http;
    protected string $apiBaseUri;

    public function __construct(
        protected string|null $token = null,
        ?HttpClient $httpClient = null,
        $apiBaseUri = null)
    {

        $this->http = $httpClient ?? new HttpClient();
        $this->setApiBaseUri($apiBaseUri ?? 'https://api.telegram.org');
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getApiBaseUri(): string
    {
        return $this->apiBaseUri;
    }

    public function setApiBaseUri(string $apiBaseUri): self
    {
        $this->apiBaseUri = rtrim($apiBaseUri, '/');

        return $this;
    }

    protected function httpClient(): HttpClient
    {
        return $this->http;
    }

    public function setHttpClient(HttpClient $http): self
    {
        $this->http = $http;

        return $this;
    }

    public function getUpdates(array $params): ?ResponseInterface
    {
        return $this->sendRequest('GET','getUpdates', $params);
    }

    protected function sendRequest(string $method, string $endpoint, array $params, bool $multipart = false): ?ResponseInterface
    {
        if (blank($this->token)) {
            throw CouldNotSendNotification::telegramBotTokenNotProvided('You must provide your telegram bot token to make any API requests.');
        }

        $apiUri = sprintf('%s/bot%s/%s', $this->apiBaseUri, $this->token, $endpoint);

        try {
            return $this->httpClient()->request($method, $apiUri, [
                $multipart ? 'multipart' : 'form_params' => $params,
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::telegramRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithTelegram($exception);
        }
    }

}