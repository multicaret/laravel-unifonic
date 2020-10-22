<?php

namespace Multicaret\Unifonic;

use GuzzleHttp\Client;

class UnifonicClient implements UnifonicClientContract
{
    const API_URL = 'http://basic.unifonic.com/rest/';
    const ENDPOINT_MESSAGES = 'SMS/messages';

    private $client;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $appSid;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $additionalParams;
    /**
     * @var bool
     */
    public $requestAsync = false;

    /**
     * @var Callable
     */
    private $requestCallback;


    public function __construct(string $appSid, string $email, string $password)
    {
        $this->appSid = $appSid;
        $this->email = $email;
        $this->password = $password;
        $this->client = new Client();
        $this->headers = [
            'headers' => [
                'Authorization' => 'Basic '.base64_encode("$this->email:$this->password")
            ],
        ];
        $this->additionalParams = [];
    }

    /**
     * Get Message status.
     *
     * @param  int  $messageID
     *
     * @return array
     */
    public function getMessageIDStatus(int $messageID)
    {
        return $this->postRequest(self::ENDPOINT_MESSAGES.'/GetMessagesDetails', [
            'MessageID' => $messageID
        ]);
    }

    /**
     * Send a message to a recipient.
     *
     * @param  int  $recipient
     * @param  string  $message
     *
     * @param  string|null  $senderID
     *
     * @return object
     */
    public function send(int $recipient, string $message, string $senderID = null): object
    {
        return $this->postRequest(self::ENDPOINT_MESSAGES, [
            'Recipient' => $recipient,
            'Body' => $message,
            'SenderID' => $senderID
        ]);
    }

    private function usesFormUrlEncoded()
    {
        $this->headers['headers']['Content-Type'] = 'application/x-www-form-urlencoded';

        return $this;
    }

    /**
     * @param  string  $endpoint
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function postRequest(string $endpoint, $parameters = [])
    {
        $this->usesFormUrlEncoded();
        if ( ! array_key_exists('AppSid', $parameters) || empty($parameters['AppSid'])) {
            $parameters['AppSid'] = $this->appSid;
        }
        if (count($this->additionalParams)) {
            $parameters = array_merge($parameters, $this->additionalParams);
        }

        $this->headers['body'] = http_build_query($parameters);

        return json_decode(
            $this->post($endpoint)
                 ->getBody()
                 ->getContents()
        );
    }


    /**
     * Turn on, turn off async requests
     *
     * @param  bool  $on
     *
     * @return $this
     */
    public function async($on = true)
    {
        $this->requestAsync = $on;

        return $this;
    }

    /**
     * Callback to execute after Unifonic returns the response
     *
     * @param  Callable  $requestCallback
     *
     * @return $this
     */
    public function callback(callable $requestCallback)
    {
        $this->requestCallback = $requestCallback;

        return $this;
    }

    public function retrieveCredentialsForTesting(): string
    {
        return sprintf("APP ID: %s, Email: %s, Password: %s", $this->appSid, $this->email, $this->password);
    }

    public function addParams($params = [])
    {
        $this->additionalParams = $params;

        return $this;
    }

    public function setParam($key, $value)
    {
        $this->additionalParams[$key] = $value;

        return $this;
    }


    private function post($endPoint)
    {
        if ($this->requestAsync === true) {
            $promise = $this->client->postAsync(self::API_URL.$endPoint, $this->headers);

            return (is_callable($this->requestCallback) ? $promise->then($this->requestCallback) : $promise);
        }

        return $this->client->post(self::API_URL.$endPoint, $this->headers);
    }
}
