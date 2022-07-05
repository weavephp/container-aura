<?php

declare(strict_types = 1);

/**
 * Weave Aura DIC Adaptor.
 */
namespace Weave\Container\Aura;

/**
 * Test class using the Aura trait so we can unit test the trait.
 *
 * The methods are setup to return values used in the unit testing.
 */
class AuraTestClass
{
	use Aura {
		loadContainer as public;
	}

	public $routeConfig = '';

	protected function provideMiddlewarePipeline(?string $pipelineName = null): mixed
	{
		return $pipelineName . 'Foo';
	}

	protected function provideRouteConfiguration(mixed $router): void
	{
		$this->routeConfig = $router . 'Ping';
	}

	protected function provideContainerConfigs(array $config = [], ?string $environment = null): array
	{
		return [AuraTestProvidersConfig::class];
	}
}
