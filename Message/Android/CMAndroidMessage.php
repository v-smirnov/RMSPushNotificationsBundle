<?php

namespace RMSPushNotificationsBundle\Message\Android;

use RMS\PushNotificationsBundle\Device\Types;

final class CMAndroidMessage extends BaseAndroidMessage
{
    /**
     * A collection of device identifiers that the message
     * is intended for.
     *
     * @var string[]
     */
    protected $devicesIdentifiers = [];

    /**
     * Additional options to send in the message
     *
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
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
            !empty($this->options)
                ? array_merge($body, $this->options)
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
