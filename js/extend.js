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
			success:function(data){
				$('#showContent .contentTextarea').html(data);
				$('#showContent').modal('show');
			}
		});
	});
})