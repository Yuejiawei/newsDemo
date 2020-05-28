<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function getLabel($label){
	# label标签表示
	#0表示检验/认证/司法鉴定/其它，#1表示工程与材料测试，2表示轻工与消费品，
	#3表示健康与环境，4表示食品与农产品，5表示医药与生物化学，6表示机械机电，
	#7表示交通运输，8表示电子电信，9表示地矿与石油化工，10表示特种设备，11表示计量检定校准
	/**
	 * return str($label)
	 */
		switch($label){
			case '0':
				$str = "检验/认证/司法鉴定/其它";
				break;
			case '1':
				$str = "工程与材料测试";
				break;
			case '2':
				$str = "轻工与消费品";
				break;
			case '3':
				$str = "健康与环境";
				break;
			case '4':
				$str = "食品与农产品";
				break;
			case '5':
				$str = "医药与生物化学";
				break;
			case '6':
				$str = "机械机电";
				break;
			case '7':
				$str = "交通运输";
				break;
			case '8':
				$str = "电子电信";
				break;
			case '9':
				$str = "地矿与石油化工";
				break;
			case '10':
				$str = "特种设备";
				break;
			case '11':
				$str = "计量检定校准";
				break;
			default:
				$str = "未知类型";
				break;
	}
	return $str;
}
function getCategoryStr($type){
	//  0表示曝光台，1表示文件发布，2表示企业公示，3表示行业资讯，4表示会议展览
	//  return str($type)
	switch ($type){
		case 0:
			$str = "曝光台";
			break;
		case 1:
			$str = "文件发布";
			break;
		case 2:
			$str = "企业公示";
			break;
		case 3:
			$str = "行业资讯";
			break;
		case 4:
			$str = "会议展览";
			break;
		default:
			$str = "未知类型";
			break;
	}
	return $str;
}
