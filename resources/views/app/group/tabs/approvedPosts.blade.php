<div >
    <h2>Bài viết đã được chấp nhận trong nhóm</h2>
    @if ($approvedPosts->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Tên người dùng</th>
                    <th>Nội dung</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($approvedPosts->sortByDesc('created_at') as $post)
                    <tr>
                        <td>{{ $post->user->getFullName() }}</td>
                        <td>{{ $post->body }}</td>
                        <td>
                            <form
                                action="{{ route('groups.deletePost', ['groupId' => $group->id, 'postId' => $post->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Không có bài viết nào được chấp nhận trong nhóm này.</p>
    @endif
    <h2>Bài viết chưa được chấp nhận:</h2>
    <table>
        <thead>
            <tr>
                <th>Tên Người Dùng</th>
                <th>Nội Dung Bài Đăng</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($unapprovedPosts->sortByDesc('created_at') as $post)
                <tr>
                    <td>{{ $post->user->getFullName() }}</td>
                    <td>{{ $post->body }}</td>
                    <td>
                        <form
                            action="{{ route('groups.approvePost', ['groupId' => $group->id, 'postId' => $post->id]) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Chấp Nhận</button>
                        </form>
                        <form
                            action="{{ route('groups.deletePost', ['groupId' => $group->id, 'postId' => $post->id]) }}"
                            method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger">Từ Chối</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>