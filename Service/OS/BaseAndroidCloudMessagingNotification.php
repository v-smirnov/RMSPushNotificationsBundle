<?php

namespace RMS\PushNotificationsBundle\Service\OS;

use Psr\Log\LoggerInterface;
use RMS\PushNotificationsBundle\Exception\InvalidMessageTypeException;
use RMS\PushNotificationsBundle\Message\MessageInterface;
use Buzz\Browser;
use Buzz\Client\AbstractCurl;
use Buzz\Client\Curl;
use Buzz\Client\MultiCurl;

abstract class BaseAndroidCloudMessagingNotification implements OSNotificationServiceInterface
{
    /**
     * Whether or not to use the dry run CM
     *
     * @var bool
     */
    protected $useDryRun = false;

    /**
     * Google CM API key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Max registration count
     *
     * @var integer
     */
    protected $registrationIdMaxCount = 1000;

    /**
     * Browser object
     *
     * @var \Buzz\Browser
     */
    protected $browser;

    /**
     * Collection of the responses from the CM communication
     *
     * @var array
     */
    protected $responses;

    /**
     * Monolog logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param string $apiKey
     * @param bool $useMultiCurl
     * @param int $timeout
     * @param LoggerInterface $logger
     * @param AbstractCurl $client (optional)
     * @param bool $dryRun
     */
    public function __construct($apiKey, $useMultiCurl, $timeout, $logger, AbstractCurl $client = null, $dryRun = false)
    {
        $this->useDryRun = $dryRun;
        $this->apiKey = $apiKey;

        if (!$client) {
            $client = ($useMultiCurl ? new MultiCurl() : new Curl());
        }
        $client->setTimeout($timeout);

        $this->browser = new Browser($client);
        $this->browser->getClient()->setVerifyPeer(false);
        $this->responses = [];
        $this->logger = $logger;
    }

    /**
     * @param  MessageInterface $message
     * @throws InvalidMessageTypeException
     * @return bool
     */
    public function send(MessageInterface $message)
    {
        $this->validateMessage($message);

        $data = $this->prepareInitialData($message);

        if ($this->isSingleDeviceNotification($message)) {
            $data['to'] = $message->getDeviceIdentifier();
            $this->responses[] = $this->browser->post($this->getApiUrl(), $this->getHeaders(), json_encode($data));
        } else if ($this->isMultiDeviceNotification($message)){
            // Chunk number of registration IDs according to the maximum allowed by GCM
            $chunks = array_chunk($message->getDevicesIdentifiers(), $this->registrationIdMaxCount);

            foreach ($chunks as $registrationIDs) {
                $data['registration_ids'] = $registrationIDs;
                $this->responses[] = $this->browser->post($this->getApiUrl(), $this->getHeaders(), json_encode($data));
            }
        } else {
            throw new \RuntimeException('No device identifiers presented in message.');
        }

        // If we're using multiple concurrent connections via MultiCurl
        // then we should flush all requests
        if ($this->browser->getClient() instanceof MultiCurl) {
            $this->browser->getClient()->flush();
        }

        // Determine success
        foreach ($this->responses as $response) {
            $message = json_decode($response->getContent());
            if ($message === null || $message->success == 0 || $message->failure > 0) {
                if ($message == null) {
                    $this->logger->error($response->getContent());
                } else {
                    foreach ($message->results as $result) {
                        if (isset($result->error)) {
                            $this->logger->error($result->error);
                        }
                    }
                }
                return false;
            }
        }

        return true;
    }

    /**
     * Returns responses
     *
     * @return array
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @return string
     */
    abstract protected function getApiUrl();

    /**
     * @param MessageInterface $message
     * @return void
     * @throws InvalidMessageTypeException
     */
    abstract protected function validateMessage(MessageInterface $message);

    /**
     * @return string[]
     */
    private function getHeaders()
    {
        return [
            "Authorization: key=" . $this->apiKey,
            "Content-Type: application/json",
        ];
    }

    /**
     * @param MessageInterface $message
     * @return bool
     */
    private function isSingleDeviceNotification(MessageInterface $message)
    {
        return !empty($message->getDeviceIdentifier());
    }

    /**
     * @param MessageInterface $message
     * @return bool
     */
    private function isMultiDeviceNotification(MessageInterface $message)
    {
        return count($message->getDevicesIdentifiers()) > 0;
    }

    /**
     * @param MessageInterface $message
     * @return mixed[]
     */
    private function prepareInitialData(MessageInterface $message)
    {
        return
            $this->useDryRun
                ? array_merge($message->getMessageBody(), ['dry_run' => true])
                : $message->getMessageBody()
            ;
    }
}
