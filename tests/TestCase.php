<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests;

use BaseCodeOy\Crate\TestBench\AbstractTestCase;
use BaseCodeOy\Pennant\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @internal
 */
abstract class TestCase extends AbstractTestCase
{
    /**
     * {@inheritDoc}
     */
    #[\Override()]
    protected function getEnvironmentSetUp($app): void
    {
        $app->config->set('app.key', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA');
        $app->config->set('tonic.servers', []);

        $app->config->set('cache.driver', 'array');

        $app->config->set('database.default', 'sqlite');
        $app->config->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app->config->set('mail.driver', 'log');

        $app->config->set('session.driver', 'array');

        $app->useStoragePath(\realpath(__DIR__.'/storage'));

        Schema::create('users', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->string('name');
            $blueprint->boolean('is_high_traffic_customer')->default(false);
            $blueprint->timestamps();
        });

        Schema::create('feature_flags', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->string('name');
            $blueprint->text('description')->nullable();
            $blueprint->string('state');
            $blueprint->timestamp('expires_at')->nullable();
            $blueprint->timestamps();
        });

        Schema::create('feature_flag_groups', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->string('name');
            $blueprint->text('description')->nullable();
            $blueprint->string('state');
            $blueprint->timestamp('expires_at')->nullable();
            $blueprint->timestamps();
        });

        Schema::create('feature_flag_group_members', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->foreignUlid('feature_flag_group_id')->constrained()->cascadeOnDelete();
            $blueprint->ulidMorphs('model');
            $blueprint->timestamps();
        });

        Schema::create('model_has_feature_flags', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignUlid('feature_flag_id')->constrained()->cascadeOnDelete();
            $blueprint->ulidMorphs('model');
            $blueprint->timestamps();

            $blueprint->unique(['feature_flag_id', 'model_type', 'model_id']);
        });

        Schema::create('model_has_feature_flag_groups', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->foreignUlid('feature_flag_group_id')->constrained()->cascadeOnDelete();
            $blueprint->ulidMorphs('model');
            $blueprint->timestamps();

            $blueprint->unique(['feature_flag_group_id', 'model_type', 'model_id']);
        });
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application                            $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    #[\Override()]
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}
