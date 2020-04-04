@extends('templates.base')

@section('title', 'Gate List')

@section('content')
<div class="row align-items-start">
    <div class="col-12">
        <h2>Gate List</h2>
        <div class="row">
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Gate</th>
                            <th>Gatemanager</th>
                            <th>Enabled</th>
                            <th></th>
                        </tr>
                    </thead>
                    @foreach ($gates as $gate)
                        <tr class="border-bottom">
                            <td>{{ $gate->nice_name}}</td>
                            <td><a href="{{ route('admin.editGateManager', ['id' => $gate->gateManager->id]) }}">{{ $gate->gateManager->name }}<a></td>
                            <td>{{ $gate->enabled ? 'Yes' : 'No' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href=" {{ route('admin.editGate', ['id' => $gate->id]) }}" role="button" class="btn btn-primary">Edit</a>
                                    @if ($gate->enabled) <a href=" {{ route('admin.disableGate', ['id' => $gate->id, 'source' => 'admin.listGates']) }}" role="button" class="btn btn-secondary">Disable</a>
                                    @else <a href=" {{ route('admin.enableGate', ['id' => $gate->id, 'source' => 'admin.listGates']) }}" role="button" class="btn btn-info">Enable</a> @endif
                                    <a href=" {{ route('admin.deleteGate', ['id' => $gate->id, 'source' => 'admin.listGates']) }}" role="button" class="btn btn-danger">Delete</a>
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
                    <a href=" {{ route('admin.addGate')}}" role="button" class="btn btn-success">Add Gate</a>
            </div>
        </div>

    </div>
</div>
@endsection
