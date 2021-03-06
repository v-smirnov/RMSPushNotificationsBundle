<?php

namespace RMS\PushNotificationsBundle\Message\Android;

use RMS\PushNotificationsBundle\Device\Types;

final class CMAndroidMessage extends BaseAndroidMessage
{
    /**
     * A collection of device identifiers that the message
     * is intended for.
     *
     * @var string[]
     */
    private $devicesIdentifiers = [];

    /**
     * @var mixed[]
     */
    private $notification = [];

    /**
     * @param mixed[] $notification
     */
    public function setNotification(array $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return mixed[]
     */
    public function getNotification()
    {
        return $this->notification;
    }

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
        $body = ["data" => array_merge(['message' => $this->message], $this->data)];

        return
            !empty($this->notification)
                ? array_merge($body, ['notification' => $this->notification])
                : $body
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetOS()
    {
        return Types::OS_ANDROID_CM;
    }
}
