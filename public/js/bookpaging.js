function bookpaging(t){var n=document.getElementById("postId").value;$.ajax({type:"post",url:"/bookpaging",data:{id:n,page:t},beforeSend:function(){scrollTo(),$(".spinner").attr("style","display:inline-block")},success:function(t){return $(".spinner").attr("style","display:none"),$("#booklist").html(t),!1},error:function(t){return $(".spinner").attr("style","display:none"),!1}})}function scrollTo(){return $("html, body").animate({scrollTop:$("#booklistbox").offset().top},"fast"),!1}