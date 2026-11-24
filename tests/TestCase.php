<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** The only database tests may use. The real site data lives in `cultivation`. */
    public const TEST_DATABASE = 'cultivation_test';

    public function createApplication()
    {
        $app = parent::createApplication();
        // Before anything can touch the schema: LegacyAdminSchema below, and every database
        // trait (RefreshDatabase, DatabaseTransactions, ...), which Laravel only sets up
        // after createApplication() has returned.
        self::assertTestDatabase($app);
        \Tests\Support\LegacyAdminSchema::ensure($app);
        return $app;
    }

    /**
     * Throw unless the default connection is the local MySQL cultivation_test database, both
     * as configured and as the server reports it. The configured name is checked first, so a
     * misconfigured run never even opens a connection to another database.
     */
    public static function assertTestDatabase($app): void
    {
        $db = $app['db']->connection();
        $configured = $db->getDatabaseName();
        $safe = $app->environment('testing')
            && $db->getDriverName() === 'mysql'
            && in_array($db->getConfig('host'), ['127.0.0.1', 'localhost'], true)
            && $configured === self::TEST_DATABASE
            && $db->selectOne('SELECT DATABASE() AS name')->name === self::TEST_DATABASE;

        if (! $safe) {
            throw new \RuntimeException(sprintf(
                'Refusing to run tests: the database must be the local MySQL "%s", but this run is configured for "%s" (%s on %s, APP_ENV=%s). '
                .'Never run tests, RefreshDatabase or migrate:fresh against the real "cultivation" database.',
                self::TEST_DATABASE, $configured, $db->getDriverName(), $db->getConfig('host'), $app->environment()
            ));
        }
    }
}
