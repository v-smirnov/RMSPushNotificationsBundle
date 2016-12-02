<?php

namespace RMS\PushNotificationsBundle\Service\OS;

use RMS\PushNotificationsBundle\Device\Types;
use RMS\PushNotificationsBundle\Exception\InvalidMessageTypeException;
use RMS\PushNotificationsBundle\Message\MessageInterface;

class AndroidGCMNotification extends BaseAndroidCloudMessagingNotification
{
    /**
     * {@inheritdoc}
     */
    protected function getApiUrl()
    {
        return 'https://android.googleapis.com/gcm/send';
    }

    /**
     * {@inheritdoc}
     */
    protected function validateMessage(MessageInterface $message)
    {
        if ($message->getTargetOS() != Types::OS_ANDROID_GCM) {
            throw new InvalidMessageTypeException(sprintf("Message type '%s' not supported by GCM", get_class($message)));
        }
    }
}
