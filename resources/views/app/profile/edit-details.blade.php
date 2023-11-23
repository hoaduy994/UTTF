@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1 class="text-center">Chỉnh sửa thông tin người dùng</h1>
                {!! Form::model($user, ['route' => ['profile.update', $user->id], 'method' => 'PATCH', 'files' => true]) !!}
                    <div class="form-group">
                        {!! Form::label('first_name', 'Tên:') !!}
                        {!! Form::text('first_name', null, ['class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('last_name', 'Họ:') !!}
                        {!! Form::text('last_name', null, ['class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('email', 'Email:') !!}
                        {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('avatar', 'Ảnh đại diện:') !!}
                        {!! Form::file('avatar', ['class' => 'form-control']) !!}
                        <small>Chỉ chọn ảnh nếu bạn muốn cập nhật ảnh đại diện.</small>
                    </div>
                    <div class="form-group">
                        {!! Form::label('dob', 'Ngày sinh:') !!}
                        {!! Form::date('dob', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', 'Mô tả bản thân:') !!}
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('address', 'Địa chỉ:') !!}
                        {!! Form::text('address', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::submit('Lưu thông tin', ['class' => 'btn btn-primary']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
