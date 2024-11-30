<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Facades;

use BaseCodeOy\Pennant\Contracts\HasFeatureFlagsInterface;
use BaseCodeOy\Pennant\Repositories\FeatureFlagRepository;
use BaseCodeOy\Pennant\Services\FeatureFlagService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static FeatureFlagRepository for(HasFeatureFlagsInterface $model)
 */
final class FeatureFlag extends Facade
{
    #[\Override()]
    protected static function getFacadeAccessor(): string
    {
        return FeatureFlagService::class;
    }
}
