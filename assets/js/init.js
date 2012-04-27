$(function(){
		   
	
	var filter = getUrlVars()["filter"];
	if(!filter){
		filter="";
	}
	$.ajax({
	   url:'/api/',
	   data:'request=all&filter='+filter,
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
	
	$.ajax({
	   url:'/api/',
	   data:'request=posts&filter='+filter,
	   type:'get',
	   dataType:'JSON',
	   success:function(data){
		   for(var i=0; i <data.length; i ++){
			   console.log(data[i].article.image);
			   $("#posts").append("<div class='row' id='p"+i+"'></div>");
			   var content = "<div class='span1'><img src='assets/img/ego-icons/"+data[i].article.image+"' width='48px' height='48px' class='ego-icon' /></div><div class='span8'><h2 class='title'>"+data[i].article.title+"</h2><div class='submitted'>"+data[i].article.date+"</div>";
			if(data[i].article.article_image != ""){
					content += "<img src='"+data[i].article.article_image+"' width='150px' class='post-thumb' />";
			}
			content += "<p class='content'>"+data[i].article.content+"</p><a href='"+data[i].article.url+"' class='out-link'>Continue reading.</a></div>";
			$("#p"+i).append(content);
			   
		   }
		  //console.log(data[0].article.title); 
	   }
	   });
	
	$.ajax({
	   url:'/api/',
	   data:'request=editorials&filter='+filter,
	   type:'get',
	   dataType:'JSON',
	   success:function(data){
		   for(var i=0; i <data.length; i ++){
			   console.log(data[i].article.image);
			   $("#editorials").append("<div class='row' id='e"+i+"'></div>");
			   var content = "<div class='span1'><img src='assets/img/ego-icons/"+data[i].article.image+"' width='48px' height='48px' class='ego-icon' /></div><div class='span8'><h2 class='title'>"+data[i].article.title+"</h2><div class='submitted'>"+data[i].article.date+"</div>";
			if(data[i].article.article_image != ""){
					content += "<img src='"+data[i].article.article_image+"' width='150px' class='post-thumb' />";
			}
			content += "<p class='content'>"+data[i].article.content+"</p><a href='"+data[i].article.url+"' class='out-link'>Continue reading.</a></div>";
			$("#e"+i).append(content);
			   
		   }
		  //console.log(data[0].article.title); 
	   }
	   });
	
	function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars;
	}
});