<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
use app\admin\model\User;
class Message extends Controller
{	
	//留言板列表
	public function index(){
		$MessageList = array('1' => array('id' => '12','content' => '隔壁张大妈养的猪被偷了','sign'=>'','username' => '黄淑碧', 'type' => '1','ctime' => '1530586313','status' => '1'),'2' => array('id' => '12','content' => '隔壁张大妈养的猪被偷了第二遍','sign' => '你是不是傻','username' => '黄淑碧', 'type' => '1','ctime' => '1530586313','status' => '2'));
		$this->assign('MessageList',$MessageList);
		return $this->fetch();
	}

	//回复
	public function reply(){
		if ($_POST) {
			$message_id = $_POST['id'];
			$content = $_POST['content'];
			//提交回复
			return array('code' => 1);
		}
		$this->assign('id',input('id'));
		return $this->fetch();
	}

	//标记处理
	public function sign(){
		$id = $_POST['id'];
		//处理
		return array('code' => 1);
	}

	//删除留言
	public function message_del(){
		$message_id = $_POST['id'];
		//删除
		return array('code' => 1);
	}
}