<?php
require 'dir.inc.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>服务器文件管理系统</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="css/cikonss.css" />
    <link rel="stylesheet" href="css/extend.css" />
  </head>
  <body>
  <!--[主体开始]-->
    <div class="container">

      <!--[导航条部分]-->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">服务器文件管理系统v1.0</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="index.php">主目录<span class="icon icon-small "><span class="icon-tpl-full"></span></span></a></li>
              <li><a href="javascript:void(0)" data-toggle="modal" data-target="#createDir"><span class="icon icon-small "><span class="icon-folder"></span></span>新建文件夹</a></li>
              <li><a href="javascript:void(0)" data-toggle="modal" data-target="#createFile"><span class="icon icon-small "><span class="icon-file"></span></span>新建文件</a></li>
              <li><a href="javascript:window.history.go(-1)"><span class="icon icon-small "><span class="icon-arrowLeft"></span></span>返回上级</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#" data-toggle="modal" data-target="#uploadFile"><span class="icon icon-small "><span class="icon-upload"></span></span>上传文件</a></li>
            </ul>
          </div>
        </div>
      </nav>
      <!--[//导航条部分结束]-->

      <div class="jumbotron">
        <h1>PHP文件管理器</h1>
        <p>基于php实现的web在线文件管理器,前端使用bootstrap框架和Cikonss图标样式库---陶士涵</p>
        <!--[if IE]><p>请尽量不要使用IE或IE内核的浏览器查看本页面, O(∩_∩)O~</p><![endif]-->
        <p class="masthead-button-links">
          <a class="btn btn-lg btn-primary btn-shadow" href="fileManage-master.zip" target="_blank" role="button">FileManage中文版(v1.0)</a>
        </p>
      </div>

      <div class="roadPath">
      文件路径: <a href="index.php">根目录</a>
      <?php 
      $temp=explode('/',$path);
      $p='';
      foreach($temp as $v){
        $p.=$v.'/';
        if($v!='') echo " &gt; <a href='index.php?path={$p}'>{$v}</a>";
      }
      ?>
      </div>

      <table class="table table-bordered table-hover">
        <tr class="active">
          <td>类型</td>
          <td>名称</td>
          <td>大小</td>
          <td>读</td>
          <td>写</td>
          <td>执行</td>
          <td>创建时间</td>
          <td>操作</td>
        </tr>
        <?php if(!empty($data['dir'])){foreach ($data['dir'] as $k => $row) {?>
        <tr>
          <td><span class="icon icon-mid "><span class="<?php $ext=$row['type']=='dir' ?'icon-folder' : 'icon-file';echo $ext;?>"></span></span></td>
          <td>
          <div class="changeFileName">
             <p class="oldRowFileName"><?php echo $row['showname'];?></p>
             <p class="newRowFileName"></p>
          </div>
          </td>
          <td><?php echo $row['size'];?></td>
          <td><span class="icon icon-mid "><span class="<?php $ico=$row['readable']==true ?'icon-play' : 'icon-pause';echo $ico;?>"></span></span></td>
          <td><span class="icon icon-mid "><span class="<?php $ico=$row['writable']==true ?'icon-play' : 'icon-pause';echo $ico;?>"></span></span></td>
          <td><span class="icon icon-mid "><span class="<?php $ico=$row['executable']==true ?'icon-play' : 'icon-pause';echo $ico;?>"></span></span></td>
          <td><?php echo $row['ctime'];?></td>
          <td>
            <a href="index.php?path=<?php echo $row['name']?>" class="showContentBtn btn btn-info btn-sm">打开</a>&nbsp;&nbsp;
           
            <a href="javascript:void(0)" data-filename="<?php echo $row['name']?>" data-url="index.php?act=delFile&fileName=<?php echo $row['name']?>" data-toggle="modal" data-target="#delFileConfirm" class="delFileBtn  btn btn-danger btn-sm">删除</a>&nbsp;&nbsp;
            
          </td>
        </tr>
        <?php }}?>


        <?php if(!empty($data['file'])){foreach ($data['file'] as $k => $row) {?>
        <tr>
          <td><span class="icon icon-mid "><span class="<?php $ext=$row['type']=='dir' ?'icon-folder' : 'icon-file';echo $ext;?>"></span></span></td>
          <td>
          <div class="changeFileName">
             <p class="oldRowFileName"><?php echo $row['showname'];?></p>
             <p class="newRowFileName"></p>
          </div>
          </td>
          <td><?php echo $row['size'];?></td>
          <td><span class="icon icon-mid "><span class="<?php $ico=$row['readable']==true ?'icon-play' : 'icon-pause';echo $ico;?>"></span></span></td>
          <td><span class="icon icon-mid "><span class="<?php $ico=$row['writable']==true ?'icon-play' : 'icon-pause';echo $ico;?>"></span></span></td>
          <td><span class="icon icon-mid "><span class="<?php $ico=$row['executable']==true ?'icon-play' : 'icon-pause';echo $ico;?>"></span></span></td>
          <td><?php echo $row['ctime'];?></td>
          <td>
            <a href="javascript:void(0)" data-url="index.php?act=showContent&fileName=<?php echo $row['name']?>" data-toggle="modal" data-target="#showContent" class="showContentBtn btn btn-info btn-sm">查看</a>&nbsp;&nbsp;
            <a href="javascript:void(0)" data-filename="<?php echo $row['name']?>" data-url="index.php?act=editContent&fileName=<?php echo $row['name']?>" data-toggle="modal" data-target="#editContent" class="editContentBtn  btn btn-info btn-sm">编辑</a>&nbsp;&nbsp;
            <a href="javascript:void(0)" data-filename="<?php echo $row['name']?>" data-url="index.php?act=delFile&fileName=<?php echo $row['name']?>" data-toggle="modal" data-target="#delFileConfirm" class="delFileBtn  btn btn-danger btn-sm">删除</a>&nbsp;&nbsp;
            <a data-filename="<?php echo $row['name']?>" href="index.php?act=downFile&fileName=<?php echo $row['name']?>" class="downloadFileBtn  btn btn-info btn-sm">
            <span class="icon icon-small "><span class="icon-download"></span></span>
            下载
            </a>
          </td>
        </tr>
        <?php }}?>
      </table>

    </div> 
    <input type="hidden" id="hiddenPath" value="<?php echo $path;?>" />
    <!--[主体结束]-->



    <!--[模态框]-->
    <div class="modal fade" id="createFile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">创建文件</h4>
          </div>
          <div class="modal-body">
            <form action="index.php?act=createFile" method="post" id="createFileForm">
              <div class="form-group">
                <label for="recipient-name" class="control-label">文件名称(请填上完整后缀):</label>
                <input type="text" class="form-control" id="fileName">
                <input type="hidden" id="path" value="<?php echo $path?>" />
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <a id="createFileBtn" class="btn btn-primary">提交</a>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="createDir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">创建文件夹</h4>
          </div>
          <div class="modal-body">
              <div class="form-group">
                <label for="recipient-name" class="control-label">文件夹名称:</label>
                <input type="text" class="form-control" id="dirName">
                <input type="hidden" id="path" value="<?php echo $path?>" />
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <a id="createDirBtn" class="btn btn-primary">提交</a>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="smallAlert">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">信息提示</h4>
          </div>
          <div class="modal-body">
            <p>&hellip;</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="reload">关闭</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="showContent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">查看文件</h4>
          </div>
          <div class="modal-body">
            <form action="index.php?act=createFile" method="post" id="createFileForm">
              <div class="form-group">
              <div class="contentTextarea">载入中...</div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="delFileConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">删除文件</h4>
          </div>
          <div class="modal-body">
              <div class="contentTextarea">确认是否真的要删除此文件,删除后不可恢复哟</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <a href="" class="delFileConfirmBtn btn btn-danger" data-dismiss="modal">确认</a>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="editContent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">编辑文件</h4>
          </div>
          <div class="modal-body">
            <form action="index.php?act=doEditContent" method="post" id="createFileForm">
              <div class="form-group">
              <textarea class="form-control contentTextarea" rows="10" id="editContentTextarea" data-filename=""></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <button type="button" class="btn btn-primary editContentSubmit" data-dismiss="modal">提交</button>
          </div>
        </div>
      </div>
    </div>

     <div class="modal fade" id="uploadFile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">上传文件</h4>
          </div>
          <div class="modal-body">
            <form action="index.php?act=uploadFile&path=<?php echo $path;?>" method="post" id="uploadFile" enctype="multipart/form-data">
              <div class="form-group">
                <a class="file">选择文件<input type="file" class="form-control uploadFileInput" name="uploadFileInput" /></a>
              </div>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <input type="submit" class="btn btn-primary uploadFileBtn"  value="提交"/></form>
          </div>
        </div>
      </div>
    </div>
  <!--[//模态框结束] -->


    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/extend.js"></script>
  </body>
</html>