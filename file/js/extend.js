$(function(){
	/*创建文件*/
	$('#createFileBtn').click(function(){
		var fileName=$('#createFileForm #fileName').val();
		var path=$('#createFileForm #path').val();
		$.ajax({
			type:'POST',
			url:'index.php?act=createFile',
			data:{fileName:fileName,path:path},
			success:function(data){
				
				$('#createFile').modal('hide');
				$('#smallAlert .modal-body').html(data)
				$('#smallAlert').modal('show');
			}
		});
	});
	/*创建文件夹*/
	$('#createDirBtn').click(function(){
		var dirName=$('#createDir #dirName').val();
		var path=$('#createDir #path').val();
		$.ajax({
			type:'POST',
			url:'index.php?act=createDir',
			data:{dirName:dirName,path:path},
			success:function(data){
				
				$('#createDir').modal('hide');
				$('#smallAlert .modal-body').html(data)
				$('#smallAlert').modal('show');
			}
		});
	});
	/*刷新页面*/
	$('#reload').click(function(){
		location.reload();
	});
	/*查看文件*/
	$('.showContentBtn').click(function(){
		var url=$(this).attr("data-url");
		$.ajax({
			type:'GET',
			url:url,
			beforeSend:function(){
				$('#showContent .contentTextarea').html('载入中...');
			},
			success:function(data){
				$('#showContent .contentTextarea').html(data);
				$('#showContent').modal('show');
			}
		});
	});
	/*编辑文件*/
	$('.editContentBtn').click(function(){
		var url=$(this).attr("data-url");
		var fileName=$(this).attr("data-filename");
		$.ajax({
			type:'GET',
			url:url,
			beforeSend:function(){
				$('#editContent .contentTextarea').html('载入中...');
			},
			success:function(data){
				$('#editContent .contentTextarea').html(data).attr('data-filename',fileName);
				$('#editContent').modal('show');
			}
		});
	});
	/*处理编辑文件*/
	$('.editContentSubmit').click(function(){
		var url="index.php?act=doEditContent";
		var content=$('#editContentTextarea').val();
		var fileName=$('#editContentTextarea').attr("data-filename");
		$.ajax({
			type:'POST',
			url:url,
			data:{fileContent:content,fileName:fileName},
			success:function(data){
				$('#editContent').modal('hide');
				$('#smallAlert .modal-body').html(data)
				$('#smallAlert').modal('show');
			}
		});
	});
	/*重命名文件*/
	$('.oldRowFileName').dblclick(function(){
		var oldFileName=$(this).html();
		var inputStr="<input type='text' class='form-control newFileName' value='"+oldFileName+"' data-oldFileName='"+oldFileName+"'/>";
		$(this).html('').parent('.changeFileName').find('.newRowFileName').html(inputStr);
	});
	$(document).on('blur','.newFileName',function(){
		var newFileName=$(this).val();
		var oldFileName=$(this).attr('data-oldFileName');
		var path=$('#hiddenPath').val();
		if(newFileName==oldFileName){
			$('#smallAlert .modal-body').html("请填写新文件名称")
			$('#smallAlert').modal('show');
		}else{
			$.ajax({
				type:'POST',
				url:'index.php?act=renameFile',
				data:{newFileName:newFileName,oldFileName:oldFileName,path:path},
				success:function(data){
					$('#smallAlert .modal-body').html(data)
					$('#smallAlert').modal('show');
				}
			});			
		}

	});
	/*删除文件*/
	$('.delFileBtn').click(function(){
		var url=$(this).attr('data-url');
		$('#delFileConfirm .delFileConfirmBtn').attr('href',url);
	});
	$(document).on('click','.delFileConfirmBtn',function(){
		var url=$(this).attr('href');
		$.ajax({
			type:'GET',
			url:url,
			success:function(data){
				$('#smallAlert .modal-body').html(data)
				$('#smallAlert').modal('show');
			}
		});
	});
	/*上传文件*/
	$('.uploadFileBtn').click(function(){
		$('#uploadFile').submit();
	});

});
