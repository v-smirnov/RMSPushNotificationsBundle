<?php

namespace RMSPushNotificationsBundle\Message\Android;

use RMS\PushNotificationsBundle\Device\Types;

class C2DMAndroidMessage extends BaseAndroidMessage
{
    const DEFAULT_COLLAPSE_KEY = 1;

    /**
     * Collapse key for data
     *
     * @var int
     */
    protected $collapseKey = self::DEFAULT_COLLAPSE_KEY;

    /**
     * @param int $collapseKey
     */
    public function setCollapseKey($collapseKey)
    {
        $this->collapseKey = $collapseKey;
    }

    /**
     * @return int
     */
    public function getCollapseKey()
    {
        return $this->collapseKey;
    }

    /**
     * @return array
     */
    public function getMessageBody()
    {
        $data = array(
            "registration_id" => $this->deviceIdentifier,
            "collapse_key"    => $this->collapseKey,
            "data.message"    => $this->message,
        );
        if (!empty($this->data)) {
            $data = array_merge($data, $this->data);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetOS()
    {
        return Types::OS_ANDROID_C2DM;
    }
}
