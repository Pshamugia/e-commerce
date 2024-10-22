<!-- jQuery (required by Chosen) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Chosen JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $book->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="price" class="form-label">Price</label>
    <input type="number" name="price" class="form-control" id="price" value="{{ old('price', $book->price ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="photo" class="form-label">Photo</label>
    @if (isset($book) && $book->photo)
        <div class="mb-2">
            <img src="{{ asset('storage/'.$book->photo) }}" class="img-thumbnail" width="150" alt="{{ $book->title }}">
        </div>
    @endif
    <input type="file" name="photo" class="form-control" id="photo" {{ isset($book) ? '' : 'required' }}>
</div>

<div class="mb-3">
    <label for="quantity" class="form-label">Quantity</label>
    <input type="number" name="quantity" class="form-control" id="quantity" value="{{ old('quantity', $book->quantity ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="Publishing_date" class="form-label">Publishing Date</label>
    <input type="text" name="publishing_date" class="form-control" id="publishing_date" value="{{ old('publishing_date', $book->publishing_date ?? '') }}">
</div>


<div class="mb-3">
    <label for="cover" class="form-label">ყდის ფორმატი (რბილი/მაგარი) </label>
    <input type="text" name="cover" class="form-control" id="cover" value="{{ old('cover', $book->cover ?? '') }}">
</div>


<div class="mb-3">
    <label for="pages" class="form-label"> გვერდების რაოდენობა </label>
    <input type="number" name="pages" class="form-control" id="pages" value="{{ old('pages', $book->pages ?? '') }}">
</div>


<div class="mb-3">
    <label for="status" class="form-label">სტატუსი (შელახული, ახალი) </label>
    <input type="text" name="status" class="form-control" id="status" value="{{ old('status', $book->status ?? '') }}">
</div>





<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" class="form-control" id="description" required>{{ old('description', $book->description ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label for="full" class="form-label">Full Content</label>
    <textarea name="full" class="form-control" id="full" required>{{ old('full', $book->full ?? '') }}</textarea>
</div>
<div class="mb-3">
  
 
    <select name="author_id" class="chosen-select" multiple id="author_id" style="width:320px; height:35px; margin-top:35px; position: relative;" data-placeholder="მონიშნე ავტორი" required>
        <option value=""></option> <!-- Add an empty option to trigger the placeholder -->
        @foreach ($authors as $author)
            <option value="{{ $author->id }}" {{ (old('author_id', $book->author_id ?? '') == $author->id) ? 'selected' : '' }}>
                {{ $author->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="category_id" class="form-label">Category</label>
    <select name="category_id" class="form-select" id="category_id" required>
        <option value="">Select Category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ (old('category_id', $book->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>
<!-- jQuery (Chosen requires jQuery) -->
 
<!-- Chosen JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

<!-- Initialize Chosen -->
<script>
    $(document).ready(function() {
        $('.chosen-select').chosen({
            no_results_text: "Oops, nothing found!"
        });
    });
</script>