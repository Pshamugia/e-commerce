<div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $author->name ?? '') }}" required>
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
