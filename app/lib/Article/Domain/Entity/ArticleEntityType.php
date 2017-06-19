<?php

namespace Accorlex\Article\Domain\Entity;

use Accordia\Cqrs\Aggregate\AggregateId;
use Accordia\Cqrs\Aggregate\Revision;
use Accordia\Entity\EntityType\Attribute;
use Accordia\Entity\EntityType\EntityType;
use Accordia\Entity\Entity\TypedEntityInterface;
use Accordia\Entity\ValueObject\Text;

class ArticleEntityType extends EntityType
{
    public function __construct(string $entityName = 'Article')
    {
        parent::__construct($entityName, [
            Attribute::define("id", AggregateId::class, $this),
            Attribute::define("revision", Revision::class, $this),
            Attribute::define("title", Text::class, $this),
            Attribute::define("content", Text::class, $this),
        ]);
    }

    /**
     * @param mixed[] $articleState
     * @param TypedEntityInterface|null $parent
     * @return TypedEntityInterface
     */
    public function makeEntity(array $articleState = [], TypedEntityInterface $parent = null): TypedEntityInterface
    {
        $articleState["@type"] = $this;
        $articleState["@parent"] = $parent;
        return ArticleEntity::fromArray($articleState);
    }
}
