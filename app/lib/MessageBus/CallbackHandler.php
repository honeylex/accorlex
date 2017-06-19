<?php

namespace Accorlex\MessageBus;

use Accordia\MessageBus\Channel\Subscription\MessageHandler\MessageHandlerInterface;
use Accordia\MessageBus\EnvelopeInterface;

class CallbackHandler implements MessageHandlerInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param EnvelopeInterface $envelope
     * @return bool
     */
    public function handle(EnvelopeInterface $envelope): bool
    {
        return call_user_func($this->callback, $envelope);
    }
}
