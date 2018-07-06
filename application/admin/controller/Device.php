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
			//根据机构名称/id查询机构详情
	        $data = "<tr onClick='admin_jg(1)' style='cursor:Pointer;' id='jg1'><th>1</th><td>健康源</td></tr>";
	        return array('code' => 1,'msg' => $data);
			// return array('code' => '1','msg' => '健康源,机构编号:1');
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
		//批量删除
		$ids = $_POST['ids'];
		$len = count($ids);
		return array('code' => 1,'len' => $len);
	}

	//用户绑定/解绑设备
	public function bangding(){
		//解绑设备
		if (input('post.remove') == 1) {
			$dataid = input('post.id');
			$p_id = input('post.p_id');
			//解绑(接口)
			return array('code' => '1','msg' => '解绑成功');
		}
		//绑定设备
		if (input('post.bangding') == 1) {
			$dataid = input('post.id');
			$p_id = input('post.p_id');
			//绑定(接口)
			if (1==2) {
				return array('code' => '1','msg' => '绑定成功');
			}else{
				return array('code' => '2','msg' => '你已经绑定xxxxx,确认更换绑定设备吗？');
			}
		}
		//替换以前的设备
		if (input('post.change') == 1) {
			$data['pid']=$_POST['p_id'];
			$data['id']=$_POST['id'];
			return array('code' => '1','msg' => '绑定成功');
		}
		//搜索
		if (input('?post.pid') and input('post.info') == 1) {
			$pid = input('post.pid');
			$select = input('post.select');
			//根据搜索内容查询设备列表(接口)
			
		}
		$id = input('id');
		$this->assign('id',$id);
		return $this->fetch();
	}

	
}