<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use BaseCodeOy\Pennant\Models\FeatureFlag;
use BaseCodeOy\Pennant\Models\FeatureFlagGroup;
use Tests\Models\User;

covers(FeatureFlagGroup::class);

it('can get all feature flags', function (): void {
    /** @var FeatureFlagGroup $model */
    $model = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    /** @var FeatureFlag $featureFlag2 */
    $featureFlag2 = FeatureFlag::factory()->create();

    $model->features()->activate($featureFlag, $featureFlag2);

    expect($model->features()->all()->count())->toBe(2);

    expect($model->features()->all()->sortBy('id')->first()->name)->toBe($featureFlag->name);
    expect($model->features()->all()->sortBy('id')->first()->description)->toBe($featureFlag->description);
    expect($model->features()->all()->sortBy('id')->first()->state->getValue())->toBe($featureFlag->state->getValue());

    expect($model->features()->all()->sortByDesc('id')->first()->name)->toBe($featureFlag2->name);
    expect($model->features()->all()->sortByDesc('id')->first()->description)->toBe($featureFlag2->description);
    expect($model->features()->all()->sortByDesc('id')->first()->state->getValue())->toBe($featureFlag2->state->getValue());
});

it('can check if a feature flag is active', function (): void {
    /** @var FeatureFlagGroup $model */
    $model = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    $model->features()->activate($featureFlag);

    expect($model->features()->active($featureFlag->name))->toBeTrue();
});

it('can check if all feature flags are active', function (): void {
    /** @var FeatureFlagGroup $model */
    $model = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    /** @var FeatureFlag $featureFlag2 */
    $featureFlag2 = FeatureFlag::factory()->create();

    $model->features()->activate($featureFlag, $featureFlag2);

    expect($model->features()->allAreActive([$featureFlag->name, $featureFlag2->name]))->toBeTrue();
});

it('can check if some feature flags are active', function (): void {
    /** @var FeatureFlagGroup $model */
    $model = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    /** @var FeatureFlag $featureFlag2 */
    $featureFlag2 = FeatureFlag::factory()->create();

    $model->features()->activate($featureFlag);

    expect($model->features()->someAreActive([$featureFlag->name, $featureFlag2->name]))->toBeTrue();
});

it('can check if a feature flag is inactive', function (): void {
    /** @var FeatureFlagGroup $model */
    $model = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    $model->features()->activate($featureFlag);

    expect($model->features()->inactive($featureFlag->name))->toBeFalse();
});

it('can check if all feature flags are inactive', function (): void {
    /** @var FeatureFlagGroup $model */
    $model = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    /** @var FeatureFlag $featureFlag2 */
    $featureFlag2 = FeatureFlag::factory()->create();

    $model->features()->activate($featureFlag);

    expect($model->features()->allAreInactive([$featureFlag->name, $featureFlag2->name]))->toBeFalse();
});

it('can check if some feature flags are inactive', function (): void {
    /** @var FeatureFlagGroup $model */
    $model = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    /** @var FeatureFlag $featureFlag2 */
    $featureFlag2 = FeatureFlag::factory()->create();

    $model->features()->activate($featureFlag);

    expect($model->features()->someAreInactive([$featureFlag->name, $featureFlag2->name]))->toBeTrue();
});

it('can activate a feature flag', function (): void {
    /** @var FeatureFlagGroup $model */
    $model = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    $model->features()->activate($featureFlag);

    expect($model->featureFlags()->count())->toBe(1);
    expect($model->featureFlags()->first()->name)->toBe($featureFlag->name);
    expect($model->featureFlags()->first()->description)->toBe($featureFlag->description);
    expect($model->featureFlags()->first()->state->getValue())->toBe($featureFlag->state->getValue());
});

it('can activate multiple feature flags', function (): void {
    /** @var FeatureFlagGroup $model */
    $model = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    /** @var FeatureFlag $featureFlag2 */
    $featureFlag2 = FeatureFlag::factory()->create();

    $model->features()->activate($featureFlag, $featureFlag2);

    expect($model->featureFlags()->count())->toBe(2);

    expect($model->featureFlags()->orderBy('id')->first()->name)->toBe($featureFlag->name);
    expect($model->featureFlags()->orderBy('id')->first()->description)->toBe($featureFlag->description);
    expect($model->featureFlags()->orderBy('id')->first()->state->getValue())->toBe($featureFlag->state->getValue());

    expect($model->featureFlags()->orderByDesc('id')->first()->name)->toBe($featureFlag2->name);
    expect($model->featureFlags()->orderByDesc('id')->first()->description)->toBe($featureFlag2->description);
    expect($model->featureFlags()->orderByDesc('id')->first()->state->getValue())->toBe($featureFlag2->state->getValue());
});

it('can deactivate a feature flag', function (): void {
    /** @var FeatureFlagGroup $model */
    $model = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    $model->features()->activate($featureFlag);

    expect($model->featureFlags()->count())->toBe(1);
    expect($model->featureFlags()->first()->name)->toBe($featureFlag->name);
    expect($model->featureFlags()->first()->description)->toBe($featureFlag->description);
    expect($model->featureFlags()->first()->state->getValue())->toBe($featureFlag->state->getValue());

    $model->features()->deactivate($featureFlag);

    expect($model->featureFlags()->count())->toBe(0);
});

it('can deactivate multiple feature flags', function (): void {
    /** @var FeatureFlagGroup $model */
    $model = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    /** @var FeatureFlag $featureFlag2 */
    $featureFlag2 = FeatureFlag::factory()->create();

    $model->features()->activate($featureFlag, $featureFlag2);

    expect($model->featureFlags()->count())->toBe(2);

    expect($model->featureFlags()->orderBy('id')->first()->name)->toBe($featureFlag->name);
    expect($model->featureFlags()->orderBy('id')->first()->description)->toBe($featureFlag->description);
    expect($model->featureFlags()->orderBy('id')->first()->state->getValue())->toBe($featureFlag->state->getValue());

    expect($model->featureFlags()->orderByDesc('id')->first()->name)->toBe($featureFlag2->name);
    expect($model->featureFlags()->orderByDesc('id')->first()->description)->toBe($featureFlag2->description);
    expect($model->featureFlags()->orderByDesc('id')->first()->state->getValue())->toBe($featureFlag2->state->getValue());

    $model->features()->deactivate($featureFlag, $featureFlag2);

    expect($model->featureFlags()->count())->toBe(0);
});

it('can check if a feature flag is active through a group', function (): void {
    /** @var FeatureFlagGroup $group */
    $group = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    /** @var User $user */
    $user = User::create([
        'name' => 'John Doe',
    ]);

    $group->features()->activate($featureFlag);
    $group->members()->create([
        'model_id' => $user->id,
        'model_type' => $user->getMorphClass(),
    ]);

    expect($group->features()->active($featureFlag->name))->toBeTrue();
    expect($user->features()->activeThroughGroup($featureFlag->name, $group))->toBeTrue();
});

it('can check if a feature flag is active through any group', function (): void {
    /** @var FeatureFlagGroup $group */
    $group = FeatureFlagGroup::factory()->create();

    /** @var FeatureFlag $featureFlag */
    $featureFlag = FeatureFlag::factory()->create();

    /** @var User $user */
    $user = User::create([
        'name' => 'John Doe',
    ]);

    $group->features()->activate($featureFlag);
    $group->members()->create([
        'model_id' => $user->id,
        'model_type' => $user->getMorphClass(),
    ]);

    expect($group->features()->active($featureFlag->name))->toBeTrue();
    expect($user->features()->activeThroughAnyGroup($featureFlag->name))->toBeTrue();
});
