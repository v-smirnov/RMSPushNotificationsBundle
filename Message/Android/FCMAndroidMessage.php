<?php

namespace RMS\PushNotificationsBundle\Message\Android;

use RMS\PushNotificationsBundle\Device\Types;

final class FCMAndroidMessage extends BaseCloudMessagingAndroidMessage
{
    /**
     * @var mixed[]
     */
    private $notification = [];

    /**
     * @return mixed[]
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param mixed[] $notification
     * @return void
     */
    public function setNotification(array $notification)
    {
        $this->notification = $notification;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetOS()
    {
        return Types::OS_ANDROID_FCM;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageBody()
    {
        return
            !empty($this->notification)
                ? array_merge(parent::getMessageBody(), ['notification' => $this->notification])
                : parent::getMessageBody()
            ;
    }
}
