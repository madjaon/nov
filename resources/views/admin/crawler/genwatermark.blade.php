@extends('admin.layouts.master')

@section('title', 'Generate Watermark Images')

@section('content')

<div class="row margin-bottom">
	<div class="col-xs-12">
		<a href="{{ route('admin.post.index') }}" class="btn btn-success btn-sm">Danh sách post</a>
		<a href="{{ route('admin.post.create') }}" class="btn btn-primary btn-sm">Thêm post</a>
		<a href="/admin/genthumb" class="btn btn-warning btn-sm">Gen Thumb</a>
		<a href="/admin/genwatermark" class="btn btn-danger btn-sm">Gen Watermark</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Generate Watermark Images</h3>
			</div>
			<div class="box-body">
				@if(isset($data->total) && $data->total > 0)
					<p>Có <strong>{{ $data->total }} ảnh được tạo watermark</strong></p>
				@else
					<p>Không có ảnh nào được tạo watermark</p>
				@endif
				<form action="{{ url('admin/genwatermarkAction') }}" method="POST">
					{!! csrf_field() !!}
					<div class="form-group">
						<label>Thư mục ảnh</label>
						<p>Nếu để trống không nhập: mặc định là thư mục images/</p>
						<p>Nếu nhập: theo mẫu: images/ten-thu-muc/</p>
						<p>Hoặc nhập theo mẫu: images/ten-thu-muc/ten-thu-muc-con/</p>
						<div class="row">
							<div class="col-sm-8">
								<input name="dir" type="text" value="{{ old('dir') }}" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Mã ảnh</label>
						<p>Mã base64 encode: <a href="https://www.base64-image.de/" target="_blank" rel="nofollow">click để tới trang tạo mã</a></p>
						<p>Cỡ ảnh đang là: 176x28</p>
						<p>Đang để chỉ tạo watermark với ảnh cỡ: width >= 300, height > 150</p>
						<p>Ảnh watermark nếu để trống: <img src="{{ WATERMARK_BASE64 }}" alt="" width="176" height="28"></p>
						<div class="row">
							<div class="col-sm-8">
								<input name="code" type="text" value="{{ old('code') }}" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Vị trí</label>
						<p>Mặc định (để trống): bottom-right</p>
						<p>// top-left
							// top
							// top-right
							// left
							// center
							// right
							// bottom-left
							// bottom
							// bottom-right</p>
						<div class="row">
							<div class="col-sm-8">
								<input name="position" type="text" value="{{ old('position') }}" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-8">
								<input type="submit" class="btn btn-primary" value="Lưu lại" />
								<input type="reset" class="btn btn-default" value="Nhập lại" />
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@stop