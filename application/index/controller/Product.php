<?php
namespace app\index\controller;
use think\View;
use think\Controller;
use think\Session;
use app\index\controller\Base;
class Product extends Base
{
	public function product_list(){
		return $this->fetch();
	}
}