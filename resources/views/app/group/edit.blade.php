<!-- resources/views/groups/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row timeline">
        <div class="col-md-3">
            <div class="col-md-12 profile followUser">
                <div class="profile-img text-center">
                    <a href="{{ route('profile.view', ['id' => Auth::user()->id]) }}">
                        <img src="{{ Auth::user()->getAvatarImagePath() }}" height="100px" class="img-circle">
                    </a>
                    <p>{{ Auth::user()->getFullName() }}</p>
                </div>
                @include('layouts.menu_links')
            </div>
        </div> <!-- profile -->
        <div class="col-md-6">
    <h1>Chỉnh Sửa Thông Tin Nhóm</h1>

    <form action="{{ route('groups.update', $group->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên Nhóm:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $group->name) }}">
        </div>

        <div class="form-group">
            <label for="description">Mô Tả Nhóm:</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $group->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
    </form>
</div>
<div class="col-md-3 sidebar">
    @if (Auth::user()->HasAnyFriendRequestsPending()->count())
       <div class="panel panel-default">
           <div class="panel-heading">
               Các yêu cầu kết bạn đang đợi
           </div>
           <div class="panel-body">
               @include('layouts.search_results', array('users' => Auth::user()->HasAnyFriendRequestsPending()))
           </div>
       </div>
   @endif

   @if (Auth::user()->notifications()->where('seen', 0)->count())
       <div class="panel panel-default" id="NotificationsPanel">
           <div class="panel-heading">
               Thông báo
           </div>
           <div class="panel-body">
                   @foreach (Auth::user()->notifications()->where('seen', 0)->get() as $notification)
                       <p>
                           <a href="{{ route('profile.view', ['id' => $notification->userFrom->id]) }}">{{ $notification->userFrom->getFullName() }}</a>
                           đã
                           @if ($notification->notification_type == 'App\Like')
                               thích <a class="smoothScroll"
                                             href="#PostId{{ $notification->notification->likeable->id }}">bài viết của bạn</a>
                               .
                           @else
                               bình luận <a class="smoothScroll"
                                                    href="#PostId{{ $notification->notification->post->id }}">bài viết của bạn</a>
                               .
                           @endif
                       </p>
                   @endforeach
                   <p class="text-center">
                       <button class="btn btn-signature" id="NotificationsButtonSeen">Đánh dấu đã xem</button>
                   </p>
               </div>
           </div>
       @endif

       <div class="panel panel-default">
           <div class="panel-heading">
               Tìm kiếm bạn bè
           </div>
           <div class="panel-body">
               <div class="input-group">
                   {!! Form::text('q', null, ['class' => 'form-control', 'placeholder' => 'Tìm bạn..', 'id' => 'q']) !!}
                   <span class="input-group-btn">
                   <button class="btn btn-default" type="button" id="SearchForFriendsButton"><i
                               class="fa fa-search" aria-hidden="true"></i></button>
               </span>
               </div><!-- /input-group -->
               <div id="SearchResults">

               </div>
           </div>
       </div>

       <div class="panel panel-default">
           <div class="panel-heading">
               Hoạt động của bạn bè
           </div>
           <div class="panel-body" style="font-size: 14px;">
               {!! Auth::user()->friendsLastActivity() !!}
           </div>
       </div>
</div>
</div>
@endsection
