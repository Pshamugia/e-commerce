<?php

namespace App\Models;
use App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relationships

    /**
     * Get the books for the category.
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
