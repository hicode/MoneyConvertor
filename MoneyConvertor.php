<?php

/** 
 * MoneyConvertor Library For PHP
 * 人民币大小写转换类
 * ---------------------------------------------------
 * @author	解海 <xiehai@vip.qq.com>
 * @link	http://www.hiceon.com/topic/moneyconvertor
 * 
 */

final class MoneyConvertor {
	
	//大写数字
	private $NUMBER_STR = array(
		"零",
		"壹",
		"贰",
		"叁",
		"肆",
		"伍",
		"陆",
		"柒",
		"捌",
		"玖"
	);
	
	//整数位货币单位
	private $I_UNIT_STR = array(
		"元",
		"拾",
		"佰",
		"仟",
		"万",
		"拾",
		"佰",
		"仟",
		"亿",
		"拾",
		"佰",
		"仟"
	);
	
	//小数位货币单位
	private $D_UNIT_STR = array(
		"角",
		"分",
		"厘"
	);
	
	//转换结果
	private $resultString = null;

	/**
	 * 使用一个小写数字金额的字符串来转换resultString对象
	 * ---------------------------------------------------
	 * @param	$numberStr		将要转换的小写数字金额
	 * @return 	$this->resultString
	 */

	public function convert($numberStr) {
		
		//处理小数位为0
		if(preg_match('/^[0-9]+\.[0]+$/',$numberStr))
			$numberStr = intval($numberStr);
		
		//补齐类似.5这样的无整数位数字
		if(substr($numberStr, 0, 1) == '.')
			number_format($numberStr);
		
		//如果带逗号分隔符的数字
		if(strpos($numberStr, ','))
			$numberStr = str_replace(",","",$numberStr);
		
		//判断是否为数字
		if (!is_numeric($numberStr))
			return '不是有效的货币数值';
		
		//执行转换
		self::convertor($numberStr);
		
		//返回转换结果
		return $this->resultString;
	}
	
	
	/**
	 * 执行转换
	 * ---------------------------------------------------
	 * @param	$numberStr		将要转换的小写数字金额
	 * @return 	void
	 */
	private function convertor($numberStr){
		//分差整数与浮点位，整数和小数部分分开，分别进行转换
		$cutedNumber = explode('.', (string)$numberStr);

		//如果只有整数部分
		if (count($cutedNumber) == 1) {
			self::convertInteger($numberStr, TRUE);
		} else {
			self::convertInteger($cutedNumber[0]);
			self::convertDecimal($cutedNumber[1]);
		}

		//去除无用零字符
		self::removeZero();
	}
	
	

	/**
	 * 对整数部分进行转换
	 * ------------------------------------------------------------------
	 * @param	$integer				将要转换的小写数字整数部分
	 * @param	$without_fractional		是否原数不带浮点数，即在最后显示“整”
	 *
	 * @return 	$this
	 */
	private function convertInteger($integer, $without_fractional = false) {
		$resultString = null;
			
		for ($i = 0; $i < strlen($integer); $i++) {
			$resultString .= $this->I_UNIT_STR[$i];
			$resultString .= $this->NUMBER_STR[substr(strrev($integer), $i, 1)];
		}
		//如果没有小数位
		$tidy = $without_fractional == false ? '' : '整';
		$this->resultString = self::str_reverse($resultString) . $tidy;

		return $this;
	}

	/**
	 * 对小数点后三位部分进行转换
	 * ------------------------------------------------------------------
	 * @param	$integer				将要转换的小数点后三位部分
	 * @return 	$this
	 */
	private function convertDecimal($decimal) {
		
		$resultString = null;
		
		for ($i = 0; $i < strlen($decimal); $i++) {
			$resultString .= $this->NUMBER_STR[substr($decimal, $i, 1)];
			$resultString .= $this->D_UNIT_STR[$i];
		}
		$this->resultString .= $resultString;

		return $this;
	}

	/**
	 * 去掉多余的"零X"
	 * ------------------------------------------------------------------
	 * @return 	$this
	 */
	private function removeZero() {
		while (strpos($this->resultString, "零拾") || strpos($this->resultString, "零佰") || strpos($this->resultString, "零仟") || strpos($this->resultString, "零万") || strpos($this->resultString, "零亿") || strpos($this->resultString, "零角") || strpos($this->resultString, "零分") || strpos($this->resultString, "零厘") || strpos($this->resultString, "零零") || strpos($this->resultString, "亿万") || strpos($this->resultString, "零元")) {
			$this->resultString = str_replace("零拾", "零", $this->resultString);
			$this->resultString = str_replace("零佰", "零", $this->resultString);
			$this->resultString = str_replace("零仟", "零", $this->resultString);
			$this->resultString = str_replace("零万", "万", $this->resultString);
			$this->resultString = str_replace("零亿", "亿", $this->resultString);
			$this->resultString = str_replace("零角", "零", $this->resultString);
			$this->resultString = str_replace("零分", "零", $this->resultString);
			$this->resultString = str_replace("零厘", "零", $this->resultString);
			$this->resultString = str_replace("零零", "零", $this->resultString);
			$this->resultString = str_replace("亿万", "亿", $this->resultString);
			$this->resultString = str_replace("零元", "元", $this->resultString);
		}

		return $this;
	}

	/**
	 * 中文UTF-8字符串反转
	 * ------------------------------------------------------------------
	 * @param 	$str	需要转换的UTF-8字符串
	 * @return 	void
	 */
	function str_reverse($str) {
		//判断输入的是不是utf8类型的字符，否则退出
		if (!is_string($str) || !mb_check_encoding($str, 'UTF-8')) {
			return;
		}
		$array = array();
		//将字符串存入数组
		$l = mb_strlen($str, 'UTF-8');
		for ($i = 0; $i < $l; $i++) {
			$array[] = mb_substr($str, $i, 1, 'UTF-8');
		}
		//反转字符串
		krsort($array);
		//拼接字符串
		$string = implode($array);
		return $string;
	}

}
