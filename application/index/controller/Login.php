<?php
/**
 * Created by PhpStorm.
 * User: Jiawei
 * Date: 2020/5/25
 * Time: 23:40
 */

namespace app\index\controller;
use think\App;
use think\facade\Request;
use app\index\model\UserModel;
use think\Controller;

class Login extends Controller {
	public function __construct(App $app = null) {
		parent::__construct($app);
		$this->model = new UserModel;
	}

	public function login(){
		if(Request::isPost()){
//			print_r(input('post.'));exit(0);
			$account = input('post.account');
			$pwd = input('post.password');
			$loginType = input('post.loginType');
			# 因tp5 session过期时间是在是个bug，暂时省略七天免登陆功能
			$longlog = input('post.longlog') ? input('post.longlog') : 'off';
			if($loginType == 'phone'){
				$correctPhone = preg_match('/^1[3456789]{1}\d{9}$/',$account);
				if($correctPhone){
					$phone = $this->model->getOneColumnData('phone_number',$account,'phone_number');
					if($phone){
						$pwd_user = $this->model->getOneColumnData('phone_number',$account,'pwd');
						$pwd_salt = $this->model->getOneColumnData('phone_number',$account,'pwd_salt');
						if($pwd_user == md5($pwd.$pwd_salt)){
							$username = $this->model->getOneColumnData('phone_number',$account,'name');
							session('user',$username,'think');
							return json([
								"status"=>200,
								"info"=> "登陆成功"
							]);
					}else {
							return json([
								"status"=>500,
								"info"=>"密码输入错误"
							]);
						}
					}else{
						return json([
							"status"=>500,
							"info"=>"手机号尚未注册"
						]);
					}
				}else{
					return json([
						"status"=> 500,
						"info"=>"手机号格式有误"
					]);
				}
			}elseif ($loginType == 'email'){
				$correctEmail = preg_match('/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/',$account);
				if($correctEmail){
					$email = $this->model->getOneColumnData('email',$account,'email');
					if($email){
						$pwd_user = $this->model->getOneColumnData('email',$account,'pwd');
						$pwd_salt = $this->model->getOneColumnData('email',$account,'pwd_salt');
						if($pwd_user == md5($pwd.$pwd_salt)){
							$username = $this->model->getOneColumnData('email',$account,'name');
							session('user',$username,'think');
							return json([
								"status"=>200,
								"info"=> "登陆成功"
							]);
						}else {
							return json([
								"status"=>500,
								"info"=>"密码输入错误"
							]);
						}
					}else{
						return json([
							"status"=>500,
							"info"=>"邮箱尚未注册"
						]);
					}
				}else{
					return json([
						"status"=> 500,
						"info"=>"邮箱格式错误"
					]);
				}
			}else{
				if(strlen($account) < 6 || strlen($account)>20 || strlen($pwd) <6 || strlen($pwd)>16){
					return json([
						"status"=> 500,
						"info"=>"用户名或密码格式错误"
					]);
				}
				$user = $this->model->getOneColumnData('name',$account,'name');
				if($user){
					$pwd_user = $this->model->getOneColumnData('name',$account,'pwd');
					$pwd_salt = $this->model->getOneColumnData('name',$account,'pwd_salt');
					if($pwd_user == md5($pwd.$pwd_salt)){
						session('user',$account,'think');
						return json([
							"status"=>200,
							"info"=> "登陆成功"
						]);
					}else{
						return json([
							"status"=> 500,
							"info"=> "密码输入错误"
						]);
					}
				}else{
					return json([
						"status"=> 500,
						"info"=>"用户名不存在"
					]);
				}
			}
		}else{
			if(session('user','','think')){
				return redirect('index/news');
			}
			return $this->fetch();
		}
	}
	# 验证码方式登录
	public function loginConfirm(){
		if(Request::isPost() && input('post.smscode')){
			$acccount = input('post.mobile');
			$smscode = input('post.smscode');
			$loginType = input('post.loginType');
			if($loginType == "phone"){
				$row = $this->model->getOneColumnData('phone_number',$acccount,'email_code');
				if($row){
					$res = $smscode == $row ? true : false;
					if($res){
						$name= $this->model->getOneColumnData('phone_number',$acccount,'name');
						session('user',$name,'think');
						return json([
							"status"=>200,
							"info"=>"登陆成功"
						]);
					}else{
						return json([
							"status"=>500,
							"info"=>"验证码错误"
						]);
					}
				}else{
					return json([
						"status"=>500,
						"info"=>"手机号尚未注册"
					]);
				}
			}elseif ($loginType=="email"){
				$row = $this->model->getOneColumnData('email',$acccount,'email_code');
				if($row){
					$res = $smscode == $row ? true : false;
					if($res){
						$name= $this->model->getOneColumnData('email',$acccount,'name');
						session('user',$name,'think');
						return json([
							"status"=>200,
							"info"=>"登陆成功"
						]);
					}else{
						return json([
							"status"=>500,
							"info"=>"验证码错误"
						]);
					}
				}else{
					return json([
						"status"=>500,
						"info"=>"邮箱尚未注册"
					]);
				}
			}else{
				$row = $this->model->getOneColumnData('name',$acccount,'email_code');
				if($row){
					$res = $smscode == $row ? true : false;
					if($res){
						session('user',$acccount,'think');
						return json([
							"status"=>200,
							"info"=>"登陆成功"
						]);
					}else{
						return json([
							"status"=>500,
							"info"=>"验证码错误"
						]);
					}
				}else{
					return json([
						"status"=>500,
						"info"=>"用户名不存在"
					]);
				}
			}
		}else{
			return json([
				"status"=>500,
				"info"=>"登录方式有误"
			]);
		}
	}
	public function check_login(){
		if(Request::isPost()){
			$type = input('post.action');
			if($type == "check_login" and !session('user','','think')){
				return "logging";
			}else{
				return "logged";
			}
		}else{
			return "fail";
		}
	}
	public function logout(){
		// 删除（当前作用域）
		session('user', null);
		// 清除session（当前作用域）
		session(null);
		// 清除think作用域
		session(null, 'think');
		$this->redirect('index/news');
	}
}