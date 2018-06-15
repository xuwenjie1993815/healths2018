<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use app\admin\model\User;
use think\Db;
class Device extends Controller{
	//列表
	public function index(){
		//获取设备列表(接口)
		
		return $this->fetch('index');die;
	}

	//添加设备
	public function device_add(){
		//权限判断
		if (1==1) {
			$userMsg['id'] = 1;
			$userMsg['name'] = '健康源';
			$this->assign('type',1);
			$this->assign('userMsg',$userMsg);
		}else{
			//查询当前登录者机构信息(名字+id)
			$userMsg['id'] = 20;
			$userMsg['name'] = '限盐组';
			$this->assign('type',2);
			$this->assign('userMsg',$userMsg);
		}
		if (input('?get.select_admin') != false AND input('get.select_admin') == '1'){
			$content = input('get.content');
			//机构信息(接口)
			return array('code' => '1','msg' => '健康源,机构编号:1');
		}
		if (input('?post.number') != false) {
			$number = input('post.number');
			//添加设备操作(接口)
			return array('code' => '1','msg' => '健康源,机构编号:1');
		}
		return $this->fetch();
	}

	//删除,解绑设备
	public function device_remove(){
		//获取设备编号或id,调用接口进行解绑,删除操作
		// input('post.number');
	}
	
}