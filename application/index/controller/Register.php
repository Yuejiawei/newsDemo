<?php
/**
 * Created by PhpStorm.
 * User: Jiawei
 * Date: 2020/5/23
 * Time: 22:11
 */

namespace app\index\controller;
use app\index\model\UserModel;
use think\App;
use think\Controller;
use think\facade\Config;
use think\facade\Request;
use \vendor\Mail;

class Register extends Controller {
	public function __construct(App $app = null) {
		parent::__construct($app);
		$this->model = new UserModel;
	}

	public function reg(){
		if(session('user','','think')){
			return redirect('index/news');
		}
		return $this->fetch();
	}
//	public function test(){
//		$mail = new \Mail();
//		$res = $mail->sendMail("2219531345@qq.com", "Jiawei", "我来了，哈哈", "这是主题", $attachment = null);
//		if($res){
//			echo "<script>alert('邮件发送成功');</script>";
//		}else{
//			var_dump($res);
//		}
//		exit(0);
//	}
	public function checkEmailUsed(){
		if(Request::isPost()){
			$email = input('post.email');
			$result = preg_match('/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/',$email);
			if($result){
				$bool = $this->model->getUserEmail($email);
				if(!$bool){
					return false;
				}else{
					return true;
				}
			}
			return false;
		}
		return false;
	}
	public function checkPhoneUsed(){
		# 已存在返回False
		if(Request::isPost()){
			$phone = input('post.phone');
			$bool = $this->model->getUserPhone($phone);
			if(!$bool){
				return json(false);
			}else{
				return json(true);
			}

		}
		return false;
	}
	public function insert(){
		$data = [
			"email"=>"2218543432@qq.com",
			"email_code"=>"erf32d"
		];
		$row = $this->model->save($data);
		echo $row;
		if($row){
			return '123';
		}

	}
	public function verifyCodeLogin(){
//		print_r(input('post.'));
//		return json([
//			"status" => 200,
//			"info" => "验证码已发送至您的邮箱"
//		]);
		if(Request::isPost() && input('post.loginType')){
			$loginType = input('post.loginType');
			$account = input('post.account');
//			var_dump($account);
//			exit(0);
			$mail = new \Mail();
			if($loginType == "phone"){
				$email = $this->model->getOneColumnData('phone_number',$account,'email');
			}else{
				$email = $account;
			}
			if($email){
				$code = $this->generate_code();
				$res = $mail->sendMail($email,"亲爱的用户","邮箱检验码提醒",
					"您的邮箱校验码为 ".$code." 。若不是您本人在操作，请忽略！");
				if($res){
					$row = $this->model->save(["email_code"=>$code],["email"=>$email]);
					if($row){
						return json([
							"status" => 200,
							"info" => "验证码已发送至您的邮箱"
						]);
					}else{
						return json([
							"status" => 500,
							"info" => "验证码保存失败，请稍后重试"
						]);
					}
				}else{
					return json([
						"status"=>500,
						"info"=>"邮件发送失败"
					]);
				}
			}else{
				return json([
					"status"=>500,
					"info"=>"邮箱获取失败"
				]);
			}
		}else{
			return json([
				"status"=>500,
				"info"=>"登录参数错误"
			]);
		}
	}
	public function verifyCode(){
		if(Request::isPost() && input('post.codeType') == "reg"){
			$email = input('post.email');
			$mail = new \Mail();
			$code = $this->generate_code();
			$res = $mail->sendMail($email,"亲爱的用户","邮箱检验码提醒",
				"您的邮箱校验码为 ".$code." 。若不是您本人在操作，请忽略！");
			if($res){
				# 将验证码和邮箱入库，暂存为一条记录
				$row = $this->model->save([
					"email" => $email,
					"email_code" => $code
				]);
				if($row){
					return json([
						"status" => 200,
						"info" => "验证码已发送至您的邮箱"
					]);
				}else{
					return json([
						"status" => 500,
						"info" => "验证码保存失败，请稍后重试"
					]);
				}
			}else{
				return json([
					"status" => 500,
					"info" => "验证码发送失败，请稍后重试"
				]);
			}
		}else{
			return false;
		}
	}
	public function commitInfo($username,$email,$msgno,$password,$mobilephone,$invitecode="",$regtype){
		if(!$this->model->getUserEmail($email) && $regtype == 'email'){
			if($this->judgeInfo($username,$email,$msgno,$password,$mobilephone,$invitecode)){
				$salt = $this->generate_code();
				$row = $this->model->save([
					"name"=>$username,
					"email_code"=>$msgno,
					"pwd_ori"=>$password,
					"pwd" => $this->generate_pwd($password,$salt),
					"pwd_salt" => $salt,
					"phone_number"=>$mobilephone,
					"invite_code"=>$invitecode
				],["email"=>$email,"email_code"=>$msgno]);
				if($row){
					return json([
						"status" => 200,
						"info"=>"注册成功",
					]);
				}else{
					return json([
						"status" => 500,
						"info"=>"异常错误，请重试"
					]);
				}
			}else{
				return json([
					"status" => 500,
					"info"=>"信息有误，请重新核对"
				]);
			}
		}else{
			return json([
				"status" => 500,
				"info"=>"未知错误，请重试"
			]);
		}
	}
	public function generate_pwd($pwd,$salt){
		return md5($pwd.$salt);
	}

	public function judgeInfo($username,$email,$msgno,$password,$mobilephone,$invitecode){
		if(strlen($username) < 6 || strlen($username) > 20){
			return false;
		}
		if(!preg_match('/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/',$email)){
			return false;
		}
		if(strlen($msgno) != 6 ||  $this->model->getUserMsgNO($email) != $msgno){
			return false;
		}
		if(strlen($password) < 6 || strlen($password) >16){
			return false;
		}
		if(!preg_match('/^1[3456789]{1}\d{9}$/',$mobilephone)){
			return false;
		}
		if($invitecode){
			if(!Config::get('info.invite_code') == $invitecode){
				return false;
			}else{
				return true;
			}
		}
		return true;
	}
	public function generate_code(){
		$code = "";
		// 无 l o  4(共33字符)
		$pattern='123567890abcdefghijkmnpqrstuvwxyz';
		for($i=0;$i<6;$i++){
			$code .= $pattern[mt_rand(0,32)];
		}
		return $code;
	}
	public function sendCode(){
		if(Request::isPost()){
			$phone = input('post.phone');
			$email = $this->model->getOneColumnData('phone_number',$phone,'email');
			if($email){
				$mail = new \Mail();
				$code = $this->generate_code();
				$res = $mail->sendMail($email,"亲爱的用户","邮箱检验码提醒",
					"您的邮箱校验码为 ".$code." 。若不是您本人在操作，请忽略！");
				if($res){
					$row = $this->model->save(["email_code"=>$code],["email"=>$email]);
					if($row){
						return json([
							"status" => 200,
							"info" => "验证码已发送至您的邮箱"
						]);
					}else{
						return json([
							"status" => 500,
							"info" => "数据更新失败"
						]);
					}
				}else{
					return json([
						"status"=>500,
						"info"=>"验证码发送失败"
					]);
				}
			}else{
				return json([
					"status"=>500,
					"info"=>"账户不存在"
				]);
			}
		}else{
			return json([
				"status"=>500,
				"info"=>"非法请求"
			]);
		}
	}

	public function resetpassword(){
		if(Request::isPost()){
			$phone = input('post.mobile');
			$newpwd = input('post.newpasswd');
			$smscode = input('post.smscode');
			$code = $this->model->getOneColumnData('phone_number',$phone,'email_code');
			if($code != $smscode){
				return json([
					"status"=>500,
					"info"=>"验证码不匹配"
				]);
			}
			$pwd = $this->model->getOneColumnData('phone_number',$phone,'pwd');
			$pwd_salt = $this->model->getOneColumnData('phone_number',$phone,'pwd_salt');
			if($pwd == $this->generate_pwd($newpwd,$pwd_salt)){
				return json([
					"status"=>500,
					"info"=>"密码相同，修改失败"
				]);
			}
			$new_salt = $this->generate_code();
			$new_pwd = $this->generate_pwd($newpwd,$new_salt);
			$row = $this->model->save(["pwd_ori"=>$newpwd,"pwd"=>$new_pwd,'pwd_salt'=>$new_salt],["phone_number"=>$phone]);
			if($row){
				return json([
					"status"=>200,
					"info"=>"密码修改成功"
				]);
			}else{
				return json([
					"status"=>500,
					"info"=>"密码未修改"
				]);
			}
		}else{
			return json([
				"status"=>500,
				"info"=>"非法请求"
			]);
		}
	}
	public function duty1(){
		return $this->fetch();
	}

	public function duty2(){
		return $this->fetch();
	}

}