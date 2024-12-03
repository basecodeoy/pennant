<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests\Models;

use BaseCodeOy\Pennant\Concerns\HasFeatureFlags;
use BaseCodeOy\Pennant\Contracts\HasFeatureFlagsInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

final class User extends Model implements HasFeatureFlagsInterface
{
    use HasFeatureFlags;
    use HasUlids;

    protected $guarded = [];
}
