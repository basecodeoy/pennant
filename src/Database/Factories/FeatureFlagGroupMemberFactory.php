<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Database\Factories;

use BaseCodeOy\Pennant\Models\FeatureFlag;
use BaseCodeOy\Pennant\Models\FeatureFlagGroupMember;
use Illuminate\Database\Eloquent\Factories\Factory;

final class FeatureFlagGroupMemberFactory extends Factory
{
    protected $model = FeatureFlagGroupMember::class;

    #[\Override()]
    public function definition(): array
    {
        return [
            'feature_flag_id' => fn (): Factory => FeatureFlag::factory(),
        ];
    }
}
