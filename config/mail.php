<?php
/**
 * Created by PhpStorm.
 * User: Jiawei
 * Date: 2020/5/25
 * Time: 8:54
 */
/**
 * 邮件服务相关配置
 */
return [
	// 邮件编码
	'charset' => 'utf-8',
	// Debug模式。0: 关闭，1: 客户端消息，2: 客户端和服务器消息，3: 2和连接状态，4: 更详细
	'smtp_debug' => 0,
	// Debug输出类型。`echo`（默认）,`html`,或`error_log`
	'debug_output' => 'html',
	// SMTP服务器地址
	'host' => 'smtp.126.com',
	// 端口号。默认25
	'port' => 465,
	// 启用SMTP认证
	'smtp_auth' => true,
	// 启用安全协议。''（默认）,'ssl'或'tls'，留空不启用
	'smtp_secure' => 'ssl',
	// SMTP登录邮箱
	'username' => 'y2219531345@126.com',
	// SMTP登录密码。126邮箱使用客户端授权码，QQ邮箱用独立密码
	'password' => 'yjw917',
	// 发件人邮箱
	'from' => 'y2219531345@126.com',
	// 发件人名称
	'from_name' => 'huaxia_test',
	// 回复邮箱的地址。留空取发件人邮箱
	'reply_to' => '',
	// 回复邮箱人名称。留空取发件人名称
	'reply_to_name' => ''
];