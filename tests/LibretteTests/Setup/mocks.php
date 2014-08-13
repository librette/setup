<?php
namespace LibretteTests\Setup;

use Librette;
use Nette\DI\ServiceDefinition;

class TestProcessor implements Librette\Setup\IProcessor
{

	public static $passed = array();


	public function process(ServiceDefinition $definition, $args)
	{
		self::$passed[] = array($definition, $args);
	}
}


interface FooInterface
{

}


if (PHP_VERSION_ID >= 50400) {
	trait FooTrait
	{

	}


	class Foo implements FooInterface
	{

		use FooTrait;
	}

} else {
	class Foo implements FooInterface
	{

	}
}


class Bar extends Foo
{

	/** @var Lorem @inject */
	public $lorem;

	/** @var Lorem */
	public $foo;


	public function setFoo(Foo $foo)
	{
		$this->foo = $foo;
	}
}


interface BarFactory
{

	/**
	 * @return Bar
	 */
	public function create();
}


class Lorem
{

}
