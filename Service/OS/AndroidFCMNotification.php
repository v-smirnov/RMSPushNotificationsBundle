<?php

namespace RMS\PushNotificationsBundle\Service\OS;

use RMS\PushNotificationsBundle\Device\Types;
use RMS\PushNotificationsBundle\Exception\InvalidMessageTypeException;
use RMS\PushNotificationsBundle\Message\MessageInterface;

class AndroidFCMNotification extends BaseAndroidCloudMessagingNotification
{
    /**
     * {@inheritdoc}
     */
    protected function getApiUrl()
    {
        return 'https://fcm.googleapis.com/fcm/send';
    }

    /**
     * {@inheritdoc}
     */
    protected function validateMessage(MessageInterface $message)
    {
        if ($message->getTargetOS() != Types::OS_ANDROID_FCM) {
            throw new InvalidMessageTypeException(sprintf("Message type '%s' not supported by FCM", get_class($message)));
        }
    }
}
