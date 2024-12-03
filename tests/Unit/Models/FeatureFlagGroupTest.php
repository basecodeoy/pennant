<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use BaseCodeOy\Pennant\Models\FeatureFlagGroup;

covers(FeatureFlagGroup::class);

it('can create a feature flag group', function (): void {
    $model = FeatureFlagGroup::factory()->create();

    expect($model->name)->toBeString();
    expect($model->description)->toBeString();
});
