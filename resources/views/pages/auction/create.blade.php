@extends('layouts.app')

@section('title', 'Create Auction')

@section('content')
<div class="container rectangle-div">
    <h1>Create a New Auction</h1>
    <form action="{{ route('auction.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Auction Title" value="{{ old('title') }}" required>
        </div>
        <div class="form-group">
            <label for="description" >Description</label>
            <textarea placeholder="Auction Description" class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div>
            <label for="picture" class="form-label">Profile Picture</label>
            <input type="file" name="picture" id="picture" class="form-control @error('picture') is-invalid @enderror">
            @error('picture')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="minimum_bid">Minimum Bid</label>
            <input placeholder="Minimum Bid" type="number" class="form-control" id="minimum_bid" name="minimum_bid" value="{{ old('minimum_bid') }}" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input placeholder="Enter End Date" type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Auction</button>
    </form>
</div>
@endsection