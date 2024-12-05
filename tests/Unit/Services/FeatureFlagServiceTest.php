<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use BaseCodeOy\Pennant\Models\FeatureFlagGroup;
use BaseCodeOy\Pennant\Services\FeatureFlagService;
use Tests\Fixtures\Features\NewFeature;
use Tests\Models\User;

covers(FeatureFlagGroup::class);

it('can resolve a feature from a custom resolver closure', function (): void {
    /** @var User $user */
    $user = User::create([
        'name' => 'John Doe',
        'is_high_traffic_customer' => true,
    ]);

    $featureFlagService = FeatureFlagService::for($user);
    $featureFlagService->define('new-feature', fn (User $user): bool => $user->is_high_traffic_customer);

    expect($featureFlagService->active('new-feature'))->toBeTrue();
});

it('can resolve a feature from a custom resolver class', function (): void {
    /** @var User $user */
    $user = User::create([
        'name' => 'John Doe',
        'is_high_traffic_customer' => true,
    ]);

    $featureFlagService = FeatureFlagService::for($user);
    $featureFlagService->define('new-feature', NewFeature::class);

    expect($featureFlagService->active('new-feature'))->toBeTrue();
});

it('can resolve a feature from a custom resolver class that was discovered', function (): void {
    /** @var User $user */
    $user = User::create([
        'name' => 'John Doe',
        'is_high_traffic_customer' => true,
    ]);

    $featureFlagService = FeatureFlagService::for($user);
    $featureFlagService->discover(
        'Tests\\Fixtures\\Features',
        __DIR__.'/../../Fixtures/Features',
    );

    expect($featureFlagService->active('new-feature'))->toBeTrue();
});
