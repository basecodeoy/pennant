<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests\Fixtures\Features;

use BaseCodeOy\Pennant\Contracts\HasFeatureFlagsInterface;

final readonly class NewFeature
{
    public function name(): string
    {
        return 'new-feature';
    }

    public function resolve(HasFeatureFlagsInterface $hasFeatureFlags): mixed
    {
        return $hasFeatureFlags->is_high_traffic_customer;
    }
}
