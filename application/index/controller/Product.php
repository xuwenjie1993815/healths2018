<?php
namespace app\index\controller;
use think\View;
use think\Controller;
use think\Session;
class Product extends Controller
{
	public function product_list(){
		return $this->fetch();
	}
}