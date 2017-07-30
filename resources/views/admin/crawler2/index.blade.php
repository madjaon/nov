@extends('admin.layouts.master')

@section('title', 'Steal novel')

@section('content')

<div class="row margin-bottom">
	<div class="col-xs-12">
		<a href="{{ route('admin.post.index') }}" class="btn btn-success btn-sm">Danh sách post</a>
		<a href="{{ route('admin.post.create') }}" class="btn btn-primary btn-sm">Thêm post</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Crawler</h3>
			</div>
			<div class="box-body">
				<h4>Truyenfull.vn</h4>
				<a href="/admin/crawler2/truyenfulltacgia">Lấy Danh sách tác giả</a>
				<br>
				<a href="/admin/crawler2/truyenfullpost">Lấy Danh sách Truyện đã hoàn thành</a>
				<br>
				<a href="/admin/crawler2/truyenfullpostep">Lấy Danh sách Chapter Truyện đã hoàn thành</a>
			</div>
		</div>
	</div>
</div>

@stop