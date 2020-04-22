<?php

namespace Multicaret\Unifonic;

use GuzzleHttp\Client;

class UnifonicClient implements UnifonicClientContract
{
    const API_URL = 'http://api.unifonic.com/rest/';
    const ENDPOINT_MESSAGES = 'Messages/';
    const ENDPOINT_ACCOUNT = "Account/";

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


    public function __construct(string $appSid)
    {
        $this->appSid = $appSid;
        $this->client = new Client();
        $this->headers = ['headers' => []];
        $this->additionalParams = [];
    }

    /**
     * Get Message status.
     *
     * @param int $messageID
     *
     * @return array
     */
    public function getMessageIDStatus(int $messageID)
    {
        return $this->postRequest(self::ENDPOINT_MESSAGES . 'GetMessageIDStatus', [
            'MessageID' => $messageID
        ]);
    }

    /**
     * Get summarized report about the sent messages within a specific time interval
     *
     *
     * @param null        $dateFrom
     * @param null        $dateTo
     * @param string|null $senderId
     * @param string|null $status
     * @param string|null $delivery
     *
     * @return object
     */
    public function getMessagesReport(
        $dateFrom = null,
        $dateTo = null,
        string $senderId = null,
        string $status = null,
        string $delivery = null
    ): object {
        return $this->postRequest(self::ENDPOINT_MESSAGES . 'GetMessagesReport', [
            'DateFrom' => $dateFrom,
            'DateTo' => $dateTo,
            'SenderID' => $senderId,
            'Status' => $status,
            'DLR' => $delivery,
        ]);
    }


    /**
     * Check the balance of your account.
     *
     * @return object
     */
    public function getBalance(): object
    {
        return $this->postRequest(self::ENDPOINT_ACCOUNT . 'GetBalance');
    }

    /**
     * Add a new sender ID to your account.
     *
     * Sender ID should not exceed 11 characters or 16 numbers,
     * only English letters allowed with no special characters or spaces
     *
     * @param string $senderID
     *
     * @return object
     */
    public function addSenderID(string $senderID): object
    {
        return $this->postRequest(self::ENDPOINT_ACCOUNT . 'addSenderID');
    }

    /**
     * Send a message to a recipient.
     *
     * @param int         $recipient
     * @param string      $message
     *
     * @param string|null $senderID
     *
     * @return object
     */
    public function send(int $recipient, string $message, string $senderID = null): object
    {
        return $this->postRequest(self::ENDPOINT_MESSAGES . 'Send', [
            'Recipient' => $recipient,
            'Body' => $message,
            'SenderID' => $senderID
        ]);
    }

    /**
     * Send a message to multiple recipients, comma separated.
     *
     * @param array       $recipients
     * @param string      $message
     *
     * @param string|null $senderID
     *
     * @return object
     */
    public function sendBulk(array $recipients, string $message, string $senderID = null): object
    {
        $recipients = implode(',', $recipients);

        return $this->postRequest(self::ENDPOINT_MESSAGES . 'SendBulk', [
            'Recipient' => $recipients,
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
     * @param string $endpoint
     * @param array  $parameters
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
     * @param bool $on
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
     * @param Callable $requestCallback
     *
     * @return $this
     */
    public function callback(Callable $requestCallback)
    {
        $this->requestCallback = $requestCallback;

        return $this;
    }

    public function testCredentials(): string
    {
        return "APP ID: " . $this->appSid;
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
            $promise = $this->client->postAsync(self::API_URL . $endPoint, $this->headers);

            return (is_callable($this->requestCallback) ? $promise->then($this->requestCallback) : $promise);
        }

        return $this->client->post(self::API_URL . $endPoint, $this->headers);
    }
}
