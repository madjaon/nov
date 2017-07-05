@if(getDevice2() == MOBILE)
<header class="mb-4">
  <div class="container">
    <div class="row">
      <div class="col-6">
        <a href="{!! url('/') !!}" class="d-flex justify-content-start py-2">Truyện On</a>  
      </div>
      <div class="col-6">
        <a class="d-flex justify-content-end py-2 text-primary" onclick="document.getElementById('menumobile').style.display='block'"><i class="fa fa-align-justify menuicon" aria-hidden="true"></i></a>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <form action="{!! route('site.search') !!}" method="GET" class="form-inline my-3">
          <div class="input-group">
          <input name="s" type="text" value="" class="form-control search-input" placeholder="Tìm kiếm theo tên hoặc tác giả truyện" id="search">
            <span class="input-group-btn">
              <button class="btn btn-success" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </span>
          </div>
        </form>
      </div>
    </div>
  </div>
</header>
<div class="menumobile" id="menumobile">
  {!! $menumobile !!}
</div>
@else
<header class="mb-4">
  <nav class="navbar navbar-toggleable-sm py-1">
    <div class="container">
      <a class="navbar-brand" href="{!! url('/') !!}">Truyện On</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="hover" aria-haspopup="true" aria-expanded="false">Thể loại</a>
              {!! $menutypes !!}
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkList" data-toggle="hover" aria-haspopup="true" aria-expanded="false">Danh sách</a>
            <div class="dropdown-menu animated fadeInDownNew" aria-labelledby="navbarDropdownMenuLinkList">
              <a class="dropdown-item" href="{!! CommonUrl::getUrlPostKind('da-hoan-thanh') !!}" title="Danh sách truyện đã hoàn thành">Truyện đã hoàn thành</a>
              <a class="dropdown-item" href="{!! CommonUrl::getUrlPostKind('con-tiep-tuc') !!}" title="Danh sách truyện còn tiếp tục">Truyện còn tiếp tục</a>
              <a class="dropdown-item" href="{!! url('tac-gia') !!}" title="Danh sách tác giả">Tác giả</a>
            </div>
          </li>
        </ul>
        <form action="{!! route('site.search') !!}" method="GET" class="form-inline my-2 my-lg-0">
          <div class="input-group">
          <input name="s" type="text" value="" class="form-control search-input" placeholder="Tìm kiếm theo tên hoặc tác giả truyện" id="search">
            <span class="input-group-btn">
              <button class="btn btn-success" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </span>
          </div>
        </form>
      </div>
    </div>
  </nav>
</header>
@endif
