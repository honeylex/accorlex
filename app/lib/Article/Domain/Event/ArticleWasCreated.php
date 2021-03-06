<?php

namespace Accorlex\Article\Domain\Event;

use Accordia\Cqrs\Aggregate\AggregateId;
use Accordia\Cqrs\Aggregate\DomainEvent;
use Accordia\Entity\ValueObject\Text;
use Accordia\MessageBus\MessageInterface;
use Accorlex\Article\Domain\Command\CreateArticle;

final class ArticleWasCreated extends DomainEvent
{
    /**
     * @var \Accordia\Entity\ValueObject\Text
     * @buzz::fromArray->fromNative
     */
    private $title;

    /**
     * @var \Accordia\Entity\ValueObject\Text
     * @buzz::fromArray->fromNative
     */
    private $content;

    /**
     * @param  mixed[] $nativeValues
     * @return MessageInterface
     */
    public static function fromArray(array $nativeValues): MessageInterface
    {
        return new self(
            AggregateId::fromNative($nativeValues['aggregateId']),
            Text::fromNative($nativeValues['title']),
            Text::fromNative($nativeValues['content'])
        );
    }

    /**
     * @param  CreateArticle $createArticle
     * @return self
     */
    public static function viaCommand(CreateArticle $createArticle): self
    {
        return new self(
            $createArticle->getAggregateId(),
            $createArticle->getTitle(),
            $createArticle->getContent()
        );
    }

    /**
     * @return Text
     */
    public function getTitle(): Text
    {
        return $this->title;
    }

    /**
     * @return Text
     */
    public function getContent(): Text
    {
        return $this->content;
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        $arr = parent::toArray();
        $arr['title'] = $this->title->toNative();
        $arr['content'] = $this->content->toNative();
        return $arr;
    }

    /**
     * @param AggregateId $aggregateId
     * @param Text $title
     * @param Text $content
     */
    protected function __construct(AggregateId $aggregateId, Text $title, Text $content)
    {
        parent::__construct($aggregateId);
        $this->title = $title;
        $this->content = $content;
    }
}
