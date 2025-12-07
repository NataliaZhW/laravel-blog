<?php

namespace App\Http\Controllers\Web;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, $lang, Category $category)
    {
        $posts = $category->posts()->paginate(10);
        return view('posts.index', compact('posts', 'category'));
    }
}
