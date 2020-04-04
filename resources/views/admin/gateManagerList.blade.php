@extends('templates.base')

@section('title', 'Gatemanager List')

@section('content')
<div class="row align-items-start">
    <div class="col-12">
        <h2>Gatemanager List</h2>
        <div class="row">
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Gatemanager</th>
                            <th>Enabled</th>
                            <th></th>
                        </tr>
                    </thead>
                    @foreach ($gateManagers as $gateManager)
                        <tr class="border-bottom">
                            <td>{{ $gateManager->name}}</td>
                            <td>{{ $gateManager->enabled ? 'Yes' : 'No' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href=" {{ route('admin.editGateManager', ['id' => $gateManager->id]) }}" role="button" class="btn btn-primary">Edit</a>
                                    @if ($gateManager->enabled) <a href=" {{ route('admin.disableGateManager', ['id' => $gateManager->id, 'source' => 'admin.listGateManagers']) }}" role="button" class="btn btn-secondary">Disable</a>
                                    @else <a href=" {{ route('admin.enableGateManager', ['id' => $gateManager->id, 'source' => 'admin.listGateManagers']) }}" role="button" class="btn btn-info">Enable</a> @endif
                                    <a href=" {{ route('admin.deleteGateManager', ['id' => $gateManager->id, 'source' => 'admin.listGateManagers']) }}" role="button" class="btn btn-danger">Delete</a>
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
                    <a href=" {{ route('admin.addGateManager')}}" role="button" class="btn btn-success">Add Gatemanager</a>
            </div>
        </div>

    </div>
</div>
@endsection
