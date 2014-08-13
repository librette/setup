<?php
namespace Librette\Setup;

use Nette\DI\CompilerExtension;
use Nette\DI\ServiceDefinition;
use Nette\Reflection;


/**
 * @author David Matejka
 */
class SetupExtension extends CompilerExtension
{

	public $defaults = array(
		'processors' => array(
			'setup'  => 'Librette\Setup\Processors\SetupProcessor',
			'inject' => 'Librette\Setup\Processors\InjectProcessor',
			'tags'   => 'Librette\Setup\Processors\TagsProcessor',
		),
		'targets'    => array(),
	);


	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);
		$processors = $config['processors'];
		/** @var IProcessor[] $initializedProcessors */
		$initializedProcessors = array();
		$targets = $this->getTargets($config);
		/** @var ServiceDefinition $definition */
		foreach ($builder->getDefinitions() as $definition) {
			if (!($class = $this->detectClass($definition))) {
				continue;
			}
			$types = self::getAllClassTypes($class);
			foreach ($targets as $target) {
				if (count(array_intersect($target['type'], $types)) === 0) {
					continue;
				}
				unset($target['type']);
				foreach ($target as $processor => $args) {
					if (is_int($processor)) {
						$processor = $args;
						$args = TRUE;
					}
					if (!isset($processors[$processor])) {
						throw new \RuntimeException("Setup processor \"{$processor}\" not exists.");
					}
					if (!isset($initializedProcessors[$processor])) {
						$initializedProcessors[$processor] = new $processors[$processor]($builder);
					}
					$initializedProcessors[$processor]->process($definition, $args);
				}
			}
		}

	}


	/**
	 * @param ServiceDefinition
	 * @return string|null
	 */
	protected static function detectClass(ServiceDefinition $def)
	{
		if ($def->getClass()) {
			return $def->getClass();
		} elseif ($interface = $def->getImplement()) {
			$rc = Reflection\ClassType::from($interface);
			$method = $rc->hasMethod('create') ? 'create' : ($rc->hasMethod('get') ? 'get' : NULL);
			if ($method === NULL) {
				return NULL;
			}
			if (!($returnType = $rc->getMethod($method)->getAnnotation('return'))) {
				return NULL;
			}

			return Reflection\AnnotationsParser::expandClassName(preg_replace('#[|\s].*#', '', $returnType), $rc);
		}

		return NULL;
	}


	/**
	 * @param string
	 * @return array
	 */
	protected static function getAllClassTypes($class)
	{
		$rc = Reflection\ClassType::from($class);
		$classTypes = array_merge(array($class), class_parents($class), class_implements($class));
		if(PHP_VERSION_ID >= 50400) {
			do {
				$classTypes = array_merge($classTypes, $rc->getTraitNames());
			} while ($rc = $rc->getParentClass());
		}

		return array_map(function ($val) {
			return ltrim(strtolower($val), '\\');
		}, $classTypes);
	}


	/**
	 * @param array
	 * @return array
	 */
	protected function getTargets($config)
	{
		$targets = $config['targets'];
		unset($config['processors'], $config['targets']);
		$targets = array_merge($targets, $config);
		foreach ($targets as &$target) {
			$target['type'] = array_map(function ($val) {
				return ltrim(strtolower($val), '\\');
			}, (array) $target['type']);
		}

		return $targets;
	}
}
