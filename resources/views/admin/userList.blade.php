@extends('templates.base')

@section('title', 'User List')

@section('content')
<div class="row align-items-start">
    <div class="col-12">
        <h2>User List</h2>
        <div class="row">
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->username}}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href=" {{ route('admin.editUser', ['id' => $user->id]) }}" role="button" class="btn btn-primary">Edit</a>
                                    @if( $user->enabled ) <a href=" {{ route('admin.disableUser', ['id' => $user->id, 'source' => 'admin.listUsers']) }}" role="button" class="btn btn-secondary">Disable</a>
                                    @else <a href=" {{ route('admin.enableUser', ['id' => $user->id, 'source' => 'admin.listUsers']) }}" role="button" class="btn btn-info">Enable</a> @endif
                                    <a href=" {{ route('admin.deleteUser', ['id' => $user->id, 'source' => 'admin.listUsers']) }}" role="button" class="btn btn-danger @if($user->ldap_uuid !== NULL) disabled @endif">Delete</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 ">
                <div class="btn-group" role="group">
                    <a href=" {{ route('admin.addUser')}}" role="button" class="btn btn-success">Add User</a>
                    @if( env('LDAP_IMPORT_ENABLED', false) ) <a href=" {{ route('admin.ldapImport')}}" role="button" class="btn btn-warning">Import Users from LDAP</a> @endif
            </div>
        </div>

    </div>
</div>
@endsection
