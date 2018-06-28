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
		$this->assign('id',10001);
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
		$id = input('id');
		$this->assign('id',$id);
		//基本信息
		//既往病史
		//体检报告
		//用药记录
		//测量统计
		//服务记录
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
		//提交表单
		if (input("?post.submit_set")) {
			//id:患者id
			//jg_id:机构id
			//doctor:医生id
			//yz:医助id
			$id = input("post.id");
			$jg_id = input("post.jg_id");
			$doctor = input("post.doctor");
			$yz = input("post.yz");
			//修改组别(接口)
			return array('code' => 1);
		}

		$id = input('id');
		//通过用户id查询当前组别
		$this->assign('id',$id);
		return $this->fetch();
	}

	//根据机构查找医生医助
	public function jg(){
		$id = input('post.p_id');
		//根据机构id获取数据(接口)
		
		if (input('post.p_id') == 11) {
			$data = array('ys' => array('12' => array('name' => '朱玉婷','tel' => '1322131513'),'13' => array('name' => '徐医生','tel' => '56445112313')),'yz' => array('255' => array('name' => '肖莉','tel' => '22251111'),'620' => array('name' => '杨涛','tel' => '669955442')));
		}
		if (input('post.p_id') == 12) {
			$data = array('ys' => array('12' => array('name' => '徐医生','tel' => '1322131513'),'13' => array('name' => '杨医生','tel' => '56445112313')),'yz' => array('255' => array('name' => '谢晓燕','tel' => '22251111'),'620' => array('name' => '嘻嘻嘻','tel' => '669955442')));
		}

		$html.="<tbody id='list'>";
		$html_yz.="<tbody id='list_yz'>";
		foreach ($data['ys'] as $key => $value) {
			$html.="<tr class='text-c'><td><input name='doctor' type='radio' value=".$key." id='doctor'></td><td>".$value['name']."</td><td>".$value['tel']."</td>";
		}
		foreach ($data['yz'] as $key => $value) {
			$html_yz.="<tr class='text-c'><td><input name='yz' type='radio' value=".$key." id='yz'></td><td>".$value['name']."</td><td>".$value['tel']."</td>";
		}

		$html.="</tbody>";
		$html_yz.="</tbody>";
		return array('code' => 1,'data' => $data,'html' => $html,'html_yz' => $html_yz);
	}

	//添加用药记录
	public function add_drug_record(){
		$data['time'] = strtotime($_POST['data2']);
		$data['drug_name'] = $_POST['drug_name'];
		$data['consumption'] = $_POST['consumption'];
		$data['regular'] = $_POST['regular'];
		$data['id'] = input('id');
		//添加用药记录(接口)
		return $this->fetch();
	}

	//患者病史设置
	public function patient_record_set(){
		var_dump($_POST);die;
		//设置病史(接口)
	}

	//患者体检报告设置
	public function patient_inspect_set(){
		var_dump($_POST);die;
	}

	//根据机构查找医生医助(分级)
	public function jg_fj(){
		$id = input('post.p_id');
		//根据机构id获取数据(接口)
		
		if (input('post.p_id') == 11) {
			$data = array('ys' => array('12' => array('name' => '朱玉婷','tel' => '1322131513','img' => '头像'),'13' => array('name' => '徐医生','tel' => '56445112313','img' => '头像')),'yz' => array('255' => array('name' => '肖莉','tel' => '22251111'),'620' => array('name' => '杨涛','tel' => '669955442')));
		}
		if (input('post.p_id') == 12) {
			$data = array('ys' => array('12' => array('name' => '徐医生','tel' => '1322131513'),'13' => array('name' => '杨医生','tel' => '56445112313')),'yz' => array('255' => array('name' => '谢晓燕','tel' => '22251111'),'620' => array('name' => '嘻嘻嘻','tel' => '669955442')));
		}

		$html.="<tbody id='list'>";
		$html_yz.="<tbody id='list_yz'>";
		foreach ($data['ys'] as $key => $value) {
			$html.="<tr class='text-c'><td>".$value['name']."</td><td>".'头像'."</td><td>".$value['tel']."</td><td>".'操作'."</td>";
		}
		foreach ($data['yz'] as $key => $value) {
			$html_yz.="<tr class='text-c'><td>".$value['name']."</td><td>".$value['tel']."</td>";
		}

		$html.="</tbody>";
		$html_yz.="</tbody>";
		return array('code' => 1,'data' => $data,'html' => $html,'html_yz' => $html_yz);
	}


}