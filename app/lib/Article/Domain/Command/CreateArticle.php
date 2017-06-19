<?php

namespace Accorlex\Article\Domain\Command;

use Accordia\Cqrs\Aggregate\AggregateId;
use Accordia\Cqrs\Aggregate\Command;
use Accordia\Entity\ValueObject\Text;
use Accordia\MessageBus\FromArrayTrait;
use Accordia\MessageBus\ToArrayTrait;

final class CreateArticle extends Command
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
