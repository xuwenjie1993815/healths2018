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
		$data['oderNum'] = $_POST['oderNum'];
		$url = config('path')."/goods/oder/delete";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			return array('code' => 1);
		}else{
			return array('code' => 2,'msg' => $res['message'] );
		}
	}
}