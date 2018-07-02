<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
class Order extends Controller
{
	public function index(){
		//获取数据(接口)
		return $this->fetch();
	}

	//删除订单
	public function order_del(){
		$id = $_POST['id'];
		//删除订单
		return array('code' => 1);
	}
}