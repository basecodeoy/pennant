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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final readonly class FeatureFlagRepository
{
    public function __construct(
        private HasFeatureFlagsInterface $hasFeatureFlags,
    ) {}

    public function all(?array $features = null): Collection
    {
        $query = $this->hasFeatureFlags->featureFlags()->active();

        if (\is_array($features)) {
            foreach ($features as $feature) {
                $feature = str($feature)->snake();
            }

            $query->whereIn('name', $features);
        }

        return $query->get();
    }

    public function allByGroup(FeatureFlagGroup $featureFlagGroup, ?array $features = null): Collection
    {
        $query = $featureFlagGroup->featureFlags()->active();

        if (\is_array($features)) {
            foreach ($features as $feature) {
                $feature = str($feature)->snake();
            }

            $query->whereIn('name', $features);
        }

        return $query->get();
    }

    public function allByGroups(?array $features = null): Collection
    {
        $morphToMany = $this->hasFeatureFlags->featureFlagsGroups();

        if (\is_array($features)) {
            foreach ($features as $feature) {
                $feature = str($feature)->snake();
            }

            $this->hasFeatureFlags->featureFlagsGroups()->whereHas('featureFlags', function (Builder $builder) use ($features): void {
                $builder->whereIn('name', $features);
            });
        }

        return $morphToMany->get();
    }

    public function active(string $feature): bool
    {
        return $this->all([$feature])->isNotEmpty();
    }

    public function activeThroughGroup(string $feature, FeatureFlagGroup $featureFlagGroup): bool
    {
        return $this->allByGroup($featureFlagGroup, [$feature])->isNotEmpty();
    }

    public function activeThroughAnyGroup(string $feature): bool
    {
        return $this->allByGroups([$feature])->isNotEmpty();
    }

    public function allAreActive(array $features): bool
    {
        return $this->all($features)->count() === \count($features);
    }

    public function allAreActiveThroughGroup(array $features, FeatureFlagGroup $featureFlagGroup): bool
    {
        return $this->allByGroup($featureFlagGroup, $features)->count() === \count($features);
    }

    public function allAreActiveThroughAnyGroup(array $features): bool
    {
        return $this->allByGroups($features)->count() === \count($features);
    }

    public function someAreActive(array $features): bool
    {
        return $this->all($features)->isNotEmpty();
    }

    public function someAreActiveThroughGroup(array $features, FeatureFlagGroup $featureFlagGroup): bool
    {
        return $this->allByGroup($featureFlagGroup, $features)->isNotEmpty();
    }

    public function someAreActiveThroughAnyGroup(array $features): bool
    {
        return $this->allByGroups($features)->isNotEmpty();
    }

    public function inactive(string $feature): bool
    {
        return !$this->active($feature);
    }

    public function inactiveThroughGroup(string $feature, FeatureFlagGroup $featureFlagGroup): bool
    {
        return !$this->activeThroughGroup($feature, $featureFlagGroup);
    }

    public function inactiveThroughAnyGroup(string $feature): bool
    {
        return !$this->activeThroughAnyGroup($feature);
    }

    public function allAreInactive(array $features): bool
    {
        return !$this->someAreActive($features);
    }

    public function allAreInactiveThroughGroup(array $features, FeatureFlagGroup $featureFlagGroup): bool
    {
        return !$this->someAreActiveThroughGroup($features, $featureFlagGroup);
    }

    public function allAreInactiveThroughAnyGroup(array $features): bool
    {
        return !$this->someAreActiveThroughAnyGroup($features);
    }

    public function someAreInactive(array $features): bool
    {
        return !$this->allAreActive($features);
    }

    public function someAreInactiveThroughGroup(array $features, FeatureFlagGroup $featureFlagGroup): bool
    {
        return !$this->allAreActiveThroughGroup($features, $featureFlagGroup);
    }

    public function someAreInactiveThroughAnyGroup(array $features): bool
    {
        return !$this->allAreActiveThroughAnyGroup($features);
    }

    public function activate(...$features): self
    {
        $this->hasFeatureFlags->featureFlags()->attach($features);

        return $this;
    }

    public function deactivate(...$features): self
    {
        $this->hasFeatureFlags->featureFlags()->detach($features);

        return $this;
    }

    public function activateForEveryone(): self
    {
        throw new \RuntimeException('Not implemented');
    }

    public function deactivateForEveryone(): self
    {
        throw new \RuntimeException('Not implemented');
    }

    public function purge(): self
    {
        throw new \RuntimeException('Not implemented');
    }
}
