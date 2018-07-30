<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
use app\admin\model\User;
use app\admin\controller\Base;
class Integral extends Base
{
	public function index(){
		$userMsg = Session::get('userMsg');
		$data['adminId'] = $userMsg['id'];
		$url = config('path')."/integral/all";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			foreach ($res as $key => $value) {
				$res[$key]['nowtotal'] = $value['total']-$value['used'];
			}
			$this->assign('list',$res);
		}
		return $this->fetch();
	}

	//兑换积分
	public function integral_exchange(){
		$id = input('id');
		$this->assign('id',$id);
		if ($_POST) {
			//兑换积分(接口)
			$data['patientId'] = $_POST['id'];
			$data['used'] = $_POST['integral'];
			$data['mark'] = $_POST['remark'];
			$url = config('path')."/integral/use";
			$res = http_request($url,$data);
			$res = json_decode($res,true);
			if ($res AND !$res['error']) {
				return array('code' => 1);
			}else{
				return array('code' => 2,'msg' => $res['message']);
			}
		}
		return $this->fetch();
	}

	//历史记录
	public function integral_record(){
		$data['patientId'] = input('id');
		$url = config("path")."/integral/history";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			$this->assign('list',$res);
		}
		return $this->fetch();
	}

	//积分清零
	public function reset(){
		$data['patientId'] = $_POST['id'];
		$url = config("path")."/integral/reset";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			return array('code' => 1);
		}else{
			return array('code' => 2,'msg' => $res['message']);
		}
	}
}