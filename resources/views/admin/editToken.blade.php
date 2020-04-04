@extends('templates.base')

@section('title') {{ ($token->id == 0) ? "Create Token" : "Edit Token" }} @endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4 border">
            <h2>@if($token->id == 0) Create Token @else Edit Token @endif</h2>
            <form  action="{{ route('admin.saveToken',['id'=> $token->id])}}" method="post">
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="inputTokenname">Name</label>
                        <input type="text" class="form-control" id="inputTokenname" name="name" value="{{ $token->name }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="inputUser">User</label>
                        <select class="form-control" id="inputUser" name="user">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if ($user == $token->user) selected @endif> {{ $user->username }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-2 form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="enabled" @if($token->enabled) checked @endif id="inputEnabled">
                        <label class="form-check-label" for="inputEnabled">Enabled</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="inputTTokenHash">Token Identifier (will be hashed)</label>
                        <input type="text" class="form-control" id="inputTokenHash" name="token_id" @isset( $token->token_hash)value="••••"@endisset>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group btn-group col-md-8" role="group">
                        <button type="submit" class="btn btn-success">Save</button>
                        @if($token->id != 0)<a href=" {{ route('admin.editUser', ['id' => $token->user->id])}}" role="button" class="btn btn-primary">Edit {{ $token->user->username }}</a>@endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
