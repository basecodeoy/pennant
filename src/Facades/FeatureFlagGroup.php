<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Facades;

use BaseCodeOy\Pennant\Contracts\HasFeatureFlagsInterface;
use BaseCodeOy\Pennant\Repositories\FeatureFlagGroupRepository;
use BaseCodeOy\Pennant\Services\FeatureFlagGroupService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static FeatureFlagGroupRepository for(HasFeatureFlagsInterface $model)
 */
final class FeatureFlagGroup extends Facade
{
    #[\Override()]
    protected static function getFacadeAccessor(): string
    {
        return FeatureFlagGroupService::class;
    }
}
