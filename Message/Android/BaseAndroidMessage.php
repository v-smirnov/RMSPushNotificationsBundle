<?php

namespace RMS\PushNotificationsBundle\Message\Android;

use RMS\PushNotificationsBundle\Message\MessageInterface;

abstract class BaseAndroidMessage implements MessageInterface
{
    /**
     * @var string
     */
    protected $message = "";

    /**
     * The data to send in the message
     *
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $deviceIdentifier = "";

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param array $data The custom data to send
     */
    public function setData($data)
    {
        $this->data = (is_array($data) ? $data : [$data]);
    }

    /**
     * @return mixed[]
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $deviceIdentifier
     */
    public function setDeviceIdentifier($deviceIdentifier)
    {
        $this->deviceIdentifier = $deviceIdentifier;
    }

    /**
     * @return string
     */
    public function getDeviceIdentifier()
    {
        return $this->deviceIdentifier;
    }
}
