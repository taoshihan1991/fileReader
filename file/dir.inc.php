<?php
/**
 * PHP文件管理器
 * @author 陶士涵
 */
date_default_timezone_set('PRC');
ini_set("magic_quotes_gpc",0);
header("content-Type: text/html; charset=Utf-8"); 

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
  $filePath=iconv("utf-8","gbk//IGNORE",$filePath);

  $str=showFile($filePath);
  exit($str);
}

/*编辑文件*/
if($act=='editContent'){
  $filePath=PATH.$_GET['fileName'];
  $filePath=iconv("utf-8","gbk//IGNORE",$filePath);

  if(isImage($filePath)){
    $info="图片文件不能编辑";
  }else{
    $info=file_get_contents($filePath);
  }
  
  exit($info);
}

/*处理编辑文件*/
if($act=='doEditContent'){
  exit("sorry,怕有安全问题,关掉了");
  $filePath=PATH.$_POST['fileName'];
  $filePath=iconv("utf-8","gbk//IGNORE",$filePath);
  $fileContent=stripcslashes($_POST['fileContent']);

  if(isImage($filePath)){
    exit("图片文件不能编辑");
  }else{
    $res=file_put_contents($filePath,$fileContent);
  }

  if($res){
    exit("文件修改成功");
  }else{
    exit("文件修改失败");
  }

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

      $info['name']=iconv("gbk//IGNORE","utf-8",$info['name']);
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
 * 显示文件内容
 */
function showFile($file){
  //图片
  if(isImage($file)){
    $file=iconv("gbk","utf-8",$file);
    $resStr="<img src='{$file}' class='img-responsive' alt='Responsive image'>";
  }else{

    $info=file_get_contents($file);
    if(empty($info)) exit('文件没有内容,请编辑后查看');
    $newContent=highlight_string($info,true);
    $resStr=<<<EOF
      {$newContent}
EOF;

  }

  return $resStr;
}
/**
 * 创建文件
 */
function createFile($fileName){
  exit("sorry,怕有安全问题,关掉了");
  if(preg_match('/[\/,\*,<,>,\?,\|]/',basename($fileName))) return '文件名中包含非法字符/,*,<,>,?,|';
  if(file_exists($fileName)) return '文件名已存在,请重命名后提交';
  $fileName=iconv("utf-8","gbk//IGNORE",$fileName);
  if(touch($fileName)){
    return '文件创建成功';
  }else{
    return '文件创建失败';
  }
}
/**
 * 判断是否是图片
 */
function isImage($fileName){
   $ext=strtolower(end(explode(".",$fileName)));
   $imgExt=array('jpg','ipeg','png','gif');
    if(in_array($ext,$imgExt)){
      return true;      
    }else{
      return false;
    }
}
?>