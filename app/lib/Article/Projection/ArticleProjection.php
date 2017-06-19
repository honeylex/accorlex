<?php

namespace Accorlex\Article\Projection;

use Accordia\Cqrs\Projection\ProjectionInterface;
use Accordia\Cqrs\Projection\ProjectionTrait;
use Accorlex\Article\Domain\Entity\ArticleEntity;
use Accorlex\Article\Domain\Event\ArticleWasCreated;

final class ArticleProjection extends ArticleEntity implements ProjectionInterface
{
    use ProjectionTrait;

    /**
     * @param ArticleWasCreated $articleWasCreated
     * @return self
     */
    protected function whenArticleWasCreated(ArticleWasCreated $articleWasCreated): self
    {
        return $this
            ->withId($articleWasCreated->getAggregateId())
            ->withTitle($articleWasCreated->getTitle())
            ->withContent($articleWasCreated->getContent());
    }
}
