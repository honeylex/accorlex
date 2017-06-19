<?php

namespace Accorlex\Article\Domain\Event;

use Accordia\Cqrs\Aggregate\AggregateId;
use Accordia\Cqrs\Aggregate\DomainEvent;
use Accordia\Entity\ValueObject\Text;
use Accordia\MessageBus\FromArrayTrait;
use Accordia\MessageBus\ToArrayTrait;
use Accorlex\Article\Domain\Command\CreateArticle;

final class ArticleWasCreated extends DomainEvent
{
    use ToArrayTrait;
    use FromArrayTrait;

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
