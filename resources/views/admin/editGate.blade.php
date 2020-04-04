@extends('templates.base')

@section('title') {{ ($gate->id == 0) ? "Create Gate" : "Edit Gate" }} @endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4 border">
            <h2>@if($gate->id == 0) Create Gate @else Edit Gate @endif</h2>
            <form  action="{{ route('admin.saveGate',['id'=> $gate->id])}}" method="post">
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="inputNicename">Name</label>
                        <input type="text" class="form-control" id="inputNicename" name="nicename" value="{{ $gate->nice_name }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="inputNicename">API Identifier</label>
                        <input type="text" class="form-control" id="inputNicename" name="name" value="{{ $gate->name }}" placeholder="Leave empty for automatic name assignment">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="inputGatemanager">Gatemanager</label>
                        <select class="form-control" id="inputGatemanager" name="gateManager">
                            @foreach ($gateManagers as $gateManager)
                                <option value="{{ $gateManager->id }}" @if ($gateManager == $gate->gateManager) selected @endif> {{ $gateManager->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="inputNotes">Notes</label>
                        <textarea class="form-control" id="inputNotes" name="notes" rows="3" maxlength="500">{{ $gate->notes }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-2 form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="enabled" @if($gate->enabled) checked @endif id="inputEnabled">
                        <label class="form-check-label" for="inputEnabled">Enabled</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <div class="form-group btn-group col-md-8" role="group">
                            <button type="submit" class="btn btn-success">Save</button>
                            @if($gate->id != 0)<a href=" {{ route('admin.editGateManager', ['id' => $gate->gateManager->id])}}" role="button" class="btn btn-primary">Edit {{ $gate->gateManager->name }}</a>@endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @if($gate->id !== 0)
            <div class="col-md-4 border">
                <h2>Edit Gate Users</h2>
                <form  action={{ route('admin.saveGateUsers',['id'=> $gate->id])}} method="post">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Gate</th>
                                        <th>Enabled</th>
                                    </tr>
                                </thead>
                                @foreach ($users as $user)
                                    <tr class="border-bottom @if(in_array($user->id, $gateUserIds))table-success @endif">
                                        <td>{{ $user->username}}</td>
                                        <td>
                                            <div class="form-group col-md-2 form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="user[]" value="{{$user->id}}" @if(in_array($user->id, $gateUserIds)) checked @endif>
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
        @endif
    </div>
@endsection
