<?php

namespace RMS\PushNotificationsBundle\Service\OS;

class AndroidFCMNotification extends BaseAndroidCloudMessagingNotification
{
    /**
     * {@inheritdoc}
     */
    protected function getApiUrl()
    {
        return 'https://fcm.googleapis.com/fcm/send';
    }
}
