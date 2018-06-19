<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use app\admin\model\User;
use think\Db;
class Patient extends Controller
{
	//获取患者列表
	public function index(){
		//星级标识
		$this->assign('star',1);
		return $this->fetch('index');die;
		// $a = Db::table('patient')->where('id',67)->find();

		$where='type = 0';
		$sql = "SELECT p.* FROM patient p LEFT JOIN (SELECT pid as p_id,MAX(upload_time) AS d_time FROM blood_record GROUP BY p_id ) as b ON p.id = b.p_id WHERE ( $where ) ORDER BY b.d_time desc ";
		$a = Db::query($sql);

		var_dump($a);die;

		$pagelist = $_POST['pagelist']?:10;
			$where='type = 0';
			switch ($_SESSION['userMsg']['type']) {
				case '1':
					$where.=' and yid='.$_SESSION['userMsg']['uid'].'';
					break;
				case '2':
					$where.=' and (pid='.$_SESSION['userMsg']['uid'].' or wid like "%'.$_SESSION['userMsg']['uid'].'%")';
					break;
				case '3':
					$where.=' and gid='.$_SESSION['userMsg']['uid'].'';
					break;
			}
		return $this->fetch('index');die;
	}

	//患者详情
	public function details(){
		return $this->fetch();
	}

	//基本信息修改
	public function patient_info_submit(){
		$name = $_POST['name'];
		$age = $_POST['age'];
		$card = $_POST['card'];
		$phone = $_POST['phone'];
		$address = $_POST['address'];
		$family_phone = $_POST['family_phone'];
		$sex = $_POST['sex'];
		//基本信息提交修改接口
		var_dump($_POST);
	}

	public function patient_add(){
		//新增患者接口
		return $this->fetch();
	}

	//标记星级用户
	public function star_set(){
		$id = input('post.id');
		//(接口)
		return array('code' => 1);
	}

	//取消星级用户
	public function star_cancel(){
		$id = input('post.id');
		//(接口)
		return array('code' => 1);
	}

	//设置分组
	public function group_set(){
		$id = input('id');
		//通过用户id查询当前组别
		$this->assign('id',$id);
		return $this->fetch();
	}


}