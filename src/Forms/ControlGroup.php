<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Forms;

use Nette;


/**
 * A user group of form controls.
 */
class ControlGroup
{
	use Nette\SmartObject;

	/** @var \SplObjectStorage */
	protected $controls;

	/** @var array user options */
	private $options = [];


	public function __construct()
	{
		$this->controls = new \SplObjectStorage;
	}


	/**
	 * @return static
	 */
	public function add(...$items)
	{
		foreach ($items as $item) {
			if ($item instanceof IControl) {
				$this->controls->attach($item);

			} elseif ($item instanceof Container) {
				foreach ($item->getComponents() as $component) {
					$this->add($component);
				}
			} elseif (is_iterable($item)) {
				$this->add(...$item);

			} else {
				$type = is_object($item) ? get_class($item) : gettype($item);
				throw new Nette\InvalidArgumentException("IControl or Container items expected, $type given.");
			}
		}
		return $this;
	}


	/**
	 * @return IControl[]
	 */
	public function getControls(): array
	{
		return iterator_to_array($this->controls);
	}


	/**
	 * Sets user-specific option.
	 * Options recognized by DefaultFormRenderer
	 * - 'label' - textual or IHtmlString object label
	 * - 'visual' - indicates visual group
	 * - 'container' - container as Html object
	 * - 'description' - textual or IHtmlString object description
	 * - 'embedNext' - describes how render next group
	 *
	 * @return static
	 */
	public function setOption(string $key, $value)
	{
		if ($value === NULL) {
			unset($this->options[$key]);

		} else {
			$this->options[$key] = $value;
		}
		return $this;
	}


	/**
	 * Returns user-specific option.
	 * @return mixed
	 */
	public function getOption(string $key, $default = NULL)
	{
		return $this->options[$key] ?? $default;
	}


	/**
	 * Returns user-specific options.
	 */
	public function getOptions(): array
	{
		return $this->options;
	}

}
