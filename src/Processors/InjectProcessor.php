<?php
namespace Librette\Setup\Processors;

use Librette\Setup\IProcessor;
use Nette\DI\ServiceDefinition;
use Nette\Object;

/**
 * @author David Matejka
 */
class InjectProcessor extends Object implements IProcessor
{


	public function process(ServiceDefinition $definition, $args)
	{
		if ($args === TRUE) {
			$definition->setInject(TRUE);
		}
	}

}
