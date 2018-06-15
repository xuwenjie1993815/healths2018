<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use app\admin\model\User;
use think\Db;
class Jurisdiction extends Controller{
	public function index(){
		return $this->fetch();
	}
}