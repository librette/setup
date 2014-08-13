<?php
namespace LibretteTests\Setup;

use Librette;
use Nette;
use Tester;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Matějka
 */
class TagProcessorTestCase extends CompilerExtensionTestCase
{

	public function setUp()
	{
	}


	/**
	 * @param array|string
	 * @param array
	 * @dataProvider loadData
	 */
	public function testBasic($tags, $expected)
	{
		$extension = $this->createExtension(array(
			array(
				'type' => 'LibretteTests\Setup\Foo',
				'tags' => $tags,
			)
		));
		$builder = $extension->getContainerBuilder();

		$extension->beforeCompile();
		$tags = $builder->getDefinition('foo')->getTags();
		Tester\Assert::count(count($expected), $tags);
		Tester\Assert::equal($expected, $tags);
	}


	public function loadData()
	{
		return
			array(
				array(
					'foo',
					array('foo' => TRUE)
				),
				array(
					array('foo', 'bar' => 'attr'),
					array('foo' => TRUE, 'bar' => 'attr'),
				),

			);
	}
}


\run(new TagProcessorTestCase());
