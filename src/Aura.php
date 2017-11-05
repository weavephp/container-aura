<?php
declare(strict_types = 1);
/**
 * Weave Aura DIC Adaptor.
 */
namespace Weave\Container\Aura;

use Aura\Di\ContainerBuilder;

/**
 * Weave Aura DIC Adaptor.
 */
trait Aura
{
	use \Weave\Container\Container;

	/**
	 * The Container instance.
	 *
	 * @var \Aura\Di\Container
	 */
	protected $container;

	/**
	 * Setup the Dependency Injection Container
	 *
	 * @param array  $config      Optional config array as provided from loadConfig().
	 * @param string $environment Optional indication of the runtime environment.
	 *
	 * @return callable A callable that can instantiate instances of classes from the DIC.
	 */
	protected function loadContainer(array $config = [], $environment = null)
	{
		$containerConfigs = $this->provideContainerConfigs($config, $environment);
		array_unshift(
			$containerConfigs,
			new WeaveConfig(
				function ($pipelineName) {
					return $this->provideMiddlewarePipeline($pipelineName);
				},
				function ($router) {
					return $this->provideRouteConfiguration($router);
				}
			)
		);
		$this->container = (new ContainerBuilder)->newConfiguredInstance(
			$containerConfigs,
			ContainerBuilder::AUTO_RESOLVE
		);
		return $this->container->get('instantiator');
	}

	/**
	 * Returns an array of class strings and class instances for configuring the Aura DIC.
	 *
	 * @param array  $config      Optional config array as provided from loadConfig().
	 * @param string $environment Optional indication of the runtime environment.
	 *
	 * @return array
	 */
	abstract protected function provideContainerConfigs(array $config = [], $environment = null);
}
