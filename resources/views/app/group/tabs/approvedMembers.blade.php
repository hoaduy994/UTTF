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
                            method="POST" style="margin: 10px;">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger fa fa-trash-o" style="width: 38px; height:34px; margin-left: 4px;"></button>
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
                    <td style="display: flex;">
                        <form
                            action="{{ route('groups.approveMember', ['groupId' => $group->id, 'memberId' => $member->id]) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success" style="width: 38px; height:34px;">
                                <img width="14" height="14" src="https://img.icons8.com/external-flat-icons-inmotus-design/24/FFFFFF/external-Accept-antivirus-flat-icons-inmotus-design.png" alt="external-Accept-antivirus-flat-icons-inmotus-design"/>
                            </button>
                        </form>
                        <form
                            action="{{ route('groups.removeMember', ['groupId' => $group->id, 'memberId' => $member->id]) }}"
                            method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger fa fa-trash-o" style="width: 38px; height:34px; margin-left: 4px;"></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
