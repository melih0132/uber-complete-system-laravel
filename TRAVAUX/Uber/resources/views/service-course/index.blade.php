@extends('layouts.app')

@section('title', 'Dashboard Service Course')

@section('content')
    <h1>Courses Demandées</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Lieu de départ</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->start_location }}</td>
                    <td>{{ $course->end_location }}</td>
                    <td>{{ $course->scheduled_at }}</td>
                    <td>{{ $course->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
