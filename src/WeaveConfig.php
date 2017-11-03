<?php
declare(strict_types = 1);
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
	protected $_pipelineProvider;

	/**
	 * A callable to configure the routes for the chosen Router Adaptor.
	 *
	 * @var callable
	 */
	protected $_routeProvider;

	/**
	 * Constructor.
	 *
	 * @param callable $pipelineProvider The pipeline provider callable.
	 * @param callable $routeProvider    The route provider callable.
	 */
	public function __construct($pipelineProvider, $routeProvider)
	{
		$this->_pipelineProvider = $pipelineProvider;
		$this->_routeProvider = $routeProvider;
	}

	/**
	 *
	 * Define params, setters, and services before the Container is locked.
	 *
	 * @param Container $container The DI container.
	 *
	 * @return null
	 */
	public function define(Container $container)
	{
		$container->set(
			'instantiator',
			function () use ($container) {
				return function ($name) use ($container) {
					return $container->newInstance($name);
				};
			}
		);

		$container->params[\Weave\Middleware\Middleware::class] = [
			'pipelineProvider' => $this->_pipelineProvider,
			'resolver' => $container->lazyGet('instantiator')
		];

		$container->params[\Weave\Middleware\Dispatch::class] = [
			'resolver' => $container->lazyGet('instantiator')
		];

		$container->params[\Weave\Router\Router::class] = [
			'routeProvider' => $this->_routeProvider,
			'resolver' => $container->lazyGet('instantiator')
		];
	}
}