function errorreporting() {
  $.ajax(
  {
    type: 'post',
    url: '/errorreporting',
    data: {
      'url': window.location.href
    },
    beforeSend: function() {
      $('#errorreporting').attr('style', 'display:none');
      $('.spinner').attr('style', 'display:inline-block');
    },
    success: function()
    {
      $('.spinner').attr('style', 'display:none');
      $('#errormessage').html('<span class="badge badge-pill badge-success">Báo lỗi thành công! Cảm ơn bạn rất nhiều!</span>');
      return false;
    },
    error: function(xhr)
    {
      $('.spinner').attr('style', 'display:none');
      $('#errormessage').html('<span class="badge badge-pill badge-success">Báo lỗi thành công! Cảm ơn bạn rất nhiều!</span>');
      return false;
    }
  });
}
$(function () {
  var prev = document.getElementById('prev').value;
  var next = document.getElementById('next').value;
  document.onkeydown = function(e) {
    switch (e.keyCode) {
      case 37:
        window.location.href = prev;
        break;
      case 39:
        window.location.href = next;
        break;
    }
  };
  $(window).scroll(function() {
    if ($(this).scrollTop() == 0) {
      document.getElementById('themes').style.display='block';
    } else {
      document.getElementById('themes').style.display='none';
    }
  });
  $('#themesbtn').click(function() {
    document.getElementById('themesbtn').style.display='none';
    document.getElementById('themesbox').style.display='block';
    return false;
  });
  $('#themesboxbtn').click(function() {
    document.getElementById('themesbtn').style.display='block';
    document.getElementById('themesbox').style.display='none';
    return false;
  });
  $("#themescolor").change(function(event) {
    var themescolor = document.getElementById('themescolor').value;
    document.getElementsByTagName("body")[0].className=themescolor;
    setCookie('themescolor', themescolor, 3650);
    return false;
  });
  $("#themesfontsize").change(function(event) {
    var themesfontsize = document.getElementById('themesfontsize').value;
    document.getElementsByClassName("mb-3")[1].style.fontSize=themesfontsize;
    setCookie('themesfontsize', themesfontsize, 3650);
    return false;
  });
  $("#themeslineheight").change(function(event) {
    var themeslineheight = document.getElementById('themeslineheight').value;
    document.getElementsByClassName("mb-3")[1].style.lineHeight=themeslineheight;
    setCookie('themeslineheight', themeslineheight, 3650);
    return false;
  });
  $("#themesmenu").change(function(event) {
    if (event.target.checked) {
      document.getElementsByTagName("header")[0].style.display='none';
      document.getElementsByTagName("footer")[0].style.display='none';
      document.getElementsByTagName("ol")[0].style.display='none';
      setCookie('themesmenu', 1, 3650);
    } else {
      document.getElementsByTagName("header")[0].style.display='block';
      document.getElementsByTagName("footer")[0].style.display='block';
      document.getElementsByTagName("ol")[0].style.display='block';
      document.cookie = "themesmenu=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }
    return false;
  });

})
function setCookie(cname,cvalue,exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires=" + d.toGMTString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
