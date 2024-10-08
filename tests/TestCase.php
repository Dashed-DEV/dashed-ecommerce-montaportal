<?php

namespace Dashed\DashedEcommerceMontaportal\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\Eloquent\Factories\Factory;
use Dashed\DashedEcommerceMontaportal\DashedEcommerceMontaportalServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Dashed\\DashedEcommerceMontaportal\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            DashedEcommerceMontaportalServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_dashed-ecommerce-montaportal_table.php.stub';
        $migration->up();
        */
    }
}
