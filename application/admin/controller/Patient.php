<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
class Patient extends Controller
{
	//获取患者列表
	public function index(){
		//星级标识
		//获取患者列表
		$userMsg = Session::get('userMsg');
		$data['adminId']=$userMsg['id'];
		// $data['adminId']='3';
		if ($data['adminId']) {
	        $url = config('path')."/patient/all";
	        $res = http_request($url, $data);
	        $res = json_decode($res,true);
		}
        // if (!$res['0']['id']) {
        // 	return array('code' => 2,'msg' => $res['message']);
        // }
		// $this->assign('star',1);
		// $this->assign('id',10001);
		if (!$res['error']) {
			$this->assign('info',$res);
		}
		return $this->fetch('index');die;
	}

	//患者详情
	public function details(){
		$id = input('id');
		$this->assign('id',$id);
		//基本信息
		$url = config('path')."/patient/id/".$id;
        $res = http_request($url, $data);
        $res = json_decode($res,true);
        if ($res) {
        	$this->assign('info',$res);
        }
		//既往病史
		//体检报告
		//用药记录
		$medical_data['patientId'] = $id;
		$medical_url = config('path')."/patient/medical/getList";
        $medical_res = http_request($medical_url, $medical_data);
        $medical_res = json_decode($medical_res,true);
        if ($medical_res) {
        	$this->assign('medicalList',$medical_res);
        }
		//测量统计
		//服务记录
		$service_url = config('path')."/patient/service/getList";
		$service_data['patientId'] = $id;
		$service_res = http_request($service_url, $service_data);
        $service_res = json_decode($service_res,true);
        if ($service_res AND !$service_res['error']) {
        	$this->assign('serviceList',$service_res);
        }
		return $this->fetch();
	}

	//基本信息修改
	public function patient_info_submit(){
		foreach ($_POST as $key => $value) {
			$obj->$key = $value;
		}
		$data = json_encode($obj);
		//基本信息提交修改接口
		$url = config('path')."/patient/update";
        $headers = array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        $res = http_request($url, $data,$headers);
        $res = json_decode($res,true);
        if ($res == 1) {
        	return array('code' => 1);
        }else{
        	return array('code' => 2,'msg' => $res['message']);
        }
	}

	public function patient_add(){
		if (!$_POST) {
			return $this->fetch();die;
		}
        ini_set("error_reporting","E_ALL & ~E_NOTICE");
		$_POST['groupName'] = $_POST['unit'];
		$_POST['groupID'] = $_POST['unit_id'];
		$_POST['doctorID'] = $_POST['ys_id'];
		$_POST['assistantID'] = $_POST['yz_id'];
		unset($_POST['unit'],$_POST['unit_id'],$_POST['ys_id'],$_POST['yz_id'],$_POST['passWord2']);
		// die;
		//新增患者接口
		// $obj->AddPatientMapper = $_POST;
  //       $data =  json_encode($obj);
  // //       var_dump($data);die;
  		foreach ($_POST as $key => $value) {
  			$obj->$key = $value;
  		}
  		$data =  json_encode($obj);
		// $data['addPatientMapper']=$_POST;
        $url = config('path')."/patient/insert";
        $headers = array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        $res = http_request($url, $data,$headers);
        $res = json_decode($res,true);
        if ($res) {
        	return array('code' => 1,'msg'=>'新增成功');
        }else{
        	return array('code' => 2,'msg'=>$res['message']);
        }
        var_dump($res);die;
	}

	//标记星级用户
	public function star_set(){
		$id = input('post.id');
		$data['isStar'] = 1;
		$data['patientId'] = $id;
        $url = config('path')."/patient/star";
        $res = http_request($url, $data);
        $res = json_decode($res,true);
        if ($res === true) {
			return array('code' => 1);
        }else{
			return array('code' => 2,'msg' => $res['message']);
        }
		
	}

	//取消星级用户
	public function star_cancel(){
		$id = input('post.id');
		$data['isStar'] = 2;
		$data['patientId'] = $id;
        $url = config('path')."/patient/star";
        $res = http_request($url, $data);
        $res = json_decode($res,true);
        if ($res === true) {
			return array('code' => 1);
        }else{
			return array('code' => 2,'msg' => $res['message']);
        }
		//(接口)
		return array('code' => 1);
	}

	//设置分组
	public function group_set(){
		//提交表单
		if (input("?post.submit_set")) {

			$data['id'] = (Integer)$_POST['id'];
			$data['groupID'] = (Integer)$_POST['jg_id'];
			$data['doctorID'] = (Integer)$_POST['doctor']?:'';
			$data['assistantID'] = (Integer)$_POST['yz']?:'';
			//id:患者id
			//jg_id:机构id
			//doctor:医生id
			//yz:医助id
			//修改组别(接口)
			foreach ($data as $key => $value) {
				$obj->$key=$value;
			}
			$data = json_encode($obj);
			$url = config("path")."/patient/bangding";
			$res = http_request($url,$data,1);
			if ($res) {
				return array('code' => 1);
			}else{
				return array('code' => 2);
			}
		}
		$id = input('id');
		//通过用户id查询当前组别
		//分组需要权限,健康源(type==1)可以看到所有机构,此外(type==2)只能看到并选择自己的机构
		$userMsg = Session::get('userMsg');
		$data['adminId'] = $userMsg['id'];
		switch ($userMsg['type']) {
			case '1':
				//获取所有机构
				$url = config('path')."/classification/group/getAll";
				$res = http_request($url,$data);
				$res = json_decode($res,true);
				if ($res) {
					$this->assign('groupList',$res);
				}
				break;
			case '2':
				# code...
				break;
			default:
				return array('code' => 2,'msg'=>'权限不足');die;
				break;
		}
		// $this->assign('jg_id','12');
		// $this->assign('doctor_id','122');
		// $this->assign('assistant_doctor_id','619');

		$this->assign('id',$id);
		return $this->fetch();
	}

	//根据机构查找医生医助
	public function jg(){
		$id = input('post.p_id');
		//根据机构id获取数据(接口)
		$data['groupId'] = $id;
		$url = config('path')."/classification/doctor/getAll";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		$data['ys'] = $res;
		$url = config('path')."/classification/assistant/getAll";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		$data['yz'] = $res;
		// if (input('post.p_id') == 1) {
		// 	$data = array('ys' => array('121' => array('name' => '朱玉婷','tel' => '1322131513'),'131' => array('name' => '徐医生','tel' => '56445112313')),'yz' => array('255' => array('name' => '肖莉','tel' => '22251111'),'620' => array('name' => '杨涛','tel' => '669955442')));
		// }
		// if (input('post.p_id') == 12) {
		// 	$data = array('ys' => array('122' => array('name' => '徐医生','tel' => '1322131513'),'132' => array('name' => '杨医生','tel' => '56445112313')),'yz' => array('254' => array('name' => '谢晓燕','tel' => '22251111'),'619' => array('name' => '嘻嘻嘻','tel' => '669955442')));
		// }

		$html.="<tbody id='list'>";
		$html_yz.="<tbody id='list_yz'>";
		foreach ($data['ys'] as $key => $value) {
			$html.="<tr class='text-c'><td><input name='doctor' type='radio' value=".$value['id']." id='doctor'></td><td>".$value['name']."</td><td>"."<img style='width:50px;height:50px' src=".$value['imgUrl'].">"."</td><td>".$value['phoneNum']."</td>";
			
		}
		foreach ($data['yz'] as $key => $value) {
			// switch ($value['sex']) {
			// 	case '1':
			// 		$value['sex'] = '男';
			// 		break;
			// 	case '2':
			// 		$value['sex'] = '女';
			// 		break;
			// }
			$html_yz.="<tr class='text-c'><td><input name='yz' type='radio' value=".$value['id']." id='yz'></td><td>".$value['name']."</td><td>"."<img style='width:50px;height:50px' src=".$value['imgUrl'].">"."</td><td>".$value['phoneNum']."</td>";
		}

		$html.="</tbody>";
		$html_yz.="</tbody>";
		return array('code' => 1,'data' => $data,'html' => $html,'html_yz' => $html_yz);
	}

	//添加用药记录
	public function add_drug_record(){
		if (!$_POST AND !input('type')) {
			return $this->fetch();die;
		}
		//编辑用药记录
		if (input('type') == 'save') {
			if (!$_POST) {
				//获取单条用药记录
				$detail_data['medicalId'] = input('id');
				$detail_url = config('path')."/patient/medical/getDetail";
				$detail_res = http_request($detail_url,$detail_data);
				$detail_res = json_decode($detail_res,true);
				if ($detail_res) {
					$this->assign('medical_detail',$detail_res);
					return $this->fetch();
				}
				die;
			}
			if ($_POST['id'] AND $_POST['patientId']) {
				//提交修改数据
				$save_data = $_POST;
				$save_data['id'] = $_POST['id'];
				$save_data['useTime'] = strtotime($save_data['useTime']);
				$save_data['patientId'] = $_POST['patientId'];
				foreach ($save_data as $key => $value) {
					$obj->$key = $value;
				}
				$save_data = json_encode($obj);
				$save_url = config('path')."/patient/medical/update";
	       		$headers = array("Content-type:application/json;charset='utf-8'","Accept:application/json");
				$save_res = http_request($save_url,$save_data,$headers);
				$save_res = json_decode($save_res,true);
				if ($save_res) {
					return array('code' => 1);
				}else{
					return array('code' => 2);
				}
				die;
			}
		}
		
		$_POST['useTime'] = strtotime($_POST['useTime']);
		$_POST['patientId'] = input('id');
		foreach ($_POST as $key => $value) {
			$obj->$key = $value;
		}
		$data = json_encode($obj);
		$url = config('path')."/patient/medical/insert";
        $headers = array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        $res = http_request($url, $data, $headers);
        $res = json_decode($res,true);
		if ($res) {
			return array('code' => 1);
		}else{
			return array('code' => 2);
		}
	}

	//删除用药记录
	public function medical_del(){
		$id = $_POST['id'];//记录id
		$data['medicalId'] = $id;
		$url = config('path')."/patient/medical/delete";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		if ($res == true) {
			return array('code' => 1);
		}else{
			return array('code' => 2);
		}
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
		$data['groupId'] = $id;
		//获取医生list
		$url = config('path')."/classification/doctor/getAll";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		if (!$res['error']) {
			$data['ys'] = $res;
		}
		$html.="<tbody id='list'>";
		foreach ($data['ys'] as $key => $value) {
			$html.="<tr class='text-c'><td>".$value['name']."</td><td>"."<img style='width:50px;height:50px' src=".$value['imgUrl'].">"."</td><td>".$value['phoneNum']."</td><td>".$value['profession']."</td><td>".$value['school']."</td><td>".$value['introduction']."</td><td><a style='text-decoration:none' class='ml-5' onclick='doctor_edit(".$value['id'].")'><i class='Hui-iconfont'>&#xe6df;</i></a><a style='text-decoration:none' class='ml-5' onClick='doctor_del(".$value['id'].")' href='javascript:;'' title='删除''><i class='Hui-iconfont'>&#xe6e2;</i></a></td>";
		}
		$html.="</tbody>";
		//获取医助list
		$url = config('path')."/classification/assistant/getAll";
		$res = http_request($url,$data);
		$res = json_decode($res,true);
		if (!$res['error']) {
			$data['yz'] = $res;
		}
		$html_yz.="<tbody id='list_yz'>";
		foreach ($data['yz'] as $key => $value) {
			switch ($value['sex']) {
				case '1':
					$sex = '男';
					break;
				case '2':
					$sex = '女';
					break;
			}
			$html_yz.="<tr class='text-c'><td>".$value['name']."</td><td><img style='width:50px;height:50px' src=".$value['imgUrl']."></td><td>".$value['phoneNum']."</td><td>".$sex."</td><td>".$value['introduction']."</td><td><a style='text-decoration:none' class='ml-5' onclick='assistant_doctor_edit(".$value['id'].")'><i class='Hui-iconfont'>&#xe6df;</i></a><a style='text-decoration:none' class='ml-5' onClick='assistant_doctor_del(".$value['id'].")' href='javascript:;'' title='删除''><i class='Hui-iconfont'>&#xe6e2;</i></a></td>";
		}
		$html_yz.="</tbody>";

		return array('code' => 1,'data' => $data,'html' => $html,'html_yz' => $html_yz);
		// var_dump($res);die;
		// if (input('post.p_id') == 11) {
		// 	$data = array('ys' => array('12' => array('name' => '朱玉婷','tel' => '1322131513','img' => '头像'),'13' => array('name' => '徐医生','tel' => '56445112313','img' => '头像')),'yz' => array('255' => array('name' => '肖莉','tel' => '22251111','img' => '/public/images/1517275559.jpg','sex' => '1','introduce' => '一位合格的医助','patient_num' => '10'),'620' => array('name' => '杨涛','tel' => '669955442','img' => '/public/images/1521601030.jpg','sex' => '1','introduce' => '第二位合格的医助','patient_num' => '15')));
		// }
		// if (input('post.p_id') == 12) {
		// 	$data = array('ys' => array('12' => array('name' => '徐医生','tel' => '1322131513'),'13' => array('name' => '杨医生','tel' => '56445112313')),'yz' => array('255' => array('name' => '谢晓燕','tel' => '22251111'),'620' => array('name' => '嘻嘻嘻','tel' => '669955442')));
		// }

		// $html.="<tbody id='list'>";
		// $html_yz.="<tbody id='list_yz'>";
		// foreach ($data['ys'] as $key => $value) {
		// 	$html.="<tr class='text-c'><td>".$value['name']."</td><td>".'头像'."</td><td>".$value['tel']."</td><td>".'操作'."</td>";
		// }
		// foreach ($data['yz'] as $key => $value) {
		// 	switch ($value['sex']) {
		// 		case '1':
		// 			$sex = '男';
		// 			break;
		// 		case '2':
		// 			$sex = '女';
		// 			break;
		// 	}
		// 	$html_yz.="<tr class='text-c'><td>".$value['name']."</td><td><img style='width:50px;height:50px' src=".$value['img']."></td><td>".$value['tel']."</td><td>".$sex."</td><td>".$value['introduce']."</td><td>".$value['patient_num']."</td><td><a style='text-decoration:none' class='ml-5' onclick='assistant_doctor_edit(".$key.")'><i class='Hui-iconfont'>&#xe6df;</i></a><a style='text-decoration:none' class='ml-5' onClick='assistant_doctor_del(".$key.")' href='javascript:;'' title='删除''><i class='Hui-iconfont'>&#xe6e2;</i></a></td>";
		// }

		// $html.="</tbody>";
		// $html_yz.="</tbody>";
		// return array('code' => 1,'data' => $data,'html' => $html,'html_yz' => $html_yz);
	}

	//患者管理->预警设置(血压)
	public function detail_warning(){
		var_dump($_POST);
	}

	//患者管理->预警设置(血糖)
	public function detail_suger_warning(){
		var_dump($_POST);
	}

	//血糖数据编辑
	public function sugar_edit(){
		$id = input('id');
		$sugar_info = input('sugar_info');
		$this->assign('sugar_info',$sugar_info);
		$this->assign('id',$id);
		return $this->fetch();	
	}

	//添加服务记录
	public function add_service_record(){
		if (input('re_id') AND !$_POST) {
			//接口,根据服务记录id查询记录详情
			$data['recordId'] = input('re_id');
			$url = config('path')."/patient/service/getDetail";
			$res = http_request($url,$data);
			$res = json_decode($res,true);
			$res['patientId'] = input('id');
			$this->assign('info',$res);
			return $this->fetch();die;
		}
		if ($_POST['save_re_id']) {
			$_POST['patientId'] = $_POST['id'];
			unset($_POST['id']);
			$_POST['id'] = $_POST['save_re_id'];
			unset($_POST['save_re_id']);
			foreach ($_POST as $key => $value) {
				$obj->$key = $value;
			}
			$data = json_encode($obj);
			$url = config('path')."/patient/service/update";
			$res = http_request($url,$data,'1');
			$res = json_decode($res,true);
			if ($res==1) {
				return array('code' => 1);
			}else{
				return array('code' => 2);
			}
		}
		if ($_POST AND !$_POST['save_re_id']) {
			$_POST['patientId'] = input('id');
			// $_POST['time'] = strtotime($_POST['time']);
			$url = config('path')."/patient/service/insert";
			foreach ($_POST as $key => $value) {
				$obj->$key = $value;
			}
			$data = json_encode($obj);
			$res = http_request($url,$data,'1');
			$res = json_decode($res,true);
			if ($res==1) {
				return array('code' => 1);
			}else{
				return array('code' => 2);
			}
		}
		return $this->fetch();
	}

	//删除服务记录
	public function del_service_record(){
		$data['serviceId'] = $_POST['id'];
		//删除服务记录(接口)
		$url = config('path')."/patient/service/delete";
		$res = http_request($url,$data);
		if ($res == true) {
			return array('code' => 1);
		}else{
			return array('code' => 2);
		}
	}

	//删除患者
	public function patient_del(){
		$id = $_POST['id'];
		$data['patientId'] = $id;
        $url = config('path')."/patient/delete";
        $res = http_request($url, $data);
        $res = json_decode($res,true);
        if ($res === true) {
			return array('code' => 1);
        }else{
			return array('code' => 2,'msg' => $res['message']);
        }
		//删除用户(接口)
		return array('code' => 1);
	}

	//添加/编辑病史
	public function patient_case_add(){
		$case_name = $_POST["case_name"];
		//case_data为病史类型标识 1:其他疾病史与治疗史 2:吸烟饮酒史 3:过敏史 4:家族遗传病史 5:服用药物
		$case_data = $_POST["case_data"];
		$id = $_POST["id"];
		if ($_POST) {
			switch ($_POST['case_data']) {
				case '1':
					//添加病史记录
					//添加成功返回case_id
					return array('code' => 1,'case_id' => '148');
					break;
				case '2':
					# code...
					break;
				case '3':
					# code...
					break;
				case '4':
					# code...
					break;
				case '5':
					# code...
					break;
			}
			die;
		}

		// $case_re = array('1' => array('0' => '曾患湿疹'));
		// $this->assign('info',$case_re);
		$this->assign('id',input('id'));
		return $this->fetch();
	}

	//添加/编辑影响因素
	public function patient_factor_add(){
		if ($_POST) {
			$data = $_POST['data'];
			return array('code' => 1);
		}
		$this->assign('id',input('id'));
		return $this->fetch();
	}

	//上传体检报告
	public function examination(){
		if ($_FILES['file1']['size']) {
            foreach ($_FILES as $key => $value) {
            	var_dump($value);
            }die;
        }

		$id = input('id');
		$ex_id = input('ex_id');
		//编辑标识
		if ($ex_id) {
			//根据id查出信息
			$examination = array('name' => 'XXX体检报告','imgUrl' => '__PUBLIC__/images/1517275559.jpg','remark' => '这是XXX在第二人民医院的体检报告','date' => '2018-03-14');
			$this->assign('examination',$examination);
		}
		return $this->fetch();
	}

	//查看体检报告
	public function picture_show(){
		$id = input('id');
		return $this->fetch();
	}

	//删除体检报告
	public function examination_del(){
		
		return array('code' => 1);
	}

	//上传图片获取token
	public function uploadToken(){
		$url = config('path')."/patient/image/uploadToken";
		$res = http_request($url);
		return $res;
	}
}