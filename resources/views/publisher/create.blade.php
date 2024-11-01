@extends('layouts.app')
 
@section('content')

<div class="container">
    <h5 class="section-title" style="position: relative; margin-bottom:25px; padding-top:55px; padding-bottom:25px; align-items: left; justify-content: left;">
        <strong><i class="bi bi-stack-overflow"></i> ატვირთე წიგნი</strong>
    </h5>

    <!-- Display messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Book Upload Form -->
    <form action="{{ route('publisher.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">წიგნის სახელწოდება</label>
            <input type="text" name="title" class="form-control" id="title" required>
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">ყდის ფოტო</label>
            <input type="file" name="photo" class="form-control" id="photo">
        </div>

        <div class="mb-3">
            <label for="cover" class="form-label">ყდის ფორმატი (რბილი/მაგარი) </label>
            <input type="text" name="cover" class="form-control" id="cover" value="{{ old('cover', $book->cover ?? '') }}">
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">წიგნის მოკლე აღწერა</label>
            <textarea name="description" class="form-control" id="description" required></textarea>
        </div>
        <div class="mb-3">
            <label for="full" class="form-label">სრული ტექსტი</label>
            <textarea name="full" class="form-control" id="full" required></textarea>
        </div>

        <!-- Additional Fields -->
        <div class="mb-3">
            <label for="price" class="form-label">ფასი</label>
            <input type="number" name="price" class="form-control" id="price" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">რაოდენობა</label>
            <input type="number" name="quantity" class="form-control" id="quantity" value="{{ old('quantity', $book->quantity ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="Publishing_date" class="form-label">გამოცემის წელი</label>
            <input type="text" name="publishing_date" class="form-control" id="publishing_date" value="{{ old('publishing_date', $book->publishing_date ?? '') }}">
        </div>

        <div class="mb-3">
            <label for="pages" class="form-label"> გვერდების რაოდენობა </label>
            <input type="number" name="pages" class="form-control" id="pages" value="{{ old('pages', $book->pages ?? '') }}">
        </div>
        
        
        <div class="mb-3">
            <label for="status" class="form-label">სტატუსი (შელახული, ახალი) </label>
            <input type="text" name="status" class="form-control" id="status" value="{{ old('status', $book->status ?? '') }}">
        </div>

        <!-- Author Selection with Chosen -->
        <div class="mb-3">
             <select name="author_id" class="chosen-select" multiple id="author_id" data-placeholder="მონიშნე ავტორი" required>
                <option value=""></option>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                @endforeach
            </select>
            <a href="{{ route('publisher.authors.create') }}" class="btn btn-link">Add New Author</a>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">კატეგორია</label>
            <select name="category_id" class="form-control" id="category_id" required>
                <option value="">მონიშნე კატეგორია</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">ატვირთვა</button>
    </form>
</div>
@endsection

@section('scripts')
<!-- Include Chosen JavaScript and Initialize -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#author_id').chosen({
            width: '100%',
            placeholder_text_single: "მონიშნე ავტორი"
        }).trigger("chosen:updated"); // Ensure Chosen is updated
    });
</script>
@endsection
