<?php
/**
 * PHP文件管理器
 * @author 陶士涵
 */
date_default_timezone_set('PRC');
ini_set("magic_quotes_gpc",0);
header("content-Type: text/html; charset=Utf-8"); 

define('PATH','file/');//定义管理的目录



if(isset($_REQUEST['path'])){
  $path=str_replace("//",'/',urldecode($_REQUEST['path'])).'/';
}else{
  $path=PATH;
}
$act=isset($_REQUEST['act']) ? $_REQUEST['act'] : '';

/*验证一下*/
$checkPath=explode('/',$path);
if($checkPath[0].'/'!=PATH || !is_dir($path)){
  jump('没有这个文件夹哦',false);
}

$data=readDirectory($path);
/*创建文件*/
if($act=='createFile'){
  $fileName=$path.$_POST['fileName'];
  $info=createFile($fileName);
  exit($info);
}
/*创建文件夹*/
if($act=='createDir'){
  $dirName=$path.$_POST['dirName'];
  $info=createDir($dirName);
  exit($info);
}
/*查看文件*/
if($act=='showContent'){
  $filePath=$_GET['fileName'];
  $filePath=iconv("utf-8","gbk//IGNORE",$filePath);

  $str=showFile($filePath);
  exit($str);
}

/*编辑文件*/
if($act=='editContent'){
  $filePath=$_GET['fileName'];
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
  // exit("sorry,怕有安全问题,关掉了");
  $filePath=$_POST['fileName'];
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

/*重命名文件*/
if($act=='renameFile'){
  $oldFileName=iconv("utf-8","gbk//IGNORE",$_POST['oldFileName']);
  $newFileName=iconv("utf-8","gbk//IGNORE",$_POST['newFileName']);

  if(!checkFileName($newFileName)){
    exit('文件名中包含非法字符/,*,<,>,?,|');
  }
  if(file_exists($path.$newFileName)){
    exit('文件名已存在,请重新填写文件名');
  }
  $res=rename($path.$oldFileName, $path.$newFileName);
  if($res){
    exit('文件名修改成功!');
  }else{
    exit('修改失败,请重新填写文件名!');
  }
}
/*删除文件*/
if($act=='delFile'){
  $fileName=$_GET['fileName'];

  if(is_dir($fileName)){
    $res=delDir($fileName.'/');
  }else{
    $res=delFile($fileName);
  }
  
  if($res){
    exit('删除成功');
  }else{
    exit('删除失败');
  }
}
/*下载文件*/
if($act=='downFile'){
  $fileName=urldecode($_GET['fileName']);
  downFile($fileName);
  exit;
}
/*上传文件*/
if($act=='uploadFile'){
  $type=$_FILES["uploadFileInput"]["type"];
  $size=$_FILES["uploadFileInput"]["size"];
  $tmp_name=$_FILES["uploadFileInput"]["tmp_name"];
  $name=$_FILES["uploadFileInput"]["name"];
  $name=iconv("utf-8","gbk//IGNORE",$name);

 
  if($_FILES["uploadFileInput"]["error"]>0){
    $info= "上传文件有误:".$_FILES["uploadFileInput"]["error"];
  }
  $dir=$_GET['path'];
  $newPath=$dir."/{$name}";


  if(move_uploaded_file($tmp_name,$newPath)){ 
     jump('上传文件成功',true);
  }else{
     jump('上传文件失败',false);
  }
}
/**
 * 读目录函数
 */
function readDirectory($path){
  $path=iconv("utf-8","gbk//IGNORE",$path);
  $handle=opendir($path);
  $arr=array();
  while(($item=readdir($handle))!==false){
    
    if($item!='.'&&$item!='..'){

      $info=array();
      $filePath=$path.$item;
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
      $info['showname']=iconv("gbk//IGNORE","utf-8",$info['name']);

      if(is_dir($filePath)){
        $info['type']='dir';
        $info['name']=iconv("gbk//IGNORE","utf-8",$path.$info['name']);
        $sumSize=0;global $sumSize;$info['size']=transBytes(getDirSize($filePath.'/'));//目录总大小
        $arr['dir'][]=$info;
      }
      if(is_file($filePath)){
        $info['type']='file';
        $info['name']=iconv("gbk//IGNORE","utf-8",$path.$info['name']);
        $arr['file'][]=$info;
      }

      
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
 * 创建文件夹
 */
function createDir($fileName){
  if(preg_match('/[\/,\*,<,>,\?,\|]/',basename($fileName))) return '文件夹名中包含非法字符/,*,<,>,?,|';
  if(file_exists($fileName)) return '文件名夹已存在,请重命名后提交';
  $fileName=iconv("utf-8","gbk//IGNORE",$fileName);
  if(mkdir($fileName)){
    return '文件夹创建成功';
  }else{
    return '文件夹创建失败';
  }
}
/**
 * 删除文件
 */
function delFile($fileName){
  $fileName=iconv("utf-8","gbk//IGNORE",$fileName);
  if(unlink($fileName)){
    return true;
  }else{
    return false;
  }
}
/**
 * 下载文件
 */
function downFile($fileName){
  header('Content-Disposition: attachment; filename="'.basename($fileName).'"'); 
  header('Content-length:'.filesize($fileName));
  readFile($fileName);
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
/**
 * 检测文件名的合法性
 */
function checkFileName($fileName){
  if(preg_match('/[\/,\*,<,>,\?,\|]/',basename($fileName))){
    return false;
  }else{
    return true;
  }
}
/**
 * 获取目录的文件总大小
 */
function getDirSize($path){
  $sumSize=0;
  global $sumSize;
  $handle=opendir($path);

  while(($item=readdir($handle))!==false){
    
    if($item!='.' && $item!='..'){
      $filePath=$path.$item;
      if(is_file($filePath)){
        $sumSize+=filesize($filePath);
      }elseif(is_dir($filePath)){
        $func=__FUNCTION__;
        $func($filePath.'/');
      }
    }
  }

  closedir($handle);
  return $sumSize;
}
/**
 * 删除文件夹
 */
function delDir($path){
  $handle=opendir($path);
  while(($item=readdir($handle))!==false){
    
    if($item!='.' && $item!='..'){
      $filePath=$path.$item;
      if(is_file($filePath)){
        $filePath=str_replace('//','/',$filePath);
        unlink($filePath);
      }elseif(is_dir($filePath)){
        $func=__FUNCTION__;
        $func($filePath.'/');
      }
    }
    
  }

  closedir($handle);
  $res=rmdir($path);
  if($res){
    return true;
  }else{
    return flase;
  }
}
/**
 * 跳转函数
 */
function jump($msg,$status){
    if($status){
       $info="<h1>:)</h1><p class='success'>{$msg}</p>";
    }else{
      $info="<h1>:(</h1><p class='error'>{$msg}</p>";
    }

    $checkPath=explode('/',$_GET['path']);
    if($checkPath[0].'/'!=PATH){
      $_GET['path']=PATH;
    }

    $jumpHtml=<<<EOF
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message{ padding: 24px 48px; }
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
.system-message .jump{ padding-top: 10px}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 36px }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
</style>
<div class="system-message">
{$info}
<p class="detail"></p>
<p class="jump">
回到文件列表: <a id="href" href="index.php?path={$_GET['path']}">跳转</a>
</p>
</div>
EOF;
  echo $jumpHtml;
  exit;
}

?>