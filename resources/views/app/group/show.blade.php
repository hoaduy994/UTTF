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
            <div class="col-md-6 feed">
                <h1>{{ $group->name }}</h1>
                <h3>{{ $group->description }} </h3>
                @if (Auth::check() && $group->user_id === Auth::user()->id)
                    <!-- Hiển thị nút chỉnh sửa thông tin nhóm -->
                    <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-primary">Chỉnh Sửa</a>
                    <!-- Hiển thị nút xóa nhóm -->
                    <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa nhóm này?')">Xóa Nhóm</button>
                    </form>
                    <a href="{{ route('groups.manage', $group->id) }}" class="btn btn-success">Quản Lý Nhóm</a>
                @endif
                @if ($group->isMember(Auth::user()))
                    <!-- Hiển thị chức năng chỉ dành cho thành viên của nhóm -->
                    {!! Form::open([
                        'id' => 'GroupPostForm',
                        'method' => 'POST',
                        'action' => ['GroupController@storeGroupPost', $group->id],
                        'files' => 'true',
                    ]) !!}
                    {!! Form::textarea('body', null, [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Bạn có gì muốn chia sẻ?',
                        'rows' => '2',
                    ]) !!}

                    <div class="textareaOptions">
                        <li id="PostImageUploadBtn" class="pointer"><i class="fa fa-lg fa-camera-retro"
                                aria-hidden="true"></i></li>
                        <input type="file" id="image" name="image" style="display:none" />
                        {{ Form::button('<i class="fa fa-location-arrow" aria-hidden="true"></i> Chia sẻ ngay', ['class' => 'btn btn-signature pull-right', 'type' => 'submit']) }}
                    </div>

                    <div class="progress" style="display: none">
                        <div id="PostProgressBar" class="progress-bar progress-bar-striped active" role="progressbar"
                            aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>

                    <span class="help-block" id="PostErrors" style="display: none;"></span>

                    {!! Form::close() !!}

                    @if ($group->approvedPosts->count() > 0)
                        <div class="posts">
                            @foreach ($group->approvedPosts->sortByDesc('created_at') as $post)
                                @include('layouts.posts')
                            @endforeach
                        </div>
                    @else
                        <p>Không có bài viết nào được chấp nhận trong nhóm này.</p>
                    @endif


                @endif
                <!-- Các bài viết trong nhóm -->
            </div>
            <div class="col-md-3 sidebar">
                @if (Auth::user()->HasAnyFriendRequestsPending()->count())
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Các yêu cầu kết bạn đang đợi
                        </div>
                        <div class="panel-body">
                            @include('layouts.search_results', [
                                'users' => Auth::user()->HasAnyFriendRequestsPending(),
                            ])
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
                                    <a
                                        href="{{ route('profile.view', ['id' => $notification->userFrom->id]) }}">{{ $notification->userFrom->getFullName() }}</a>
                                    đã
                                    @if ($notification->notification_type == 'App\Like')
                                        thích <a class="smoothScroll"
                                            href="#PostId{{ $notification->notification->likeable->id }}">bài viết của
                                            bạn</a>
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
    @section('scripts')
        <script type="text/javascript">
            $(document).ready(function() {

                $("#NotificationsButtonSeen").click(function() {

                    $("#NotificationsPanel").hide();

                    $.ajax({
                        type: "post",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('notifications.seen') }}",
                    });

                });

                $(".smoothScroll").click(function(event) {
                    //prevent the default action for the click event
                    event.preventDefault();

                    //get the full url - like mysitecom/index.htm#home
                    var full_url = this.href;

                    //split the url by # and get the anchor target name - home in mysitecom/index.htm#home
                    var parts = full_url.split("#");
                    var trgt = parts[1];

                    //get the top offset of the target anchor
                    var target_offset = $("#" + trgt).offset();
                    var target_top = target_offset.top;

                    //goto that anchor by setting the body scroll top to anchor top
                    $('html, body').animate({
                        scrollTop: target_top
                    }, 425);
                });

                $("#PostForm").on('submit', function(e) {
                    e.preventDefault();

                    $form = $(this);

                    var formData = new FormData($form[0]);

                    var request = new XMLHttpRequest();

                    request.upload.addEventListener('progress', function(e) {

                        var percent = e.loaded / e.total * 100;
                        $('#PostProgressBar').parent().show();
                        $('#PostProgressBar').css('width', percent + '%').attr('aria-valuenow',
                            percent);

                    });

                    request.onreadystatechange = function() {
                        if (request.readyState == XMLHttpRequest.DONE) {
                            var data = JSON.parse(request.responseText);
                            if (data.success) {
                                location.reload();
                            } else {
                                var errorText = "";

                                if (data.errors.body) {
                                    errorText = data.errors.body + '<br>';
                                }
                                if (data.errors.image) {
                                    errorText += data.errors.image;
                                }

                                $("#PostErrors").show();
                                $("#PostErrors").html(errorText);
                            }
                        }
                    }

                    request.open('post', "{{ route('posts.store') }}");
                    request.send(formData);

                });


                function submitSearch() {
                    var q = $("#q").val();
                    var url = "{{ route('search.post') }}";
                    var token = "{{ Session::token() }}";

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            _token: token,
                            q: q
                        },
                        success: function(response) {
                            $("#SearchResults").html("<hr>");
                            $("#SearchResults").append(response);
                            friendEvents();
                        }
                    });
                }

                $("#SearchForFriendsButton").click(function() {
                    submitSearch();
                });

                $("#q").keypress(function(e) {
                    if (e.which == 13) {
                        submitSearch();
                        return false;
                    }
                });

                function friendEvents() {
                    $('.addFriend').click(function() {
                        var id = $(this).attr('data-id');

                        $.ajax({
                            type: "POST",
                            url: "{{ route('friend.add') }}",
                            data: {
                                id: id,
                                _token: '{{ Session::token() }}'
                            },
                            success: function(response) {
                                $("#friendStatusDiv" + id).html(response);
                                friendEvents();
                            }
                        });
                    });

                    $('.cancelFriend').click(function() {
                        var id = $(this).attr('data-id');

                        $.ajax({
                            type: "POST",
                            url: "{{ route('friend.cancel') }}",
                            data: {
                                id: id,
                                _token: '{{ Session::token() }}'
                            },
                            success: function(response) {
                                $("#friendStatusDiv" + id).html(response);
                                friendEvents();
                            }
                        });
                    });

                    $('.removeFriend').click(function() {
                        var id = $(this).attr('data-id');

                        $.ajax({
                            type: "POST",
                            url: "{{ route('friend.remove') }}",
                            data: {
                                id: id,
                                _token: '{{ Session::token() }}'
                            },
                            success: function(response) {
                                $("#friendStatusDiv" + id).html(response);
                                friendEvents();
                            }
                        });
                    });

                    $('.acceptFriend').click(function() {
                        var id = $(this).attr('data-id');

                        $.ajax({
                            type: "POST",
                            url: "{{ route('friend.accept') }}",
                            data: {
                                id: id,
                                _token: '{{ Session::token() }}'
                            },
                            success: function(response) {
                                $("#friendStatusDiv" + id).html(response);
                                friendEvents();
                            }
                        });
                    });
                }

                friendEvents();

                $("#PostImageUploadBtn").click(function() {
                    $("#image").trigger('click');
                });

            });
        </script>
    @append
