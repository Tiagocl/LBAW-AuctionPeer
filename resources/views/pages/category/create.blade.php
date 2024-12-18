@extends('layouts.app')

@section('title', 'Create Auction')

@section('content')
    <div class="container rectangle-div">
        <h1>Create a New Category</h1>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input placeholder="Enter Name" type="text" class="form-control" id="name" name="name" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Category</button>
        </form>
    </div>
@endsection