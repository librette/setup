<?php
namespace LibretteTests\Setup;

use Librette;
use Nette;
use Tester;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/mocks.php';


/**
 * @author David Matějka
 */
class ConfiguratorTestCase extends Tester\TestCase
{

	public function setUp()
	{
	}


	public function testConfigurator()
	{
		$configurator = new Nette\Configurator();
		$configurator->defaultExtensions = array_intersect_key($configurator->defaultExtensions, array('extensions' => TRUE));
		$configurator->autowireExcludedClasses = array();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/config/basic.neon');

		/** @var Nette\DI\Container $container */
		$container = $configurator->createContainer();
		/** @var Bar $bar */
		$bar = $container->getService('bar');
		Tester\Assert::same($container->getService('foo'), $bar->foo);
		Tester\Assert::same($container->getService('lorem'), $bar->lorem);
	}
}


\run(new ConfiguratorTestCase());
