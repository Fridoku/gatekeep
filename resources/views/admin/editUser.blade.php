@extends('templates.base')

@section('title') {{ ($user->username == NULL) ? "Create User" : "Edit ".$user->username }} @endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4 border">
            <h2>@if($user->id == 0) Create User @else Edit Profile @endif</h2>
            <form  action="{{ route('admin.saveUser',['id'=> $user->id])}}" method="post">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputUsername">Username</label>
                        <input type="text" class="form-control" id="inputUsername" name="username" value="{{ $user->username }}" @isset($user->ldap_uuid) readonly @endisset>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputFirstName">First Name</label>
                        <input type="text" class="form-control" id="inputFirstName" name="first_name" value="{{ $user->first_name }}" @isset($user->ldap_uuid) readonly @endisset>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputLastName">Last Name</label>
                        <input type="text" class="form-control" id="inputLastName" name="last_name" value="{{ $user->last_name }}" @isset($user->ldap_uuid) readonly @endisset>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail">Mail</label>
                        <input type="text" class="form-control" id="inputEmail" name="email" value="{{ $user->email }}" @isset($user->ldap_uuid) readonly @endisset>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-2 form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inputAdmin" name="is_admin" @if($user->is_admin) checked @endif >
                        <label class="form-check-label" for="inputEnabled">Admin</label>

                    </div>
                    <div class="form-group col-md-4">
                        <input type="password" class="form-control" name="password1" placeholder="Admin Password" @isset( $user->password_hash)value="••••"@endisset>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="password" class="form-control" name="password2" placeholder="Confirm Password" @isset( $user->password_hash)value="••••"@endisset>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-2 form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="enabled" @if($user->enabled) checked @endif id="inputEnabled">
                        <label class="form-check-label" for="inputEnabled">Enabled</label>
                    </div>
                </div>


                <div class="form-row">
                    <div class="form-group col-md-6">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
        @if($user->id !== 0)
            <div class="col-md-4 border">
                <h2>Edit Access</h2>
                <form  action={{ route('admin.saveUserGates',['id'=> $user->id])}} method="post">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Gate</th>
                                        <th>Enabled</th>
                                    </tr>
                                </thead>
                                @foreach ($gates as $gate)
                                    <tr class="border-bottom @if(in_array($gate->id, $userGateIds))table-success @endif">
                                        <td>{{ $gate->nice_name}}</td>
                                        <td>
                                            <div class="form-group col-md-2 form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="gate[]" value="{{$gate->id}}" @if(in_array($gate->id, $userGateIds)) checked @endif>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 border">
                <h2>Edit Tokens</h2>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Token</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @foreach ($tokens as $token)
                                <tr class="border-bottom">
                                    <td>{{ $token->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href=" {{ route('admin.editToken', ['id' => $token->id]) }}" role="button" class="btn btn-primary">Edit</a>
                                            @if ($token->enabled) <a href=" {{ route('admin.disableToken', ['id' => $token->id, 'source' => 'admin.editUser', 'source' => 'admin.listTokens', 'sourceid' => $user->id]) }}" role="button" class="btn btn-secondary">Disable</a>
                                            @else <a href=" {{ route('admin.enableToken', ['id' => $token->id, 'source' => 'admin.editUser', 'sourceid' => $user->id]) }}" role="button" class="btn btn-info">Enable</a> @endif
                                            <a href=" {{ route('admin.deleteToken', ['id' => $token->id, 'source' => 'admin.editUser', 'sourceid' => $user->id]) }}" role="button" class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="btn-group col-md-6" role="group">
                        <a href=" {{ route('admin.addToken', ['user' => $user->id]) }}" role="button" class="btn btn-success">Add Token</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
