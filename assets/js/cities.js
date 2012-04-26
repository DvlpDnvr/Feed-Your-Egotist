$(function(){

	var path = window.location.href.split('?');
	var argument = path[1].split('=');
	var filter = argument[1];
	$.ajax({
	   url:'/api/',
	   data:'filter='+filter,
	   type:'get',
	   dataType:'JSON',
	   success:function(data){
		   for(var i=0; i <data.length; i ++){
			   console.log(data[i].article.image);
			   $("#all").append("<div class='row' id='"+i+"'></div>");
			   var content = "<div class='span1'><img src='assets/img/ego-icons/"+data[i].article.image+"' width='48px' height='48px' class='ego-icon' /></div><div class='span8'><h2 class='title'>"+data[i].article.title+"</h2><div class='submitted'>"+data[i].article.date+"</div>";
			if(data[i].article.article_image != ""){
					content += "<img src='"+data[i].article.article_image+"' width='150px' class='post-thumb' />";
			}
			content += "<p class='content'>"+data[i].article.content+"</p><a href='"+data[i].article.url+"' class='out-link'>Continue reading.</a></div>";
			$("#"+i).append(content);
			   
		   }
		  //console.log(data[0].article.title); 
	   }
	   });
	
	
});