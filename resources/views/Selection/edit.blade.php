@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Selection</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('selection.update', $editselection->selection_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" name="name" value="{{ $editselection->name }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" name="description" value="{{ $editselection->description }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('selection.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection