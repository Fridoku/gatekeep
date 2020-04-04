@extends('templates.base')

@section('title') {{ ($gateManager->id == 0) ? "Create Gatemanager" : "Edit Gatemanager" }} @endsection

@section('content')
    @if($gateManager->id != 0)
        <div class="row d-flex justify-content-center">
            <div class="col-md-8 input-group py-3 border">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">API Key</span>
                  </div>
                <input type="text" class="form-control" id="APIKey"value="{{ $gateManager->api_key }}" readonly>
                <div class="input-group-append">
                    <a href=" {{ route('admin.regenerateGateManager', ['id' => $gateManager->id])}}" role="button" class="btn btn-warning">Regenerate API Key</a>
                </div>
            </div>
        </div>
    @endif
    <div class="row d-flex justify-content-center">
        <div class="col-md-4 border">
            <h2>@if($gateManager->id == 0) Create Gatemanager @else Edit Gatemanager @endif</h2>
            <form  action="{{ route('admin.saveGateManager',['id'=> $gateManager->id])}}" method="post">
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="inputName">Name</label>
                        <input type="text" class="form-control" id="inputName" name="name" value="{{ $gateManager->name }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="inputNotes">Notes</label>
                        <textarea class="form-control" id="inputNotes" name="notes" rows="3" maxlength="500">{{ $gateManager->notes }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-2 form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="enabled" @if($gateManager->enabled) checked @endif id="inputEnabled">
                        <label class="form-check-label" for="inputEnabled">Enabled</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="inputMac">(optional) MAC Address Verification</label>
                        <input type="text" class="form-control" id="inputMac" name="mac" value="{{ $gateManager->mac }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
        @if($gateManager->id !== 0)
            <div class="col-md-4 border">
                <h2>Gates</h2>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Gate</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @foreach ($gateManager->gates as $gate)
                                <tr class="border-bottom">
                                    <td>{{ $gate->nice_name}}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href=" {{ route('admin.editGate', ['id' => $gate->id]) }}" role="button" class="btn btn-primary">Edit</a>
                                            @if ($gate->enabled) <a href=" {{ route('admin.disableGate', ['id' => $gate->id, 'source' => 'admin.editGateManager', 'sourceid' => $gateManager->id]) }}" role="button" class="btn btn-secondary">Disable</a>
                                            @else <a href=" {{ route('admin.enableGate', ['id' => $gate->id, 'source' => 'admin.editGateManager', 'sourceid' => $gateManager->id]) }}" role="button" class="btn btn-info">Enable</a> @endif
                                            <a href=" {{ route('admin.deleteGate', ['id' => $gate->id, 'source' => 'admin.editGateManager', 'sourceid' => $gateManager->id]) }}" role="button" class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="btn-group col-md-6" role="group">
                        <a href=" {{ route('admin.addGate', ['gateManager' => $gateManager->id]) }}" role="button" class="btn btn-success">Add Gate</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
