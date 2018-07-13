<?php

namespace Bschmitt\Amqp;

use Closure;
use Bschmitt\Amqp\Request;
use Bschmitt\Amqp\Message;

/**
 * @author Björn Schmitt <code@bjoern.io>
 */
class Amqp
{

    /**
     * @param string $routing
     * @param mixed  $message
     * @param array  $properties
     */
    public function publish($routing, $message, array $properties = [])
    {
        $properties['routing'] = $routing;

        $publisher = $this->getPublisher();
        $publisher->publish($routing, $message);
        Request::shutdown($publisher->getChannel(), $publisher->getConnection());
    }

    /**
     * @param string  $queue
     * @param Closure $callback
     * @param array   $properties
     * @throws Exception\Configuration
     */
    public function consume($queue, Closure $callback, $properties = [])
    {
        $properties['queue'] = $queue;

        $consumer = $this->getConsumer();
        $consumer->consume($queue, $callback);
        Request::shutdown($consumer->getChannel(), $consumer->getConnection());
    }

    /**
     * @param array $properties
     * @return Consumer
     */
    public function getConsumer()
    {
        /* @var Consumer $consumer */
        $consumer = app()->make('Bschmitt\Amqp\Consumer');
        $consumer
            ->mergeProperties($properties)
            ->setup();

        return $consumer;
    }

    /**
     * @param array $properties
     * @return Publisher
     */
    public function getPublisher()
    {
        $publisher = app()->make('Bschmitt\Amqp\Publisher');
        $publisher
            ->mergeProperties($properties)
            ->setup();

        return $publisher;
    }

    /**
     * @param string $body
     * @param array  $properties
     * @return Message
     * @deprecated 3.0.0 Replace with own message object creation
     */
    public function message($body, $properties = [])
    {
        return new Message($body, $properties);
    }
}
