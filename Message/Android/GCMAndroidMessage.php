<?php

namespace RMSPushNotificationsBundle\Message\Android;

use RMS\PushNotificationsBundle\Device\Types;

final class GCMAndroidMessage extends BaseCloudMessagingAndroidMessage
{
    /**
     * {@inheritdoc}
     */
    public function getTargetOS()
    {
        return Types::OS_ANDROID_GCM;
    }
}
