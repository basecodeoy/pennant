<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @mixin Model
 */
interface HasFeatureFlagsInterface
{
    public function featureFlags(): MorphToMany;

    // TODO: probably move to a separate interface and trait
    public function featureFlagsGroups(): MorphToMany;
}
