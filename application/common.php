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


const DS = DIRECTORY_SEPARATOR;
/**
  * 截取指定文本长度补tail...
  *
  * @param $sourcestr string
  * @param $cutlength int
  * @return string
  */
function cut_str($sourcestr, $cutlength)
{
   	$returnstr = '';
   	$i = 0;
   	$n = 0;
   	$str_length = strlen($sourcestr);//字符串的字节数
   	while ( ($n < $cutlength) and ($i <= $str_length) ) {
      	$temp_str = substr($sourcestr,$i,1);
     	//得到字符串中第$i位字符的ascii码
      	$ascnum = Ord($temp_str);
	    if ( $ascnum >= 224 ) {   	//如果ASCII位高与224，
			//根据UTF-8编码规范，将3个连续的字符计为单个字符
			$returnstr = $returnstr.substr($sourcestr,$i,3);       
	        $i = $i + 3;            //实际Byte计为3
	        $n++;            		//字串长度计1
	    }
      	elseif ( $ascnum >= 192 ) { //如果ASCII位高与192，
      		//根据UTF-8编码规范，将2个连续的字符计为单个字符
	        $returnstr = $returnstr.substr($sourcestr,$i,2); 
	        $i = $i + 2;            //实际Byte计为2
	        $n++;            	  	//字串长度计1
      	}
      	elseif ( $ascnum >= 65 && $ascnum <= 90 ) {//如果是大写字母，
        	$returnstr = $returnstr.substr($sourcestr,$i,1);
        	$i = $i + 1;            //实际的Byte数仍计1个
        	$n++; 					//但考虑整体美观，大写字母计成一个高位字符
      	} else {
      		//其他情况下，包括小写字母和半角标点符号，
        	$returnstr = $returnstr.substr($sourcestr,$i,1);
        	$i = $i + 1;            //实际的Byte数计1个
        	$n = $n + 0.5;       	//小写字母和半角标点等与半个高位字符宽…
      	}
   	}
    if ($str_length > $i){
      $returnstr = $returnstr . "…";//超过长度时在尾处加上省略号
  	}
    return $returnstr;
}

/**
  * 获取文件路径
  *
  * @param path string
  * @return $arrayName array
  */
function getFiles($path)
{
	$handler = opendir($path);		//当前目录中的文件夹下的文件夹
	$files = [];
	while( ($filename = readdir($handler)) !== false ) {
      if ( $filename != "." && $filename != ".." ) {
        $files[] = $filename;
      }
 	}
 	return $files;
}

/**
  * 普通加密
  *
  * @param value string
  * @param type number 0: 加密 1:解密
  * @return value string;
  */
function encrypt($value='0', $type=0)
{
  //public key
  $key = 'LJOZsdfJDgpIU57s8dg6J4';
  return $type ? base64_decode($value) ^ md5($key) : str_replace('=', '', base64_encode($value ^ md5($key)));
}

