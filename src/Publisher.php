<?php

namespace Bschmitt\Amqp;

use Bschmitt\Amqp\Message;

/**
 * @author Björn Schmitt <code@bjoern.io>
 */
class Publisher extends Request
{

    /**
     * @param string  $routing
     * @param Message $message
     * @throws Exception\Configuration
     */
    public function publish($routing, $message)
    {
        if (is_string($message)) {
            $message = new Message($message, ['content_type' => 'text/plain', 'delivery_mode' => 2]);
        }

        $this->getChannel()->basic_publish($message, $this->getProperty('exchange'), $routing);
    }
}
