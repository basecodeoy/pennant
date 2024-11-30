<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Services;

use BaseCodeOy\Pennant\Contracts\HasFeatureFlagsInterface;
use BaseCodeOy\Pennant\Repositories\FeatureFlagRepository;

final readonly class FeatureFlagService
{
    public function for(HasFeatureFlagsInterface $hasFeatureFlags): FeatureFlagRepository
    {
        return new FeatureFlagRepository($hasFeatureFlags);
    }
}
