<?php
namespace app\index\controller;
use think\View;
use think\Controller;
use think\Session;
use app\index\controller\Base;
class Message extends Base
{
	public function index(){
		return $this->fetch();
	}
}