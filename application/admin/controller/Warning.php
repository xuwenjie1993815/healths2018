<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
class Warning extends Controller{
	//获取预警列表
	public function index(){
		//接口获取列表
		$userMsg = Session::get('userMsg');
		$data['adminId'] = $userMsg['id'];
		$data['isDeal'] = '0';
		$url = config('path')."/bloodEntity/warnInfoList";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			$this->assign('list',$res);
		}
		return $this->fetch();
	}

	//预警处理
	public function sign_warning(){
		$ids = $_POST['ids'];
		$data = json_encode($ids);
		$url = config("path")."/bloodEntity/deal";
		$res = http_request($url,$data,1);
		$res = json_decode($res,true);
		$len = count($ids);
		if ($res AND !$res['error']) {
			return array('code' => 1,'len' => $len);
		}else{
			return array('code' => 2,'msg' => $res['message']);
		}
	}

	//今日预警列表
	public function value_log(){
		$data['patientId'] = input('id');
		//1:高血压 2:低血压
		$data['type'] = '1';
		$url = config('path')."/bloodEntity/warnData";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			$this->assign('list',$res);
		}
		return $this->fetch();
	}

	//发送短信
	public function send_sms(){
		$res = send_sms($_POST['phoneNum'],$_POST['message']);
		return $res;
	}
}