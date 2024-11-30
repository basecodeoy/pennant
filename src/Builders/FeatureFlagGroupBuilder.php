<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Builders;

use BaseCodeOy\Pennant\States\Abandoned;
use BaseCodeOy\Pennant\States\Active;
use BaseCodeOy\Pennant\States\Paused;
use Illuminate\Database\Eloquent\Builder;

final class FeatureFlagGroupBuilder extends Builder
{
    public function active(): self
    {
        $this->whereState('state', Active::class);

        return $this;
    }

    public function paused(): self
    {
        $this->whereState('state', Paused::class);

        return $this;
    }

    public function abandoned(): self
    {
        $this->whereState('state', Abandoned::class);

        return $this;
    }
}
