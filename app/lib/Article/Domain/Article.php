<?php

namespace Accorlex\Article\Domain;

use Accordia\Cqrs\Aggregate\AggregateIdInterface;
use Accordia\Cqrs\Aggregate\AggregateRoot;
use Accorlex\Article\Domain\Command\CreateArticle;
use Accorlex\Article\Domain\Entity\ArticleEntityType;
use Accorlex\Article\Domain\Event\ArticleWasCreated;

final class Article extends AggregateRoot
{
    /**
     * @var \Accorlex\Article\Domain\Entity\ArticleEntity
     */
    private $articleState;

    /**
     * @return AggregateIdInterface
     */
    public function getIdentifier(): AggregateIdInterface
    {
        return $this->articleState->getIdentity();
    }

    /**
     * @param CreateArticle $createArticle
     * @param ArticleEntityType $articleType
     * @return self
     */
    public static function create(CreateArticle $createArticle, ArticleEntityType $articleType): self
    {
        return (new self($articleType))
            ->reflectThat(ArticleWasCreated::viaCommand($createArticle));
    }

    /**
     * @param  ArticleWasCreated $articleWasCreated
     * @return self
     */
    protected function whenArticleWasCreated(ArticleWasCreated $articleWasCreated)
    {
        $this->articleState = $this->articleState
            ->withId($articleWasCreated->getAggregateId())
            ->withTitle($articleWasCreated->getTitle())
            ->withContent($articleWasCreated->getContent());
    }

    /**
     * @param ArticleEntityType $articleType
     */
    protected function __construct(ArticleEntityType $articleType)
    {
        parent::__construct();
        $this->articleState = $articleType->makeEntity();
    }
}
