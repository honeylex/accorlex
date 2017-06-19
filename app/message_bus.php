<?php

use Accordia\Cqrs\Aggregate\Revision;
use Accordia\Cqrs\EventStore\CommitStream;
use Accordia\Cqrs\EventStore\CommitStreamInterface;
use Accordia\Cqrs\EventStore\PersistenceAdapterInterface;
use Accordia\Cqrs\EventStore\StreamId;
use Accordia\Cqrs\EventStore\UnitOfWork;
use Accordia\Cqrs\Projection\StandardProjector;
use Accordia\MessageBus\Channel\Channel;
use Accordia\MessageBus\Channel\ChannelMap;
use Accordia\MessageBus\Channel\Subscription\MessageHandler\MessageHandlerList;
use Accordia\MessageBus\Channel\Subscription\Subscription;
use Accordia\MessageBus\Channel\Subscription\SubscriptionMap;
use Accordia\MessageBus\Channel\Subscription\Transport\InProcessTransport;
use Accordia\MessageBus\EnvelopeInterface;
use Accordia\MessageBus\MessageBus;
use Accordia\Tests\Cqrs\Fixture\NoOpHandler;
use Accorlex\Article\CommandHandler\CreateArticleHandler;
use Accorlex\Article\Domain\Article;
use Accorlex\Article\Domain\Entity\ArticleEntityType;
use Accorlex\Article\Projection\ArticleProjectionType;
use Accorlex\MessageBus\CallbackHandler;
use Accorlex\MessageBus\LazyHandler;

// /dev/null style persistence implementation for the CommitStream
final class EchoPersistenceAdapter implements PersistenceAdapterInterface
{
    /**
     * @param StreamId $streamId
     * @param Revision|null $revision
     * @return CommitStreamInterface
     */
    public function loadStream(StreamId $streamId, Revision $revision = null): CommitStreamInterface
    {
        echo "<h4>".__METHOD__ . " streamId: $streamId, revision: $revision</h4>";
        return CommitStream::fromStreamId($streamId);
    }

    /**
     * @param CommitStreamInterface $stream
     * @param Revision $storeHead
     * @return bool
     */
    public function storeStream(CommitStreamInterface $stream, Revision $storeHead): bool
    {
        echo "<h4>".__METHOD__ . " streamId: ". $stream->getStreamId().", revision: ".$stream->getStreamRevision()."</h4>";
        return true;
    }
}

// used to pass the message-bus by reference to any command-handler factories
$messageBus = null;
// create unit-of-work for article AR's
$articleUow = new UnitOfWork(Article::class, new EchoPersistenceAdapter);
// create in-process transport used for all subscriptions
$transport = new InProcessTransport("inproc");
$commandHandlerFactory = function () use (&$messageBus, $articleUow) {
    return new CreateArticleHandler(new ArticleEntityType, $articleUow, $messageBus);
};
// setup command channel
$commandHandlers = new MessageHandlerList([ new LazyHandler($commandHandlerFactory) ]);
$commandSub = new Subscription("command-sub", $transport, $commandHandlers);
$commandChannel = new Channel("commands", new SubscriptionMap([ $commandSub ]));
// setup commit channel
$commitHandlers = new MessageHandlerList([ new StandardProjector(new ArticleProjectionType) ]);
$commitSub = new Subscription("commit-sub", $transport, $commitHandlers);
$commitChannel = new Channel("commits", new SubscriptionMap([ $commitSub ]));
// setup event channel
$eventHandlers = new MessageHandlerList([ new CallbackHandler(function (EnvelopeInterface $envelope): bool {
    echo "<h4>Received message '".get_class($envelope->getMessage())."' on (post-commit)event channel, but not doing anything with it atm.</h4>";
    return true;
}) ]);
$eventSub = new Subscription("event-sub", $transport, $eventHandlers);
$eventChannel = new Channel("events", new SubscriptionMap([ $eventSub ]));
// initialize message-bus
$messageBus = new MessageBus(new ChannelMap([ $commandChannel, $commitChannel, $eventChannel ]));
return $messageBus;
