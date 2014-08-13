<?php
namespace Librette\Setup\Processors;

use Librette\Setup\IProcessor;
use Nette\DI\ServiceDefinition;
use Nette\DI\Statement;
use Nette\Object;
use Nette\Utils\Validators;

/**
 * @author David Matejka
 */
class SetupProcessor extends Object implements IProcessor
{


	public function process(ServiceDefinition $definition, $methods)
	{
		$currentSetupMethods = array_map(function (Statement $statement) {
			return $statement->getEntity();
		}, $definition->getSetup());
		Validators::assert($methods, 'array');
		foreach ($methods as $setup) {
			$method = $setup;
			$args = array();
			if ($method instanceof Statement) {
				$args = $method->arguments;
				$method = $method->getEntity();
			}
			if (!in_array($method, $currentSetupMethods)) {
				$definition->addSetup($method, $args);
			}
		}
	}

}
