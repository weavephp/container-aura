<?php

declare(strict_types = 1);

/**
 * Weave Aura DIC Adaptor.
 */
namespace Weave\Container\Aura;

use PHPUnit\Framework\TestCase;

class AuraTest extends TestCase
{
	/**
	 * Test calling the loadContainer method.
	 *
	 * Test the loadContainer method calls the provideContainerConfigs,
	 * passing in config and environment and returning a closure.
	 *
	 * @return null
	 */
	public function testContainerInstance()
	{
		$instance = $this->getMockBuilder(AuraTestClass::class)
		->setMethods(['provideContainerConfigs'])
		->getMock();

		$instance->expects($this->once())
		->method('provideContainerConfigs')
		->with(
			$this->equalTo(['hello', 'world']),
			$this->equalTo('foo')
		)
		->willReturn([]);

		$instantiator = $instance->loadContainer(['hello', 'world'], 'foo');

		$this->assertInstanceOf(\Closure::class, $instantiator);
	}

	/**
	 * Test using the instantiator closeure and providers.
	 *
	 * Test the provided instantiator closure works and the
	 * pipeline and route provider closures work.
	 */
	public function testProviders()
	{
		$instance = new AuraTestClass();

		$instantiator = $instance->loadContainer();
		$providerTestInstance = $instantiator(AuraTestProviders::class);

		$this->assertInstanceOf(AuraTestProviders::class, $providerTestInstance);
		$this->assertInstanceOf(\Closure::class, $providerTestInstance->pipelineProvider);
		$this->assertInstanceOf(\Closure::class, $providerTestInstance->routeProvider);

		$pipelineProvider = $providerTestInstance->pipelineProvider;
		$this->assertEquals('WibbleFoo', $pipelineProvider('Wibble'));

		$routeProvider = $providerTestInstance->routeProvider;
		$routeProvider('Wibble');
		$this->assertEquals('WibblePing', $instance->routeConfig);
	}
}
