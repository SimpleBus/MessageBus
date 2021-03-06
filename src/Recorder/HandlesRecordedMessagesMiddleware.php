<?php

namespace SimpleBus\Message\Recorder;

use Exception;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class HandlesRecordedMessagesMiddleware implements MessageBusMiddleware
{
    private ContainsRecordedMessages $messageRecorder;

    private MessageBus $messageBus;

    public function __construct(ContainsRecordedMessages $messageRecorder, MessageBus $messageBus)
    {
        $this->messageRecorder = $messageRecorder;
        $this->messageBus = $messageBus;
    }

    public function handle(object $message, callable $next): void
    {
        try {
            $next($message);
        } catch (Exception $exception) {
            $this->messageRecorder->eraseMessages();

            throw $exception;
        }

        $recordedMessages = $this->messageRecorder->recordedMessages();

        $this->messageRecorder->eraseMessages();

        foreach ($recordedMessages as $recordedMessage) {
            $this->messageBus->handle($recordedMessage);
        }
    }
}
