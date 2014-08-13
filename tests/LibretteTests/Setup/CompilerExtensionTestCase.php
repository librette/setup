<?php
namespace LibretteTests\Setup;

use Librette;
use Nette;
use Tester\TestCase;

require __DIR__ . '/mocks.php';


abstract class CompilerExtensionTestCase extends TestCase
{


	protected function createExtension($targets)
	{
		$config = array(
			'processors' => array(
				'test' => 'LibretteTests\Setup\TestProcessor',
			),
			'targets'    => $targets,
		);
		$compiler = \Mockery::mock('Nette\DI\Compiler');
		$builder = new Nette\DI\ContainerBuilder();
		$compiler->shouldReceive('getContainerBuilder')->andReturn($builder);
		$compiler->shouldReceive('getConfig')->andReturn(array('setup' => $config));
		$builder->addDefinition('foo')->setClass('LibretteTests\Setup\Foo');
		$builder->addDefinition('bar')->setClass('LibretteTests\Setup\Bar');
		$extension = new Librette\Setup\SetupExtension();
		$extension->setCompiler($compiler, 'setup');

		return $extension;

	}

}
