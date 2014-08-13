<?php
namespace LibretteTests\Setup;

use Librette;
use Nette;
use Tester;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Matějka
 */
class SetupProcessorTestCase extends CompilerExtensionTestCase
{

	public function setUp()
	{
	}


	public function testBasic()
	{
		$extension = $this->createExtension(array(
			array(
				'type'  => 'LibretteTests\Setup\Foo',
				'setup' => array(
					'injectFoo',
					new Nette\DI\Statement('setBar', array('value')),
				),
			)
		));
		$builder = $extension->getContainerBuilder();

		$extension->beforeCompile();
		$setup = $builder->getDefinition('foo')->getSetup();
		Tester\Assert::count(2, $setup);
		Tester\Assert::equal(new Nette\DI\Statement('injectFoo', array()), $setup[0]);
		Tester\Assert::equal(new Nette\DI\Statement('setBar', array('value')), $setup[1]);
	}


	public function testNotRewriting()
	{
		$extension = $this->createExtension(array(
			array(
				'type'  => 'LibretteTests\Setup\Foo',
				'setup' => array(
					new Nette\DI\Statement('setBar', array('value')),
				),
			)
		));
		$builder = $extension->getContainerBuilder();
		$definition = $builder->getDefinition('foo');
		$definition->addSetup('setBar', array('lorem'));

		$extension->beforeCompile();
		$setup = $definition->getSetup();
		Tester\Assert::count(1, $setup);
		Tester\Assert::equal(new Nette\DI\Statement('setBar', array('lorem')), $setup[0]);
	}

}


\run(new SetupProcessorTestCase());
