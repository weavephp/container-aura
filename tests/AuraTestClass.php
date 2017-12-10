<?php
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

	protected function provideMiddlewarePipeline($pipelineName = null)
	{
		return $pipelineName . 'Foo';
	}

	protected function provideRouteConfiguration($router)
	{
		return $router . 'Ping';
	}

	protected function provideContainerConfigs(array $config = [], $environment = null)
	{
		return [AuraTestProvidersConfig::class];
	}
}
