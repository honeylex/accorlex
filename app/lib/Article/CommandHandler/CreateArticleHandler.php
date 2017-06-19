<?php

namespace Accorlex\Article\CommandHandler;

use Accordia\Cqrs\Aggregate\CommandHandler;
use Accordia\Cqrs\EventStore\UnitOfWorkInterface;
use Accordia\MessageBus\MessageBusInterface;
use Accordia\MessageBus\Metadata\Metadata;
use Accorlex\Article\Domain\Article;
use Accorlex\Article\Domain\Command\CreateArticle;
use Accorlex\Article\Domain\Entity\ArticleEntityType;

final class CreateArticleHandler extends CommandHandler
{
    /**
     * @var ArticleEntityType
     */
    private $articleType;

    /**
     * @param ArticleEntityType $articleType
     * @param UnitOfWorkInterface $unitOfWork
     * @param MessageBusInterface $messageBus
     */
    public function __construct(
        ArticleEntityType $articleType,
        UnitOfWorkInterface $unitOfWork,
        MessageBusInterface $messageBus
    ) {
        parent::__construct($unitOfWork, $messageBus);
        $this->articleType = $articleType;
    }

    /**
     * @param CreateArticle $createArticle
     * @param Metadata $metadata
     * @return bool
     */
    protected function handleCreateArticle(CreateArticle $createArticle, Metadata $metadata): bool
    {
        return $this->commit(
            Article::create($createArticle, $this->articleType),
            $metadata
        );
    }
}
