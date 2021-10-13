<?php

namespace App\WebSockets;

use App\Services\LogService;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
use Illuminate\Support\Facades\Log;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class CustomWebSocketHandler extends \BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler
    /*implements MessageComponentInterface*/
{

    protected $logService;

    public function __construct(ChannelManager $channelManager, LogService $logService)
    {
        parent::__construct($channelManager);

        $this->logService = $logService;
    }


    public function onOpen(ConnectionInterface $connection)
    {
        parent::onOpen($connection);

        Log::info("**** Custom WebSocketHandler onOpen ***** " . $connection->socketId);
        Log::info(date("Y-m-d h:i:sa"));


        /*$vars = get_object_vars($connection);
        foreach ($vars as $var => $val) {
            Log::info("Variable: " . $var . " : " . $val);
        }*/
    }

    public function onClose(ConnectionInterface $connection)
    {
        Log::info("**** Custom WebSocketHandler onClose ***** {$connection->socketId}");
        Log::info(date("Y-m-d h:i:sa"));

        $this->logService->touchLog($connection->socketId);

        parent::onClose($connection);

    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        parent::onError($connection, $e);

        $this->logService->touchLog($connection->socketId);

        Log::info("**** Custom WebSocketHandler onError *****");
        Log::info(date("Y-m-d h:i:sa"));
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $message)
    {
        parent::onMessage($connection, $message);

        Log::info("**** Custom WebSocketHandler onMessage ***** {$connection->socketId}");
        Log::info(date("Y-m-d h:i:sa"));

        $payload = $message->getPayload();
        $message = json_decode($payload, true);

        Log::info('$message->event)' . $message['event']);

        if ($message['event'] === 'pusher:subscribe') {

            $userData = json_decode($message['data']['channel_data'], true);

            Log::info("**** USER DATA *** " . $userData['user_id']);

            $this->logService->saveNewLog($userData['user_id'], $connection->socketId, 'subscribe');


        } else {
            $this->logService->touchLog($connection->socketId);

            Log::info(" ********* ********** *********** ********* *********");
            Log::info($payload);
        }

        /***** $message->getPayload() ******
         * {"event":"pusher:ping","data":{}}
         * {"event":"client-typing","data":1,"channel":"presence-chat.10"}
         * {"event":"pusher:subscribe","data":{"auth":"dhdrtyr5456w345twergsert4twergtyr:c941310eb34f670253bb88f2bb10a0d3dd02b3eec8cc431cd45e49e323f674c6","channel_data":"{\"user_id\":2,\"user_info\":{\"id\":2,\"name\":\"dev02\",\"email\":\"dev02@4p.es\",\"email_verified_at\":null,\"created_at\":\"2021-10-01T10:51:21.000000Z\",\"updated_at\":\"2021-10-01T10:51:21.000000Z\",\"chats\":[{\"id\":2,\"created_at\":\"2021-10-05T17:05:34.000000Z\",\"updated_at\":\"2021-10-05T17:05:34.000000Z\",\"isPrivate\":1,\"pivot\":{\"user_id\":2,\"chat_id\":2}},{\"id\":6,\"created_at\":\"2021-10-05T17:23:59.000000Z\",\"updated_at\":\"2021-10-05T17:23:59.000000Z\",\"isPrivate\":0,\"pivot\":{\"user_id\":2,\"chat_id\":6}},{\"id\":8,\"created_at\":\"2021-10-07T16:05:47.000000Z\",\"updated_at\":\"2021-10-07T16:05:47.000000Z\",\"isPrivate\":0,\"pivot\":{\"user_id\":2,\"chat_id\":8}},{\"id\":9,\"created_at\":\"2021-10-13T08:35:51.000000Z\",\"updated_at\":\"2021-10-13T08:35:51.000000Z\",\"isPrivate\":0,\"pivot\":{\"user_id\":2,\"chat_id\":9}},{\"id\":10,\"created_at\":\"2021-10-13T10:38:20.000000Z\",\"updated_at\":\"2021-10-13T10:38:20.000000Z\",\"isPrivate\":0,\"pivot\":{\"user_id\":2,\"chat_id\":10}}]}}","channel":"presence-chat.10"}}
         * [2021-10-13 14:09:52]
         *
         */
    }
}
