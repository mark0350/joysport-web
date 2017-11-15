<?php
namespace JOYSPORT;

use Closure;

/**
 * Created by PhpStorm.
 * User: markhe
 * Date: 2017/8/9
 * Time: 上午9:19
 */
class Container {

	protected $binds;
	protected $instances;

	function bind($abstract, $concrete  ) {
		if ($concrete instanceof Closure) {
			$this->binds[$abstract] = $concrete;
		} else {
			$this->instances[$abstract] = $concrete;
		}
	}

	function make($abstract,$parameter=[]) {
		if(!is_array($parameter)){
			$parameter = (array)$parameter;
		}
		if(isset($this->binds[$abstract])){
			array_unshift($parameter,$this);
			return call_user_func_array($this->binds[$abstract], $parameter);
		}else{
			return $this->instances[$abstract];
		}
	}
}