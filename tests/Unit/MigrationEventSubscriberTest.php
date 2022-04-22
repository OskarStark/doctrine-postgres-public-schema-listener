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

namespace Datana\Doctrine\EventSubscriber\Tests\Unit;

use Datana\Doctrine\EventSubscriber\MigrationEventSubscriber;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use PHPUnit\Framework\TestCase;

/**
 * @author Oskar Stark <oskarstark@googlemail.com>
 */
final class MigrationEventSubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function getSubscribedEvents(): void
    {
        $eventSubscriber = new MigrationEventSubscriber();

        self::assertSame(
            [
                'postGenerateSchema',
            ],
            $eventSubscriber->getSubscribedEvents()
        );
    }

    /**
     * @test
     */
    public function postGenerateSchemaDoesNotCreateNamespace(): void
    {
        $schema = $this->createMock(Schema::class);
        $schema->expects(self::once())->method('hasNamespace')->with('public')->willReturn(true);
        $schema->expects(self::never())->method('createNamespace');

        $args = $this->createMock(GenerateSchemaEventArgs::class);
        $args->expects(self::once())
            ->method('getSchema')
            ->willReturn($schema);

        $eventSubscriber = new MigrationEventSubscriber();
        $eventSubscriber->postGenerateSchema($args);
    }

    /**
     * @test
     */
    public function postGenerateSchemaCreatesNamespace(): void
    {
        $schema = $this->createMock(Schema::class);
        $schema->expects(self::once())->method('hasNamespace')->with('public')->willReturn(false);
        $schema->expects(self::once())->method('createNamespace')->with('public');

        $args = $this->createMock(GenerateSchemaEventArgs::class);
        $args->expects(self::once())
            ->method('getSchema')
            ->willReturn($schema);

        $eventSubscriber = new MigrationEventSubscriber();
        $eventSubscriber->postGenerateSchema($args);
    }
}
