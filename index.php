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
            <a class="navbar-brand" href="#">服务器文件管理系统v1.0</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">主目录<span class="icon icon-small "><span class="icon-tpl-full"></span></span></a></li>
              <li><a href="#"><span class="icon icon-small "><span class="icon-folder"></span></span>新建文件夹</a></li>
              <li><a href="javascript:void(0)" data-toggle="modal" data-target="#createFile"><span class="icon icon-small "><span class="icon-file"></span></span>新建文件</a></li>
              <li><a href="#"><span class="icon icon-small "><span class="icon-arrowLeft"></span></span>返回上级</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#"><span class="icon icon-small "><span class="icon-upload"></span></span>上传文件</a></li>
            </ul>
          </div>
        </div>
      </nav>
      <!--[//导航条部分结束]-->

      <div class="jumbotron">
        <h1>PHP文件管理器</h1>
        <p>基于php实现的web在线文件管理器,前端使用bootstrap框架和Cikonss图标样式库---陶士涵</p>
      </div>

      <table class="table table-bordered table-hover">
        <tr class="active">
          <td>名称</td>
          <td>类型</td>
          <td>大小</td>
          <td>读</td>
          <td>写</td>
          <td>执行</td>
          <td>创建时间</td>
          <td>操作</td>
        </tr>
        <?php foreach ($data['file'] as $k => $row) {?>
        <tr>
          <td><?php echo $row['name'];?></td>
          <td><span class="icon icon-mid "><span class="<?php $ext=$row['type']=='dir' ?'icon-folder' : 'icon-file';echo $ext;?>"></span></span></td>
          <td><?php echo $row['size'];?></td>
          <td><span class="icon icon-mid "><span class="<?php $ico=$row['readable']==true ?'icon-play' : 'icon-pause';echo $ico;?>"></span></span></td>
          <td><span class="icon icon-mid "><span class="<?php $ico=$row['writable']==true ?'icon-play' : 'icon-pause';echo $ico;?>"></span></span></td>
          <td><span class="icon icon-mid "><span class="<?php $ico=$row['executable']==true ?'icon-play' : 'icon-pause';echo $ico;?>"></span></span></td>
          <td><?php echo $row['ctime'];?></td>
          <td><a href="javascript:void(0)" data-url="index.php?act=showContent&fileName=<?php echo $row['name']?>" data-toggle="modal" data-target="#showContent" class="showContentBtn">[ 查看 ]</a></td>
        </tr>
        <?php }?>
      </table>

    </div> 
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
            <button type="button" id="createFileBtn" class="btn btn-primary">提交</button>
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
              <textarea class="form-control contentTextarea" rows="3" placeholder="没有内容"></textarea>
                <input type="hidden" id="path" value="<?php echo $path?>" />
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <button type="button" id="createFileBtn" class="btn btn-primary">提交</button>
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