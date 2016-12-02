<?php

namespace RMS\PushNotificationsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use RMS\PushNotificationsBundle\Message\MessageInterface;

class TestPushCommand extends ContainerAwareCommand
{
    /**
     * Configures the console commnad
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName("rms:test-push")
            ->setDescription("Sends a push command to a supplied push token'd device")
            ->addOption("badge", "b", InputOption::VALUE_OPTIONAL, "Badge number (for iOS devices)", 0)
            ->addOption("text", "t", InputOption::VALUE_OPTIONAL, "Text message")
            ->addArgument("service", InputArgument::REQUIRED, "One of 'ios', 'c2dm', 'gcm', 'fcm', 'mac', 'blackberry' or 'windowsphone'")
            ->addArgument("token", InputArgument::REQUIRED, "Authentication token for the service")
            ->addArgument("payload", InputArgument::OPTIONAL, "The payload data to send (JSON)", '{"data": "test"}')
        ;
    }

    /**
     * Main command execution.
     *
     * @param  InputInterface  $input  An InputInterface instance
     * @param  OutputInterface $output An OutputInterface instance
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token = $input->getArgument("token");
        $service = strtolower($input->getArgument("service"));
        $json_payload = $input->getArgument("payload");
        $payload = json_decode($json_payload, true);

        $tokenLengths = array(
            "ios" => 64,
            "c2dm" => 162,
        );

        if (isset($tokenLengths[$service]) && strlen($token) != $tokenLengths[$service]) {
            $output->writeln("<error>Token should be " . $tokenLengths[$service] . "chars long, not " . strlen($token) . "</error>");

            return;
        }

        if ($payload == null) {
            throw new \InvalidArgumentException("Invalid JSON payload " . $json_payload);
        }

        $message = $this->getEmptyMessage($service);

        if (method_exists($message, "setAPSBadge")) {
            // Set badge on iOS
            $message->setAPSBadge((int) $input->getOption("badge"));
        }
        if (method_exists($message, "setAPSSound")) {
            // Set sound on iOS
            $message->setAPSSound("default");
        }

        $message->setDeviceIdentifier($token);
        $message->setData($payload);

        if ($input->getOption("text")) {
            $message->setMessage($input->getOption("text"));
        }

        $result = $this->getContainer()->get("rms_push_notifications")->send($message);
        if ($result) {
            $output->writeln("<comment>Send successful</comment>");
        } else {
            $output->writeln("<error>Send failed</error>");
        }

        $output->writeln("<comment>done</comment>");
    }

    /**
     * Returns a message class based on the supplied os
     *
     * @param  string $service The name of the service to return a message for
     * @throws \InvalidArgumentException
     * @return MessageInterface
     */
    protected function getEmptyMessage($service)
    {
        $serviceToMessageClassMap =  $this->getServiceToMessageClassMap();

        if (!array_key_exists($service, $serviceToMessageClassMap)) {
            throw new \InvalidArgumentException("Service '{$service}' not supported presently");
        }

        return new $serviceToMessageClassMap[$service];
    }

    /**
     * @return string[]
     */
    private function getServiceToMessageClassMap()
    {
        return
            [
                'ios' => 'RMS\PushNotificationsBundle\Message\iOSMessage',
                'c2dm' => 'RMS\PushNotificationsBundle\Message\Android\C2DMAndroidMessage',
                'cm' => 'RMS\PushNotificationsBundle\Message\Android\CMAndroidMessage',
                'blackberry' => 'RMS\PushNotificationsBundle\Message\BlackberryMessage',
                'mac' => 'RMS\PushNotificationsBundle\Message\MacMessage',
                'windowsphone' => 'RMS\PushNotificationsBundle\Message\WindowsphoneMessage',
            ];
    }
}
