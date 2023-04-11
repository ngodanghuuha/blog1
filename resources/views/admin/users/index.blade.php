@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            User list
            @can('role_create')
                <a class="btn btn-success btn-sm text-white float-end" href="{{ route("admin.users.create") }}">
                    Add New
                </a>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>
                            ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Roles
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Register At
                        </th>
                        <th>
                            Block
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <td>
                                {{ $user->id ?? '' }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->email ?? '' }}
                            </td>
                            <td>
                                @foreach($user->getRoleNames() as $key => $item)
                                    <span class="badge bg-info">{{ $item }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Blocked</span>
                                @endif
                            </td>
                            <td>
                                {{ $user->created_at->format('Y-m-d') ?? '' }}
                            </td>
                            <td>
                                @if (auth()->user()->hasRole('Admin'))
                                @if($user->status)
                                    <a href="{{ route('admin.user.banUnban', ['id' => $user->id, 'status' => 0]) }}" class="badge bg-danger">Block</a>
                                @else
                                    <a href="{{ route('admin.user.banUnban', ['id' => $user->id, 'status' => 1]) }}" class="badge bg-info">Unblock</a>
                                @endif
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}">
                                    <span class="badge bg-primary">Edit</span>
                                </a>
                                <form id="delete-form-{{ $user->id }}" method="post"
                                      action="{{ route('admin.users.destroy', $user->id) }}" style="display: none">
                                    {{csrf_field()}}
                                    {{ method_field('DELETE') }}
                                </form>
                                <a href="javascript:void(0)" class="badge bg-danger text-white" onclick="
                                    if(confirm('Are you sure, You want to Delete this ??'))
                                    {
                                    event.preventDefault();
                                    document.getElementById('delete-form-{{ $user->id }}').submit();
                                    }">Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $users->links() }}
        </div>
    </div>
@endsection
