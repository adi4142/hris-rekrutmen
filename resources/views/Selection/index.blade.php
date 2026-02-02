@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Selection</h3>
        <div class="card-tools">
            <a href="{{ route('selection.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Order</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($selections as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $j->name }}</td>
                    <td>{{ $j->description }}</td>
                    <td>{{ $j->order }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('selection.edit', $j->selection_id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('selection.destroy', $j->selection_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection