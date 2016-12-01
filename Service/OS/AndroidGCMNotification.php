<?php

namespace RMS\PushNotificationsBundle\Service\OS;

class AndroidGCMNotification extends BaseAndroidCloudMessagingNotification
{
    /**
     * {@inheritdoc}
     */
    protected function getApiUrl()
    {
        return 'https://android.googleapis.com/gcm/send';
    }
}
