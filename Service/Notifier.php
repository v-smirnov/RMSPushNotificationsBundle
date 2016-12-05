<?php

namespace RMS\PushNotificationsBundle\Service;

use Exception;
use RMS\PushNotificationsBundle\Message\MessageInterface;
use RMS\PushNotificationsBundle\Service\OS\OSNotificationServiceInterface;
use RuntimeException;

final class Notifier implements NotifierInterface
{
    /**
     * @var OSNotificationServiceInterface[]
     */
    protected $handlers = [];

    /**
     * @param OSNotificationServiceInterface[] $handlers
     */
    public function __construct(array $handlers = [])
    {
        $this->handlers = $handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function send(MessageInterface $message)
    {
        $messageSent = false;

        foreach ($this->handlers as $handler) {
            /* @var OSNotificationServiceInterface $handler */
            try {
                $messageSent = $messageSent || $handler->send($message);
            } catch (Exception $e){
                continue;
            }
        }

        if (!$messageSent) {
            throw new RuntimeException(
                sprintf(
                    "Could not send push notification for message class %s",
                    get_class($message)
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendList(array $messages)
    {
        foreach ($messages as $message) {
            $this->send($message);
        }
    }

    /**
     * @param OSNotificationServiceInterface $handler
     * @return $this
     */
    public function addHandler(OSNotificationServiceInterface $handler)
    {
        if (!in_array($handler, $this->handlers)) {
            $this->handlers[] = $handler;
        }

        return $this;
    }
}
