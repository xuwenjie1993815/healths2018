<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use app\admin\model\User;
use think\Db;
class Warning extends Controller{
	//获取预警列表
	public function index(){
		//接口获取列表
		
		return $this->fetch();
	}
}