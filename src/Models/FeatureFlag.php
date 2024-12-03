<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Models;

use BaseCodeOy\Pennant\Builders\FeatureFlagBuilder;
use BaseCodeOy\Pennant\Database\Factories\FeatureFlagFactory;
use BaseCodeOy\Pennant\States\AbstractState;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\ModelStates\HasStates;

/**
 * @method static FeatureFlagBuilder query()
 */
final class FeatureFlag extends Model
{
    use HasFactory;
    use HasStates;
    use HasUlids;

    public $guarded = ['id', 'created_at', 'updated_at'];

    public static function newFactory(): Factory
    {
        return FeatureFlagFactory::new();
    }

    public function models(): MorphToMany
    {
        return $this->morphedByMany(
            related: FeatureFlagGroup::class,
            name: 'model',
            table: 'model_has_feature_flags',
            foreignPivotKey: 'feature_flag_id',
            relatedPivotKey: 'model_id',
        );
    }

    #[\Override()]
    public function newEloquentBuilder($query): FeatureFlagBuilder
    {
        return new FeatureFlagBuilder($query);
    }

    #[\Override()]
    protected function casts(): array
    {
        return [
            'state' => AbstractState::class,
        ];
    }
}
