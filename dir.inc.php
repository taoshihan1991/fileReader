<?php
date_default_timezone_set('PRC');
define('PATH','file/');//定义管理的目录

$data=readDirectory(PATH);

$path=isset($_REQUEST['path']) ? $_REQUEST['path'] : PATH;
$act=isset($_REQUEST['act']) ? $_REQUEST['act'] : '';
/*创建文件*/
if($act=='createFile'){
  $path=$_POST['path'];
  $fileName=$_POST['fileName'];
  $info=createFile($path.$fileName);
  exit($info);
}
/*查看文件*/
if($act=='showContent'){
  $filePath=PATH.$_GET['fileName'];
  $filePath=iconv("utf-8","gb2312",$filePath);
  $info=file_get_contents($filePath);
  $newContent=highlight_string($info,true);
  $str=<<<EOF
  {$newContent}
EOF;
  exit($str);
}

/**
 * 读目录函数
 */
function readDirectory($path){
  $handle=opendir($path);
  $arr=array();
  while(($item=readdir($handle))!==false){
    
    if($item!='.'&&$item!='..'){
      
      $info=array();
      $filePath=PATH.$item;
      $ext=filetype($filePath);
      $info['ext']=$ext;

      //文件大小
      $size=filesize($filePath);
      $info['size']=transBytes($size);

      //是否可读
      if(is_readable($filePath)) $info['readable']=true;
      //是否可写
      if(is_writable($filePath)) $info['writable']=true;
      //是否可执行
      if(is_executable($filePath)) $info['executable']=true;
      //创建时间
      $info['ctime']=date("Y-m-d H:i:s",filectime($filePath));

      $info['name']=$item;

      if(is_dir($filePath)){
        $info['type']='dir';
      }
      if(is_file($filePath)){
        $info['file']='file';
      }

      $info['name']=iconv("gb2312","utf-8",$info['name']);
      $arr['file'][]=$info;
    }
  }
  closedir($handle);
  return $arr;
}
/**
 * 转换字节大小
 */
function transBytes($size){
  $arr=array('B','KB','MB','GB','TB','EB');
  $i=0;
  while($size>=1024){
    $size/=1024;
    $i++;
  }
  return round($size,2).$arr[$i];
}
/**
 * 创建文件
 */
function createFile($fileName){
  if(preg_match('/[\/,\*,<,>,\?,\|]/',basename($fileName))) return '文件名中包含非法字符/,*,<,>,?,|';
  if(file_exists($fileName)) return '文件名已存在,请重命名后提交';
  $fileName=iconv("utf-8","gb2312",$fileName);
  if(touch($fileName)){
    return '文件创建成功';
  }else{
    return '文件创建失败';
  }
}
?>