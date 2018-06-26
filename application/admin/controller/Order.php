<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
class Order extends Controller
{
	public function index(){
		return $this->fetch();
	}
}