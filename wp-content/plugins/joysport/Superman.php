<?php
namespace JOYSPORT;
/**
 * Created by PhpStorm.
 * User: markhe
 * Date: 2017/8/9
 * Time: 上午9:16
 */
class Superman {
	public $module;

	function __construct(SuperModuleInterface $module) {
		$this->module = $module;
	}
}

interface SuperModuleInterface
{
	/**
	 * 超能力激活方法
	 *
	 * 任何一个超能力都得有该方法，并拥有一个参数
	 *@param array $target 针对目标，可以是一个或多个，自己或他人
	 */
	public function activate(array $target);
}

/**
 * X-超能量
 */
class XPower implements SuperModuleInterface
{
	public function activate(array $target)
	{
		$targets = implode(',',$target);
		echo 'Xpower hit '.$targets;
		die();
		// 这只是个例子。。具体自行脑补
	}
}

/**
 * 终极炸弹 （就这么俗）
 */
class UltraBomb implements SuperModuleInterface
{
	public function activate(array $target)
	{
		// 这只是个例子。。具体自行脑补
	}
}