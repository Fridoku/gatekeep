@extends('templates.base')

@section('title', 'Token List')

@section('content')
<div class="row align-items-start">
    <div class="col-12">
        <h2>Token List</h2>
        <div class="row">
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Token</th>
                            <th>User</th>
                            <th>Enabled</th>
                            <th></th>
                        </tr>
                    </thead>
                    @foreach ($tokens as $token)
                        <tr class="border-bottom">
                            <td>{{ $token->name}}</td>
                            <td><a href="{{ route('admin.editUser', ['id' => $token->user->id]) }}">{{ $token->user->username }}<a></td>
                            <td>{{ $token->enabled ? 'Yes' : 'No' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href=" {{ route('admin.editToken', ['id' => $token->id]) }}" role="button" class="btn btn-primary">Edit</a>
                                    @if ($token->enabled) <a href=" {{ route('admin.disableToken', ['id' => $token->id, 'source' => 'admin.listTokens']) }}" role="button" class="btn btn-secondary">Disable</a>
                                    @else <a href=" {{ route('admin.enableToken', ['id' => $token->id, 'source' => 'admin.listTokens']) }}" role="button" class="btn btn-info">Enable</a> @endif
                                    <a href=" {{ route('admin.deleteToken', ['id' => $token->id, 'source' => 'admin.listTokens']) }}" role="button" class="btn btn-danger">Delete</a>
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
                    <a href=" {{ route('admin.addToken')}}" role="button" class="btn btn-success">Add Token</a>
            </div>
        </div>

    </div>
</div>
@endsection
