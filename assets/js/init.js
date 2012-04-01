$(function(){
	$.ajax({
	   url:'/api/',
	   data:'request=all',
	   type:'get',
	   dataType:'JSON',
	   success:function(data){
		   for(var i=0; i <data.length; i ++){
			   console.log(data[i].article.title);
			   $("#all").append("<div class='row' id='"+i+"'></div>");
			   $("#"+i).append("<div class='span1'><img src='assets/img/ego-icons/"+data[i].article.id+".jpg' width='48px' height='48px' class='ego-icon' /></div><div class='span8'><div class='title'>"+data[i].article.title+"</div><div class='submitted'>today</div><img src='assets/img/dubai-ego-post.jpg' width='150px' class='post-thumb' /><p class='content'>"+data[i].article.content+"</p><a href='"+data[i].article.url+"' class='out-link'>Continue reading.</a></div>");
		   }
		  console.log(data[0].article.title); 
	   }
	   });
});