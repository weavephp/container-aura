<?php
/**
 * Weave Aura DIC core config.
 */
namespace Weave\Container\Aura;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

/**
 * Setup the config for a small number of injection dependencies needed by Weave.
 */
class WeaveConfig extends ContainerConfig
{
	/**
	 * A callable to provide pipelines consumable by the chosen Middleware Adaptor.
	 *
	 * @var callable
	 */
	protected $pipelineProvider;

	/**
	 * A callable to configure the routes for the chosen Router Adaptor.
	 *
	 * @var callable
	 */
	protected $routeProvider;

	/**
	 * Constructor.
	 *
	 * @param callable $pipelineProvider The pipeline provider callable.
	 * @param callable $routeProvider    The route provider callable.
	 */
	public function __construct($pipelineProvider, $routeProvider)
	{
		$this->pipelineProvider = $pipelineProvider;
		$this->routeProvider = $routeProvider;
	}

	/**
	 *
	 * Define params, setters, and services before the Container is locked.
	 *
	 * @param Container $container The DI container.
	 *
	 * @return null
	 */
	public function define(Container $container): void
	{
		$container->set(
			'instantiator',
			function () use ($container) {
				return function ($name) use ($container) {
					return $container->newInstance($name);
				};
			}
		);

		// Pipeline provider is a service so it can be unit tested.
		$container->set(
			'pipelineProvider',
			function () {
				return $this->pipelineProvider;
			}
		);

		// Route provider is a service so it can be unit tested.
		$container->set(
			'routeProvider',
			function () {
				return $this->routeProvider;
			}
		);

		$container->types[\Weave\Resolve\ResolveAdaptorInterface::class] = $container->lazyNew(
			\Weave\Resolve\Resolve::class
		);

		$container->types[\Weave\Dispatch\DispatchAdaptorInterface::class] = $container->lazyNew(
			\Weave\Dispatch\Dispatch::class
		);

		$container->params[\Weave\Resolve\Resolve::class] = [
			'instantiator' => $container->lazyGet('instantiator')
		];

		$container->params[\Weave\Middleware\Middleware::class] = [
			'pipelineProvider' => $container->lazyGet('pipelineProvider')
		];

		$container->params[\Weave\Router\Router::class] = [
			'routeProvider' => $container->lazyGet('routeProvider')
		];
	}
}
