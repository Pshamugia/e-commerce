@extends('admin.layouts.app')

@section('title', 'Add Book')

@section('content')
    <h1>Add Book</h1>

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.books.partials.form')
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
