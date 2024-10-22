@extends('admin.layouts.app')

@section('title', 'Edit Book')

@section('content')
    <h1>Edit Book</h1>

    <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.books.partials.form')
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
