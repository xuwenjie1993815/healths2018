<?php
namespace app\index\controller;
use think\View;
use think\Controller;
use think\Session;
use app\index\controller\Base;
class Message extends Base
{
	public function index(){
		//获取留言列表
		$data['patientId'] = 125;
		$url = config('path')."/app/patientQuestion";
		$res = http_request($url, $data);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			$this->assign('list',$res);
		}
		return $this->fetch();
	}
}