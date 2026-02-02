@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Selection</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('selection.update', $editselection->selection_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label for="name">Name:</label>
                <input type="text" name="name" value="{{ $editselection->name }}">
            </div>
            <div>
                <label for="description">Description:</label>
                <input type="text" name="description" value="{{ $editselection->description }}">
            </div>
            <div>
                <label for="order">Order:</label>
                <input type="number" name="order" value="{{ $editselection->order }}">
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</div>

@endsection