<?php

namespace RMS\PushNotificationsBundle\Message\Android;

abstract class BaseCloudMessagingAndroidMessage extends BaseAndroidMessage
{
    /**
     * A collection of device identifiers that the message
     * is intended for. CM use only
     *
     * @var string[]
     */
    protected $devicesIdentifiers = [];

    /**
     * @param string[] $devicesIdentifiers
     */
    public function setDevicesIdentifiers(array $devicesIdentifiers) {
        $this->devicesIdentifiers = $devicesIdentifiers;
    }

    /**
     * @return mixed
     */
    public function getDevicesIdentifiers()
    {
        return $this->devicesIdentifiers;
    }

    /**
     * @param string $deviceIdentifier
     */
    public function addDeviceIdentifier($deviceIdentifier)
    {
        $this->devicesIdentifiers[] = $deviceIdentifier;
    }

    /**
     * @param string[] $devicesIdentifiers
     */
    public function addDevicesIdentifiers(array $devicesIdentifiers)
    {
        $this->devicesIdentifiers = array_merge($this->devicesIdentifiers, $devicesIdentifiers);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageBody()
    {
        return ["data" => array_merge(['message' => $this->message], $this->data)];
    }
}
