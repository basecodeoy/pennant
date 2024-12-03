<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Models;

use BaseCodeOy\Pennant\Builders\FeatureFlagGroupBuilder;
use BaseCodeOy\Pennant\Concerns\HasFeatureFlags;
use BaseCodeOy\Pennant\Contracts\HasFeatureFlagsInterface;
use BaseCodeOy\Pennant\Database\Factories\FeatureFlagGroupFactory;
use BaseCodeOy\Pennant\States\AbstractState;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\ModelStates\HasStates;

final class FeatureFlagGroup extends Model implements HasFeatureFlagsInterface
{
    use HasFactory;
    use HasFeatureFlags;
    use HasStates;
    use HasUlids;

    public $guarded = ['id', 'created_at', 'updated_at'];

    public static function newFactory(): Factory
    {
        return FeatureFlagGroupFactory::new();
    }

    public function members(): HasMany
    {
        return $this->hasMany(FeatureFlagGroupMember::class);
    }

    #[\Override()]
    public function newEloquentBuilder($query): FeatureFlagGroupBuilder
    {
        return new FeatureFlagGroupBuilder($query);
    }

    #[\Override()]
    protected function casts(): array
    {
        return [
            'state' => AbstractState::class,
        ];
    }
}
