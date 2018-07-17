<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use app\admin\model\User;
use think\Db;
use think\Session;
class Jurisdiction extends Controller{
	
	public function index(){
		//判断机构/医生
		$usertype = Session::get('userMsg.type');
		$this->assign('usertype',$usertype);
		return $this->fetch();
	}

	//新增机构
	public function unit_add(){
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
		if (input('get.g_id') != '' AND input('get.type') == 1) {
			$g_id = input('get.g_id');
			$type = input('get.type');
			$this->assign('g_id',$g_id);
			$this->assign('type',1);
			return $this->fetch();
			die;
		}
		//新增医助
		if (input('get.g_id') != '' AND input('get.type') == 2) {
			$g_id = input('get.g_id');
			$type = input('get.type');
			$this->assign('g_id',$g_id);
			$this->assign('type',2);
			return $this->fetch();
			die;
		}
		//新增机构
		// input('?post.imgone')
		if ($_FILES AND input('?get.g_id') == false) {
			var_dump($_POST);die;
			//新增机构接口
		}
		$this->assign('jg',1);
		return $this->fetch();
	}


	//编辑医助
	public function assistant_doctor_edit(){
		if (!$_POST) {
			$assistant_doctor_id = input('get.assistant_doctor_id');
			//获取医助信息(接口)
			$assistant_doctor_info = array('imgone' => 'http://www.healths2018.com/public/images/1521601030.jpg','username' => '肖莉','mobile' => '18623525272','real_name' => '肖莉');
			$this->assign('info',$assistant_doctor_info);
			$this->assign('g_id',$g_id);
			$this->assign('type',1);
			$this->assign('assistant_doctor_id',$assistant_doctor_id);
			return $this->fetch('unit_add');die;
		}
		var_dump($_POST);
		
	}
}