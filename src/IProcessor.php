<?php
namespace Librette\Setup;

use Nette\DI\ServiceDefinition;

/**
 * @author David Matejka
 */
interface IProcessor
{

	/**
	 * @param ServiceDefinition
	 * @param mixed
	 * @return void
	 */
	public function process(ServiceDefinition $definition, $args);
}
