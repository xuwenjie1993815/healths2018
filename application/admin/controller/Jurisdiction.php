<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use app\admin\model\User;
use think\Db;
use think\Session;
use app\admin\controller\Base;
class Jurisdiction extends Base{
	
	public function index(){
		//判断机构/医生
		$usertype = Session::get('userMsg.type');
		$data['adminId'] = Session::get('userMsg.id');
		//超级用户
		if ($usertype == 1) {
			//获取所有机构
			$url = config('path')."/classification/group/getAll";
			$res = http_request($url,$data);
			$res = json_decode($res,true);
			if ($res AND !$res['error']) {
				$this->assign('groupList',$res);
			}
		}
		//机构用户todo
		if ($usertype == 2) {
			
		}
		$this->assign('usertype',$usertype);
		return $this->fetch();
	}

	//新增/编辑机构
	public function unit_add(){
		if (input('id')) {
			if ($_POST) {
				$_POST['imgUrl'] = "http://pbngsysl7.bkt.clouddn.com/".$_POST['imgkey'];
				$_POST['id'] = input('id');
				unset($_POST['imgkey']);
				$_POST = json_encode($_POST);
				$url = config('path')."/classification/group/update";
				$res = http_request($url,$_POST,1);
				$res = json_decode($res,true);
				if ($res AND !$res['error']) {
					return array('code' => 1);
				}else{
					return array('code' => 2,'msg' => $res['message']);
				}
				die;
			}
			//通过机构id查找机构详情
			$url = config('path')."/classification/group/getOne";
			$data['groupId'] = input('id');
			$res = http_request($url,$data);
			$res = json_decode($res,true);
			if ($res AND !$res['error']) {
                $str = strrev($res['imgUrl']);
                $imgkey = explode('/',$str)[0];
                $res['imgkey'] = strrev($imgkey);
				$this->assign('info',$res);
			}
		}
		// if ($_FILES["file"]["error"] > 0){
		// 	echo "Error: " . $_FILES["file"]["error"] . "<br />";
		// }else{
		// 	echo "Upload: " . $_FILES["file"]["name"] . "<br />";
		// 	echo "Type: " . $_FILES["file"]["type"] . "<br />";
		// 	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		// 	echo "Stored in: " . $_FILES["file"]["tmp_name"];
		// }
		// var_dump($_POST,$_FILES);die;
		//新增医生
		if (input('get.g_id') != '' AND input('get.type') == 1 AND !input('get.doctor_id')) {
			if ($_POST) {
				
				$_POST['imgUrl'] = "http://pbngsysl7.bkt.clouddn.com/".$_POST['imgkey'];
				unset($_POST['imgkey'],$_POST['passConfirm']);
				// var_dump($_POST);die;
				$_POST['groupID'] = input('get.g_id');
				$_POST['userName'] = '';
				$_POST['nickName'] = '';
				//新增医生接口
				foreach ($_POST as $key => $value) {
					$obj->$key = $value;
				}
				$data = json_encode($obj);
				$url = config('path')."/classification/doctor/insert";
				$res = http_request($url,$data,1);
				$res = json_decode($res,true);
				if ($res) {
					return array('code' => 1);
				}else{
					return array('code' => 2);
				}
			}
			$g_id = input('get.g_id');
			$type = input('get.type');
			$this->assign('g_id',$g_id);
			$this->assign('type',1);
			return $this->fetch();
			die;
		}
		//新增医助
		if (input('get.g_id') != '' AND input('get.type') == 2 AND !input('get.assistant_doctor_id')) {
			if ($_POST) {
				$_POST['imgUrl'] = "http://pbngsysl7.bkt.clouddn.com/".$_POST['imgkey'];
				$_POST['groupID'] = input('get.g_id');
				$_POST['userName'] = '';
				$_POST['nickName'] = '';
				unset($_POST['imgkey'],$_POST['passConfirm']);
				//新增医助接口
				foreach ($_POST as $key => $value) {
					$obj->$key = $value;
				}
				$data = json_encode($obj);
				$url = config('path')."/classification/assistant/insert";
				$res = http_request($url,$data,1);
				$res = json_decode($res,true);
				if ($res) {
					return array('code' => 1);
				}else{
					return array('code' => 2);
				}
			}
			$g_id = input('get.g_id');
			$type = input('get.type');
			$this->assign('g_id',$g_id);
			$this->assign('type',2);
			return $this->fetch();
			die;
		}
		//新增机构
		// input('?post.imgone')
		if (input('?get.g_id') == false AND $_POST) {
			$_POST['imgUrl'] = "http://pbngsysl7.bkt.clouddn.com/".$_POST['imgkey'];
			$userMsg = Session::get('userMsg');
			$_POST['id']=$userMsg['id'];
			$_POST['nickName']='';
			$_POST['userName']='';
			unset($_POST['imgkey'],$_POST['passConfirm']);
			//新增机构接口
			foreach ($_POST as $key => $value) {
				$obj->$key = $value;
			}
			$data = json_encode($obj);
			$url = config('path')."/classification/group/insert";
			$res = http_request($url,$data,1);
			$res = json_decode($res,true);
			if ($res and !$res['error']) {
				return array('code' => 1);
			}else{
				return array('code' => 2);
			}
			die;
		}
		//编辑医生
		if (input('get.g_id') !== '' AND input('get.type') == 1 AND input('get.doctor_id')) {
			if ($_POST) {
				$_POST['id'] = input('get.doctor_id');
				$_POST['nickName']='';
				$_POST['userName']='';
				$_POST['imgUrl'] = "http://pbngsysl7.bkt.clouddn.com/".$_POST['imgkey'];
				unset($_POST['imgkey']);
				foreach ($_POST as $key => $value) {
					$obj->$key = $value;
				}
				$data = json_encode($obj);
				$url = config('path')."/classification/doctor/update";
				$res = http_request($url,$data,1);
				if ($res) {
					return array('code' => 1);
				}else{
					return array('code' => 2);
				}
				die;
			}
			//获取医生详情
			$data['doctorId'] = input('get.doctor_id');
			$url = config('path')."/classification/doctor/detail";
			$res = http_request($url,$data);
			$res = json_decode($res,true);
			$g_id = input('get.g_id');
			$type = input('get.type');
			$this->assign('info',$res);
			$this->assign('g_id',$g_id);
			$this->assign('doctor_id',input('get.doctor_id'));
			$this->assign('type',$type);
			return $this->fetch();
			die;
		}
		//编辑医助
		if (input('get.g_id') !== '' AND input('get.type') == 2 AND input('get.assistant_doctor_id')) {
			if ($_POST) {
				$_POST['id'] = input('get.assistant_doctor_id');
				if (!$_POST['imgkey']) {
					$_POST['imgkey'] = "mainTest";
				}
				$_POST['imgUrl'] = "http://pbngsysl7.bkt.clouddn.com/".$_POST['imgkey'];
				unset($_POST['imgkey']);
				foreach ($_POST as $key => $value) {
					$obj->$key = $value;
				}
				$data = json_encode($obj);
				$url = config('path')."/classification/assistant/update";
				$res = http_request($url,$data,1);
				if ($res) {
					return array('code' => 1);
				}else{
					return array('code' => 2);
				}
				die;
			}
			//获取医助详情
			$data['assistantId'] = input('get.assistant_doctor_id');
			$url = config('path')."/classification/assistant/detail";
			$res = http_request($url,$data);
			$res = json_decode($res,true);
			$g_id = input('get.g_id');
			$type = input('get.type');
			$this->assign('info',$res);
			$this->assign('g_id',$g_id);
			$this->assign('assistant_doctor_id',input('get.assistant_doctor_id'));
			$this->assign('type',$type);
			return $this->fetch();
			die;
		}
		$this->assign('jg',1);
		return $this->fetch();
	}

	//删除
	public function classification_del(){
		$id = $_POST['id'];
		$type = $_POST['type'];
		switch ($type) {
			case '1':
				#删除医生
				$data['doctorId'] = $id;
				$url = config('path')."/classification/doctor/delete";
				$res = http_request($url,$data);
				if ($res == true) {
					return array('code' => 1);
				}else{
					return array('code' => 2);
				}
				break;
			case '1':
				#删除医助
				$data['assistantId'] = $id;
				$url = config('path')."/classification/assistantId/delete";
				$res = http_request($url,$data);
				if ($res == true) {
					return array('code' => 1);
				}else{
					return array('code' => 2);
				}
				break;
			default:
				#删除机构
				break;
		}
	}

	//删除机构
	public function group_del(){
		$data['groupId'] = $_POST['id'];
		$url = config('path')."/classification/group/delete";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			return array('code' => 1);
		}else{
			return array('code' => 1,'msg' => $res['message']);
		}
	}

}