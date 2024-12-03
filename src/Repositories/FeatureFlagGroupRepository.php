<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Repositories;

use BaseCodeOy\Pennant\Contracts\HasFeatureFlagsInterface;
use BaseCodeOy\Pennant\Models\FeatureFlagGroup;
use Illuminate\Database\Eloquent\Collection;

final readonly class FeatureFlagGroupRepository
{
    public function __construct(
        private HasFeatureFlagsInterface $hasFeatureFlags,
    ) {}

    public function all(?array $groups = null): Collection
    {
        $query = $this->hasFeatureFlags->featureFlagsGroups()->active();

        if (\is_array($groups)) {
            foreach ($groups as $group) {
                $group = str($group)->snake();
            }

            $query->whereIn('name', $groups);
        }

        return $query->get();
    }

    public function join(...$groups): self
    {
        $groups = $this->everything($groups);

        /** @var FeatureFlagGroup $group */
        foreach ($groups as $group) {
            $group->members()->create([
                'model_id' => $this->hasFeatureFlags->id,
                'model_type' => $this->hasFeatureFlags->getMorphClass(),
            ]);
        }

        return $this;
    }

    public function leave(...$groups): self
    {
        $groups = $this->all($groups);

        /** @var FeatureFlagGroup $group */
        foreach ($groups as $group) {
            $group->members()->where([
                'model_id' => $this->hasFeatureFlags->id,
                'model_type' => $this->hasFeatureFlags->getMorphClass(),
            ])->delete();
        }

        return $this;
    }

    public function has(...$groups): bool
    {
        return $this->all($groups)->isNotEmpty();
    }

    private function everything(?array $groups = null): Collection
    {
        $query = FeatureFlagGroup::query()->active();

        if (\is_array($groups)) {
            foreach ($groups as $group) {
                $group = str($group)->snake();
            }

            $query->whereIn('name', $groups);
        }

        return $query->get();
    }
}
