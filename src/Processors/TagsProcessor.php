<?php
namespace Librette\Setup\Processors;

use Librette\Setup\IProcessor;
use Nette\DI\ServiceDefinition;
use Nette\Object;

/**
 * @author David Matejka
 */
class TagsProcessor extends Object implements IProcessor
{


	public function process(ServiceDefinition $definition, $tags)
	{
		if (is_string($tags)) {
			$tags = array($tags);
		}

		$currentTags = $definition->getTags();
		foreach ((array) $tags as $tag => $attrs) {
			if (is_int($tag) && is_string($attrs)) {
				$tag = $attrs;
				$attrs = TRUE;
			}
			if (!isset($currentTags[$tag])) {
				$definition->addTag($tag, $attrs);
			}
		}
	}

}
