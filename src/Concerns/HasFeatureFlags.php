<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Concerns;

use BaseCodeOy\Pennant\Facades\FeatureFlag as Facade;
use BaseCodeOy\Pennant\Models\FeatureFlag;
use BaseCodeOy\Pennant\Models\FeatureFlagGroup;
use BaseCodeOy\Pennant\Repositories\FeatureFlagRepository;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasFeatureFlags
{
    public function featureFlags(): MorphToMany
    {
        return $this->morphToMany(
            related: FeatureFlag::class,
            name: 'model',
            table: 'model_has_feature_flags',
            foreignPivotKey: 'model_id',
            relatedPivotKey: 'feature_flag_id',
        );
    }

    public function featureFlagsGroups(): MorphToMany
    {
        return $this->morphToMany(
            related: FeatureFlagGroup::class,
            name: 'model',
            table: 'feature_flag_group_members',
            foreignPivotKey: 'model_id',
            relatedPivotKey: 'feature_flag_group_id',
        );
    }

    public function features(): FeatureFlagRepository
    {
        return Facade::for($this);
    }
}
