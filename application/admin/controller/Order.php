<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
class Order extends Controller
{
	public function index(){
		//获取数据(接口)
		$userMsg = session::get('userMsg');
		$url = config('path')."/goods/oder/groupOders?groupId=".$userMsg['id'];
		$res = http_request($url);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			foreach ($res as $key => $value) {
				$p_url = config('path')."/patient/id/".$value['payUserId'];
				$p_res = http_request($p_url);
				$p_res = json_decode($p_res,true);
				$res[$key]['patientName'] = $p_res['name'];
			}
			$this->assign('list',$res);
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