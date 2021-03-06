<?php

namespace RMS\PushNotificationsBundle\Service;

use RMS\PushNotificationsBundle\Message\MessageInterface;

interface NotifierInterface
{
    /**
     * @param MessageInterface $message
     * @return void
     */
    public function send(MessageInterface $message);
}
