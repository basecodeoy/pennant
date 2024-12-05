<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Pennant\Services;

use BaseCodeOy\Pennant\Contracts\HasFeatureFlagsInterface;
use BaseCodeOy\Pennant\Models\FeatureFlagGroup;
use BaseCodeOy\Pennant\Repositories\FeatureFlagRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Symfony\Component\Finder\Finder;

/**
 * @method static self       activate(...$features)
 * @method static bool       active(string $feature)
 * @method static bool       activeThroughAnyGroup(string $feature)
 * @method static bool       activeThroughGroup(string $feature, FeatureFlagGroup $featureFlagGroup)
 * @method static Collection all(?array $features = null)
 * @method static bool       allAreActive(array $features)
 * @method static bool       allAreActiveThroughAnyGroup(array $features)
 * @method static bool       allAreActiveThroughGroup(array $features, FeatureFlagGroup $featureFlagGroup)
 * @method static bool       allAreInactive(array $features)
 * @method static bool       allAreInactiveThroughAnyGroup(array $features)
 * @method static bool       allAreInactiveThroughGroup(array $features, FeatureFlagGroup $featureFlagGroup)
 * @method static Collection allByGroup(FeatureFlagGroup $featureFlagGroup, ?array $features = null)
 * @method static Collection allByGroups(?array $features = null)
 * @method static self       deactivate(...$features)
 * @method static bool       inactive(string $feature)
 * @method static bool       inactiveThroughAnyGroup(string $feature)
 * @method static bool       inactiveThroughGroup(string $feature, FeatureFlagGroup $featureFlagGroup)
 * @method static bool       someAreActive(array $features)
 * @method static bool       someAreActiveThroughAnyGroup(array $features)
 * @method static bool       someAreActiveThroughGroup(array $features, FeatureFlagGroup $featureFlagGroup)
 * @method static bool       someAreInactive(array $features)
 * @method static bool       someAreInactiveThroughAnyGroup(array $features)
 * @method static bool       someAreInactiveThroughGroup(array $features, FeatureFlagGroup $featureFlagGroup)
 */
final class FeatureFlagService
{
    use ForwardsCalls;

    private array $featureStateResolvers = [];

    private readonly FeatureFlagRepository $featureFlagRepository;

    private function __construct(
        private readonly HasFeatureFlagsInterface $hasFeatureFlags,
    ) {
        $this->featureFlagRepository = new FeatureFlagRepository($hasFeatureFlags);
    }

    public function __call(string $method, array $arguments): mixed
    {
        $feature = $arguments[0] ?? null;

        if ($this->hasResolver($feature)) {
            $resolver = $this->featureStateResolvers[$feature];

            if ($resolver instanceof \Closure) {
                return $resolver($this->hasFeatureFlags);
            }

            return $resolver->resolve($this->hasFeatureFlags);
        }

        return \call_user_func_array([$this->featureFlagRepository, $method], $arguments);
    }

    public static function for(HasFeatureFlagsInterface $hasFeatureFlags): self
    {
        return new self($hasFeatureFlags);
    }

    public function discover(string $namespace = 'App\\Features', ?string $path = null): void
    {
        $namespace = Str::finish($namespace, '\\');

        $features = Collection::make((new Finder())
            ->files()
            ->name('*.php')
            ->depth(0)
            ->in($path ?? base_path('app/Features')))
            ->map(fn ($file): string => $namespace.$file->getBasename('.php'));

        foreach ($features as $feature) {
            $this->define(App::make($feature)->name(), $feature);
        }
    }

    public function define(string $feature, \Closure|string $resolver): void
    {
        if (\is_string($resolver)) {
            $resolver = new $resolver();
        }

        $this->featureStateResolvers[$feature] = $resolver;
    }

    private function hasResolver(mixed $feature): bool
    {
        return Arr::has($this->featureStateResolvers, Arr::wrap($feature));
    }
}
