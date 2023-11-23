<div>
    <h2>Danh sách thành viên đã được xác nhận:</h2>
    <table>
        <thead>
            <tr>
                <th>Tên Người Dùng</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($approvedMembers->sortByDesc('created_at') as $member)
                <tr>
                    <td>{{ $member->getFullName() }}</td>
                    <td>
                        <form
                            action="{{ route('groups.removeMember', ['groupId' => $group->id, 'memberId' => $member->id]) }}"
                            method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger">Xóa Thành Viên</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h2>Danh sách thành viên chưa được xác nhận:</h2>
    <table>
        <thead>
            <tr>
                <th>Tên Người Dùng</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($unapprovedMembers->sortByDesc('created_at') as $member)
                <tr>
                    <td>{{ $member->getFullName() }}</td>
                    <td>
                        <form
                            action="{{ route('groups.approveMember', ['groupId' => $group->id, 'memberId' => $member->id]) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Chấp Nhận</button>
                        </form>
                        <form
                            action="{{ route('groups.removeMember', ['groupId' => $group->id, 'memberId' => $member->id]) }}"
                            method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
