<?php


namespace Oza75\OrangeSMSChannel;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use Oza75\OrangeSMSChannel\Contracts\OrangeSMSBridge;

class OrangeSMSChannel
{
    private OrangeSMSBridge $bridge;

    /**
     * OrangeSMSChannel constructor.
     *
     */
    public function __construct(OrangeSMSBridge $bridge)
    {
        $this->bridge = $bridge;
    }

    /**
     * Send the given notification.
     *
     * @param  $notifiable
     * @param Notification $notification
     * @return void
     * @throws Exception
     * @throws GuzzleException
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toOrange')) {
            throw new Exception("Your notification class should implement a toOrange method !");
        }

        $this->sendMessage($notification->toOrange($notifiable));
    }

    /**
     * Send the given notification message.
     *
     * @param OrangeMessage $message
     * @return array
     * @throws GuzzleException
     */
    public function sendMessage(OrangeMessage $message): array
    {
        $config = config('orange-sms.from');

        return $this
            ->bridge
            ->to($message->getTo())
            ->from($message->getFrom() ?? $config['phone_number'], $message->getSenderName() ?? $config['name'])
            ->message($message->getText())
            ->send();
    }
}