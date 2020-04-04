@extends('templates.base')

@section('title', 'User List')

@section('content')
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Mail</th>
        @foreach ($users as $user)

        @endforeach
