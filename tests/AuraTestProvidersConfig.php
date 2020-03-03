<?php
/**
 * Weave Aura DIC Adaptor.
 */
namespace Weave\Container\Aura;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

/**
 * Config for Aura used during unit testing.
 */
class AuraTestProvidersConfig extends ContainerConfig
{
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
		$container->params[AuraTestProviders::class] = [
			'pipelineProvider' => $container->lazyGet('pipelineProvider'),
			'routeProvider' => $container->lazyGet('routeProvider')
		];
	}
}
