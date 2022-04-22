<?php

declare(strict_types=1);

/*
 * This file is part of datana-gmbh/doctrine-postgres-public-schema-listener package.
 *
 * (c) Datana GmbH <info@datana.rocks>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Datana\Doctrine\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * @author Igor Stevovic <igorstevovic@googlemail.com>
 */
final class MigrationEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            'postGenerateSchema',
        ];
    }

    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schema = $args->getSchema();

        if (!$schema->hasNamespace('public')) {
            $schema->createNamespace('public');
        }
    }
}
