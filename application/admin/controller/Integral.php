<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
use app\admin\model\User;
class Integral extends Controller
{
	public function index(){
		return $this->fetch();
	}

	//兑换积分
	public function integral_exchange(){
		$id = input('id');
		$this->assign('id',$id);
		if ($_POST) {
			$id = $_POST['id'];
			$integral = $_POST['integral'];
			$remark = $_POST['remark'];
			//兑换积分(接口)
		}
		//兑换抛错/对
		return $this->fetch();
	}

	//历史记录
	public function integral_record(){
		return $this->fetch();
	}
}