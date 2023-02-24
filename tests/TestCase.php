<?php

namespace Mamitech\ScoutApmLaravelExtended\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mamitech\ScoutApmLaravelExtended\ScoutApmLaravelExtendedServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Mamitech\\ScoutApmLaravelExtended\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ScoutApmLaravelExtendedServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_scout-apm-laravel-extended_table.php.stub';
        $migration->up();
        */
    }
}
