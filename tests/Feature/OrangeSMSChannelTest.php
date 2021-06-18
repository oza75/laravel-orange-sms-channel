<?php

namespace Oza75\OrangeSMSChannel\Tests\Feature;

use Oza75\OrangeSMSChannel\Contracts\OrangeSMSBridge;
use Oza75\OrangeSMSChannel\Contracts\OrangeSMSClient;
use Oza75\OrangeSMSChannel\OrangeMessage;
use Oza75\OrangeSMSChannel\OrangeSMSChannel;
use Oza75\OrangeSMSChannel\Tests\TestCase;

class OrangeSMSChannelTest extends TestCase
{

    public function test_can_send_sms_message()
    {
        $this->mock(OrangeSMSClient::class);
        $bridge = $this->spy(OrangeSMSBridge::class);
        $bridge->shouldReceive('from')->andReturnSelf();
        $bridge->shouldReceive('to')->andReturnSelf();
        $bridge->shouldReceive('message')->andReturnSelf();
        $bridge->shouldReceive('send')->andReturn([]);

        $channel = app(OrangeSMSChannel::class);

        $message = (new OrangeMessage())->to('+237690000000')->from('+237690000000')->text('test');
        $channel->sendMessage($message);
    }
}