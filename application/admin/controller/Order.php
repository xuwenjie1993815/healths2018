<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
use app\admin\controller\Base;
class Order extends Base
{
	public function index(){
		//获取数据(接口)
		$userMsg = session::get('userMsg');
		$url = config('path')."/goods/oder/groupOders?adminId=".$userMsg['id'];
		$res = http_request($url);
		$res = json_decode($res,true);
		//获取订单统计信息
		$statistics_url = config('path')."/goods/oder/statistics?adminId=".$userMsg['id'];
		$statistics_res = http_request($statistics_url);
		$statistics_res = json_decode($statistics_res,true);
		if ($res AND !$res['error']) {
			foreach ($res as $key => $value) {
				$p_url = config('path')."/patient/id/".$value['payUserId'];
				$p_res = http_request($p_url);
				$p_res = json_decode($p_res,true);
				$res[$key]['patientName'] = $p_res['name'];
			}
			var_dump($res);die;
			$this->assign('list',$res);
		}
		if ($statistics_res AND !$statistics_res['error']) {
			$this->assign('statistics',$statistics_res);
		}
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
			return array('code' => 2,'msg' => $res['message']);
		}
	}
}