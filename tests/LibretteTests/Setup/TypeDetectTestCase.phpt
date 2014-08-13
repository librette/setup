<?php
namespace LibretteTests\Setup;

use Librette;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Matějka
 */
class TypeDetectTestCase extends CompilerExtensionTestCase
{

	public function setUp()
	{
	}


	/**
	 * @param array
	 * @param array
	 * @dataProvider loadData
	 */
	public function testBasic($config, $expected)
	{
		$extension = $this->createExtension($config);
		$builder = $extension->getContainerBuilder();
		Assert::count(0, TestProcessor::$passed);
		$extension->beforeCompile();
		Assert::count(count($expected), TestProcessor::$passed);
		foreach ($expected as &$value) {
			$value[0] = $builder->getDefinition($value[0]);
		}
		Assert::equal($expected, TestProcessor::$passed);
		TestProcessor::$passed = array();
	}


	public function testFactory()
	{
		$extension = $this->createExtension(array(array('type' => 'LibretteTests\Setup\Bar', 'test' => 'target')));
		$builder = $extension->getContainerBuilder();
		$builder->removeDefinition('foo');
		$builder->removeDefinition('bar');
		$builder->addDefinition('barFactory')->setImplement('LibretteTests\Setup\BarFactory');
		Assert::count(0, TestProcessor::$passed);
		$extension->beforeCompile();
		Assert::count(1, TestProcessor::$passed);
		Assert::equal(array($builder->getDefinition('barFactory'), 'target'), TestProcessor::$passed[0]);
	}


	protected function loadData()
	{
		$data = array();
		$data[] = array(
			array(
				array('type' => 'LibretteTests\Setup\Foo', 'test' => 'target1'),
				array('type' => 'LibretteTests\Setup\Bar', 'test' => 'target2'),
			),
			array(array('foo', 'target1'), array('bar', 'target1'), array('bar', 'target2')),
		);
		$data[] = array(
			array(
				array('type' => 'LibretteTests\Setup\FooInterface', 'test' => 'target1'),
			),
			array(array('foo', 'target1'), array('bar', 'target1')),
		);
		if (PHP_VERSION_ID >= 50400) {
			$data[] = array(
				array(
					array('type' => 'LibretteTests\Setup\FooTrait', 'test' => 'target1'),
				),
				array(array('foo', 'target1'), array('bar', 'target1')),
			);
		}

		return $data;
	}
}


\run(new TypeDetectTestCase());
