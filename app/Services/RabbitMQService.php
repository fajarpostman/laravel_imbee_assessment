<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    private $connection;
    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'), 
            env('RABBITMQ_PORT'), 
            env('RABBITMQ_USERNAME'), 
            env('RABBITMQ_PASSWORD')
        );
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(env('RABBITMQ_QUEUE'), false, true, false, false);
    }

    public function publish($message)
    {
        $msg = new AMQPMessage(json_encode($message), ['delivery_mode' => 2]);
        $this->channel->basic_publish($msg, env('RABBITMQ_EXCHANGE'), env('RABBITMQ_QUEUE'));
    }

    public function consume(callable $callback)
    {
        $this->channel->basic_consume(env('RABBITMQ_QUEUE'), '', false, true, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}