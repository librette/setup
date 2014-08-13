<?php
namespace LibretteTests\Setup;

use Librette;
use Nette;
use Tester;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Matějka
 */
class InjectProcessorTestCase extends CompilerExtensionTestCase
{

	public function setUp()
	{
	}


	public function testInject()
	{
		$extension = $this->createExtension(array(
			array(
				'type'   => 'LibretteTests\Setup\Bar',
				'inject' => TRUE,
			)
		));
		$builder = $extension->getContainerBuilder();
		$extension->beforeCompile();
		Tester\Assert::false($builder->getDefinition('foo')->getInject());
		Tester\Assert::true($builder->getDefinition('bar')->getInject());
	}
}


\run(new InjectProcessorTestCase());
