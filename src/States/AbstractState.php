<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class AbstractState extends State
{
    #[\Override()]
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Active::class)
            ->allowTransition(Active::class, Paused::class)
            ->allowTransition(Active::class, Abandoned::class)
            ->allowTransition(Paused::class, Active::class)
            ->allowTransition(Paused::class, Abandoned::class)
            ->allowTransition(Abandoned::class, Active::class)
            ->allowTransition(Abandoned::class, Paused::class);
    }
}
