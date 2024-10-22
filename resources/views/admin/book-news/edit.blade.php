@extends('admin.layouts.app')

@section('title', 'Edit Book News')

@section('content')
<div class="container mt-5">
    <h2>Edit Book News</h2>

    <form action="{{ route('admin.book-news.update', $bookNews->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" value="{{ $bookNews->title }}" required>
        </div>
        <div class="form-group mt-3">
            <label for="description">description</label>
            <textarea class="form-control" name="description" rows="5" required>{{ $bookNews->description }}</textarea>
        </div>
        <div class="form-group mt-3">
            <label for="full">full</label>
            <textarea class="form-control" name="full" rows="5" required>{{ $bookNews->full }}</textarea>
        </div>

        <div class="form-group mt-3">
            <label for="image">Image</label>
            @if($bookNews->image)
            <img src="{{ asset('storage/' . $bookNews->image) }}" alt="{{ $bookNews->title }}" width="100">
        @endif

 


            <input type="file" class="form-control" name="image">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
