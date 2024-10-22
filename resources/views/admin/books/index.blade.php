@extends('admin.layouts.app')

@section('title', 'Books')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h1>Books</h1>
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary">Add Book</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($books->count())
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Photo</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th width="200px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr>
                        <td>
                            @if (isset($book->photo))
                            <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" class="img-fluid" width="150">
                        @endif
                        </td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author->name }}</td>
                        <td>{{ $book->category->name }}</td>
                        <td>
                            <form action="{{ route('admin.books.toggleVisibility', $book->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn {{ $book->hide ? 'btn-danger' : 'btn-warning' }}">
                                    @if($book->hide)
                                        Show
                                    @else
                                        Hide
                                    @endif
                                </button>
                            </form>
                            
                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this book?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div> {{ $books->links('pagination.custom-pagination') }} </div>
    @else
        <div class="alert alert-warning">No books available.</div>
    @endif
@endsection
