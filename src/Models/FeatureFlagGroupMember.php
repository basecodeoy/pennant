<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Models;

use BaseCodeOy\Pennant\Concerns\HasFeatureFlags;
use BaseCodeOy\Pennant\Contracts\HasFeatureFlagsInterface;
use BaseCodeOy\Pennant\Database\Factories\FeatureFlagGroupMemberFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class FeatureFlagGroupMember extends Model implements HasFeatureFlagsInterface
{
    use HasFactory;
    use HasFeatureFlags;
    use HasUlids;

    public $guarded = ['id', 'created_at', 'updated_at'];

    public static function newFactory(): Factory
    {
        return FeatureFlagGroupMemberFactory::new();
    }

    public function featureFlagGroup(): BelongsTo
    {
        return $this->belongsTo(FeatureFlagGroup::class);
    }

    // public function newEloquentBuilder($query): FeatureFlagGroupBuilder
    // {
    //     return new FeatureFlagGroupMemberBuilder($query);
    // }
}
