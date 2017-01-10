<?php


namespace core;


class View
{
	public $loader;

	protected $twig;

	public $tmp_dir;

	protected $twig_set = [];

	public function __construct($tmp_dir = null)
	{

		if (defined('TEMPLATE_DIR') && !isset($tmp_dir))
		{
			$this->tmp_dir = TEMPLATE_DIR;
		} else {
			$this->tmp_dir = $tmp_dir;
		}

		$this->loader = new \Twig_Loader_Filesystem($this->tmp_dir);
		$this->twig = new \Twig_Environment($this->loader, $this->twig_set);
	}

	public function render($template, array $context = [])
	{
		$this->twig->display($template, $context);
	}

	/**
	 * @return array
	 */
	public function getTwigSet(): array
	{
		return $this->twig_set;
	}

	/**
	 * @param array $twig_set
	 */
	public function setTwigSet(array $twig_set)
	{
		$this->twig_set = $twig_set;
	}
}
