<?php

namespace App\Repository;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{
    public function getAllChildren(): Collection
    {
        return Category::query()->with('parent')->whereNotNull('parent_id')->get();
    }
    public function getAll(): Collection
    {
        // Все категории
        return Category::all();
    }
    
    public function getChildrenOnly(): Collection
{
    // Только дочерние категории (у которых есть родитель)
    return Category::whereNotNull('parent_id')->get();
}

    public function getParents(): Collection
    {
        // Только родительские категории
        return Category::whereNull('parent_id')->get();
    }

    public function getParentsWithChildren(): Collection
    {
        // Родительские категории с дочерними
        return Category::with('children')->whereNull('parent_id')->get();
    }
}
