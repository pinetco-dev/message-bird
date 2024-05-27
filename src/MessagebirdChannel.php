<?php

namespace Pinetcodev\MessageBird;

use Exception;
use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class MessagebirdChannel
{
    public Client $client;

    public array $sendOptions = [];

    public function __construct()
    {
        $this->client = new Client(
            config('services.messagebird.workspace_key'),
            config('services.messagebird.access_key'),
            null,
            null,
            new MessageBirdClient());

        $this->sendOptions['from'] = config('services.messagebird.originator');
    }

    public function send($notifiable, Notification $notification)
    {
        $this->sendOptions['body'] = $notification->toMessagebird($notifiable);

        $to = $notifiable->routeNotificationFor('messagebird');

        return $this->sendMessage($to);
    }

    /**
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function sendMessage(string $to)
    {
        $data = [];
        try {
            $data = $this->client
                ->messages
                ->create($to, $this->sendOptions)
                ->toArray();
        } catch (Exception $e) {
            logger()->error('BIRD: SMS Failed to '.$to.': '.$this->sendOptions['body']);
            throw $e;
        }

        return $data;
    }
}
