<?php

namespace Accorlex\Article\Domain\Command;

use Accordia\Cqrs\Aggregate\AggregateId;
use Accordia\Cqrs\Aggregate\Command;
use Accordia\Entity\ValueObject\Text;
use Accordia\MessageBus\MessageInterface;

final class CreateArticle extends Command
{
    /**
     * @var Text $title
     */
    private $title;

    /**
     * @var Text $content
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
