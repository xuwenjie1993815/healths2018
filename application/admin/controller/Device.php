<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
use app\admin\controller\Base;
class Device extends Base{
	//列表
	public function index(){
		//获取设备列表(接口)
        $userMsg = Session::get('userMsg');
        // $data['adminId'] = $userMsg['id'];
        $url = config('path')."/equipment/getAllEquipment?adminId=".$userMsg['id'];
        $res = http_request($url,$data);
        $res = json_decode($res,true);
        if (!$res['error']) {
        	$url = config('path')."/classification/group/getOne";
        	foreach ($res as $key => $value) {
        		$res[$key]['type'] = config('EQUIPMENT')[$value['type']];
        		//根据groupId查机构信息
        		$data1['groupId'] = $value['groupId'];
        		$res1 = http_request($url,$data1);
        		$res1 = json_decode($res1,true);
        		$res[$key]['groupName'] = $res1['name'];
        		//根据用户id获取用户信息
        		if ($value['patientId']) {
        			$patient_url = config('path')."/patient/id/".$value['patientId'];
        			$patient_res = http_request($patient_url);
        			$patient_res = json_decode($patient_res,true);
        			$res[$key]['patientId'] = $patient_res['name'];
        		}
        		
        	}
        	$this->assign('list',$res);
        }
		return $this->fetch('index');die;
	}

	//添加设备
	public function device_add(){
		//权限判断
        $userMsg = Session::get('userMsg');
		if ($userMsg['type']==1) {
			$this->assign('type',1);
			$this->assign('userMsg',$userMsg);
		}else{
			$this->assign('type',2);
			$this->assign('userMsg',$userMsg);
		}
		if (input('?get.select_admin') != false AND input('get.select_admin') == '1'){
			$content = input('get.content');
			//机构信息(接口)
			//根据机构名称/id查询机构详情
	        $url = config('path')."/classification/group/getByName?name=".urlencode($content);
	        $res = http_request($url);
	        $res = json_decode($res,true);
	        if ($res AND !$res['error']) {
	            $data = "<tbody id='admin_info'>";
	            foreach ($res as $key => $value) {
	                $data .= "<tr onClick='admin_jg(".$value['id'].")' style='cursor:Pointer;' id='jg".$value['id']."'><th><img style='width:30px;height: 30px;' src=".$value['imgUrl']."></th><td>".$value['name']."</td></tr>";
	            }
	            $data .= "</tbody>";
	            return array('code' => 1,'msg' => $data);
	        }else{
	            return array('code' => 2,'msg' =>'暂无信息');
	        }
		}
		if (input('?post.number') != false) {
			$number = input('post.number');
			if (!$_POST['unit_id']) {
				$_POST['unit_id'] = $userMsg['id'];
			}
			$_POST['groupId'] = $_POST['unit_id'];
			$_POST['createTime'] = date('Y-m-d H:i:s',time());
			$_POST['updateTime'] = date('Y-m-d H:i:s',time());
			unset($_POST['unit_id']);
			foreach ($_POST as $key => $value) {
				$obj->$key=$value;
			}
			$data = json_encode($obj);
			$url = config('path')."/equipment/insert";
			$res = http_request($url,$data,1);
			$res =  json_decode($res,true);
			if ($res == 1) {
				return array('code' => 1);
			}else{
				return array('code' => 2);
			}
			die;
			//添加设备操作(接口)
			// return array('code' => '1','msg' => '健康源,机构编号:1');
		}
		return $this->fetch();
	}

	//删除,解绑设备
	public function device_remove(){
		//获取设备编号或id,调用接口进行解绑,删除操作
		$ids = $_POST['ids'];
		$data = json_encode($ids);
		$url = config("path")."/equipment/delete";
		$res = http_request($url,$data,1);
		$res = json_decode($res,true);
		$len = count($ids);
		if ($res and !$res['error']) {
			return array('code' => 1,'len' => $len);
		}else{
			return array('code' => 2,'msg' => $res['message']);
		}
	}

	//用户绑定/解绑设备
	public function bangding(){
        $userMsg = Session::get('userMsg');
		//解绑设备
		if (input('post.remove') == 1) {
			$dataid = input('post.id');
			$p_id = input('post.p_id');
			//解绑(接口)
			$url = config('path')."/equipment/undoBangdingPatient";
			$data['id'] = $dataid;
			$res = http_request($url,$data);
			$res = json_decode($res,true);
			if ($res AND !$res['error']) {
				return array('code' => '1','msg' => '解绑成功');
			}else{
				return array('code' => '2','msg' => $res['message']);
			}
			die;
		}
		//绑定设备
		if (input('post.bangding') == 1) {
			$data['id'] = input('post.id');
			$data['patientId'] = input('post.p_id');
			//绑定(接口)
			$url = config('path')."/equipment/bangdingPatient";
			$res = http_request($url,$data);
			$res = json_decode($res,true);
			if ($res AND !$res['error']) {
				return array('code' => '1','msg' => '绑定成功');
			}else{
				return array('code' => '2','msg' => $res['message']);
			}
			die;
		}
		//替换以前的设备
		if (input('post.change') == 1) {
			$data['patientId']=$_POST['p_id'];
			$data['id']=$_POST['id'];
			$old_id = $_POST['old_id'];
			//解绑old_id(接口)
			$relieve_url = config('path')."/equipment/undoBangdingPatient";
			$relieve_data['id'] = $old_id;
			$relieve_res = http_request($relieve_url,$relieve_data);
			if (!$relieve_res['error']) {
				//绑定$data['id'](接口)
				$bangding_url = config('path')."/equipment/bangdingPatient";
				$bangding_res = http_request($bangding_url,$data);
				$bangding_res = json_decode($bangding_res,true);
				if ($bangding_res AND !$bangding_res['error']) {
					return array('code' => '1','msg' => '绑定成功');
				}else{
					return array('code' => '2','msg' => $bangding_res['message']);
				}
			}else{
				return array('code' => '1','msg' => $relieve_res['message']);
			}
			die;
		}
		// //搜索
		// if (input('?post.pid') and input('post.info') == 1) {
		// 	$pid = input('post.pid');
		// 	$select = input('post.select');
		// 	//根据搜索内容查询设备列表(接口)
			
		// 	die;
		// }

		$id = input('id');
		//获取用户已绑定设备
		$url = config("path")."/equipment/patientEquipment?patientId=".$id;
		$res = http_request($url);
		$user_el = json_decode($res,1);
		if ($user_el and !$user_el['error']) {
			foreach ($user_el as $key => $value) {
				$user_type[$value['type']] = $value['id'];
				$user_el[$key]['type'] = config('EQUIPMENT')[$value['type']];
			}
			$this->assign('user_el',$user_el);
			$this->assign('user_type',$user_type);
		}
		//获取未绑定的设备
		$url = config('path')."/equipment/getConditionEquipment?adminId=".$userMsg['id'];
		$res = http_request($url);
		$res = json_decode($res,true);
		if (!$res['error']) {
			foreach ($res as $key => $value) {
				$res[$key]['type_name'] = config('EQUIPMENT')[$value['type']];
			}
			$this->assign('list',$res);
		}
		$this->assign('id',$id);
		return $this->fetch();
	}

	
}