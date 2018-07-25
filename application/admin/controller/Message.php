<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
class Message extends Controller
{	
	//留言板列表
	public function index(){
		$url = config('path')."/board/question/all";
		$res = http_request($url);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			//根据问题id获取回复的内容
			foreach ($res as $key => $value) {
				$answerUrl = config('path')."/board/question/".$value['id'];
				$answerRes = http_request($answerUrl);
				$answerRes = json_decode($answerRes,true);
				if ($answerRes AND !$answerRes['error']) {
					$res[$key]['amswer'] = $answerRes['answerMappers'];
				}
			}			
			$this->assign('MessageList',$res);
			return $this->fetch();
		}
		// $MessageList = array('1' => array('id' => '12','content' => '隔壁张大妈养的猪被偷了','sign'=>'','username' => '黄淑碧', 'type' => '1','ctime' => '1530586313','status' => '1'),'2' => array('id' => '12','content' => '隔壁张大妈养的猪被偷了第二遍','sign' => '你是不是傻','username' => '黄淑碧', 'type' => '1','ctime' => '1530586313','status' => '2'));
		// $this->assign('MessageList',$MessageList);
		// return $this->fetch();
	}

	//回复
	public function reply(){
		if ($_POST) {
			$data['questionId'] = $_POST['id'];
			$data['content'] = $_POST['content'];
			//回答的类型：1服务团队；2医学团队；3技术团队；4客服
			$data['answerType'] = $_POST['answerType'];
			var_dump($data);die;
			foreach ($data as $key => $value) {
				$obj->$key=$value;
			}
			$data = json_encode($obj);
			$url = config('path')."/board/answer";
			$res = http_request($url,$data,1);
			$res = json_decode($res,true);
			if ($res AND !$res['error']) {
				return array('code' => 1);
			}else{
				return array('code' => 2,'msg' => $res['message']);
			}
			die;
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

	//查看回复
	public function check_amswer(){
		$answerUrl = config('path')."/board/question/".input('id');
		$answerRes = http_request($answerUrl);
		$answerRes = json_decode($answerRes,true);
		if ($answerRes AND !$answerRes['error']) {
			$this->assign('list',$answerRes['answerMappers']);
		}
		return $this->fetch();
	}
}