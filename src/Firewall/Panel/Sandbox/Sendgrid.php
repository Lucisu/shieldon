<?php
/*
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Shieldon\Firewall\Panel\Sandbox;

use Shieldon\Messenger\Sendgrid as SendgridTest;
use function explode;
use function filter_var;
use function str_replace;


/**
 * The sandbox for Sendgrid.
 */
class Sendgrid
{
    /**
     * Invoker.
     *
     * @param array $args
     *
     * @return bool
     */
    public function __invoke(array $args): bool
    {
        return $this->sandbox($args[0], $args[1]);
    }

    /**
     * Test Sendgrid.
     *
     * @param array $getParams The GET params passed from tryMessenger method.
     * @param array $message   The message title and body.
     *
     * @return bool
     */
    private function sandbox($getParams, $message)
    {
        $apiKey = $getParams['apiKey'] ?? '';
        $sender = $getParams['sender'] ?? '';
        $recipients = $getParams['recipients'] ?? '';

        if (!empty($sender) && !empty($recipients) && !empty($apiKey)) {
            $recipients = str_replace("\r", '|', $recipients);
            $recipients = str_replace("\n", '|', $recipients);
            $recipients = explode('|', $recipients);

            $messenger = new SendgridTest($apiKey);

            foreach($recipients as $recipient) {
                if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                    $messenger->addRecipient($recipient);
                }
            }

            if (filter_var($sender, FILTER_VALIDATE_EMAIL)) {
                $messenger->addSender($sender);
            }

            $messenger->setSubject($message['title']);

            if ($messenger->send($message['body'])) {
                return true;
            }
        }
        return false;
    }
}