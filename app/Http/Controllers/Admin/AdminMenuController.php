<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminMenuController extends Controller
{
    /**
     * List Menu Items
     */
    public function index(Request $request): View
    {
        $categoryFilter = $request->query('category', 'all');
        $search = $request->query('search');

        $query = Food::with('categoryItem')->latest();

        if ($categoryFilter !== 'all') {
            $query->where('category', $categoryFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        $foods = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('admin.menu.index', compact('foods', 'categories', 'categoryFilter', 'search'));
    }

    /**
     * Store new menu item
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'prepTime' => 'nullable|string|max:50',
            'calories' => 'nullable|string|max:50',
            'popular' => 'nullable|boolean',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'ingredients' => 'nullable|string', // comma separated
        ]);

        $category = Category::where('slug', $validated['category'])
            ->orWhere('name', $validated['category'])
            ->first();

        // Handle Image
        $imageUrl = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop&q=80'; // fallback
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('foods', 'public');
            $imageUrl = asset('storage/' . $path);
        } elseif (!empty($validated['image_url'])) {
            $imageUrl = $validated['image_url'];
        }

        // Parse ingredients
        $ingredients = [];
        if (!empty($validated['ingredients'])) {
            $ingredients = array_map('trim', explode(',', $validated['ingredients']));
        }

        Food::create([
            'name' => $validated['name'],
            'category' => $category ? $category->slug : $validated['category'],
            'category_id' => $category ? $category->id : null,
            'description' => $validated['description'],
            'price' => $validated['price'],
            'rating' => 5.0,
            'reviews' => 0,
            'image' => $imageUrl,
            'ingredients' => $ingredients,
            'prepTime' => $validated['prepTime'] ?: '15-20 min',
            'calories' => $validated['calories'] ?: '500 kkal',
            'popular' => $request->boolean('popular'),
        ]);

        return redirect()->route('admin.menu.index')->with('success', "Menu '{$validated['name']}' berhasil ditambahkan!");
    }

    /**
     * Update menu item
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $food = Food::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'prepTime' => 'nullable|string|max:50',
            'calories' => 'nullable|string|max:50',
            'popular' => 'nullable|boolean',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'ingredients' => 'nullable|string',
        ]);

        $category = Category::where('slug', $validated['category'])
            ->orWhere('name', $validated['category'])
            ->first();

        $imageUrl = $food->image;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('foods', 'public');
            $imageUrl = asset('storage/' . $path);
        } elseif (!empty($validated['image_url'])) {
            $imageUrl = $validated['image_url'];
        }

        $ingredients = $food->ingredients ?? [];
        if (isset($validated['ingredients'])) {
            $ingredients = array_filter(array_map('trim', explode(',', $validated['ingredients'])));
        }

        $food->update([
            'name' => $validated['name'],
            'category' => $category ? $category->slug : $validated['category'],
            'category_id' => $category ? $category->id : null,
            'description' => $validated['description'],
            'price' => $validated['price'],
            'image' => $imageUrl,
            'ingredients' => $ingredients,
            'prepTime' => $validated['prepTime'] ?: $food->prepTime,
            'calories' => $validated['calories'] ?: $food->calories,
            'popular' => $request->boolean('popular'),
        ]);

        return redirect()->route('admin.menu.index')->with('success', "Menu '{$food->name}' berhasil diperbarui!");
    }

    /**
     * Toggle popular status
     */
    public function togglePopular(int $id): RedirectResponse
    {
        $food = Food::findOrFail($id);
        $food->popular = !$food->popular;
        $food->save();

        $statusText = $food->popular ? 'ditandai Populer' : 'dihapus dari Populer';
        return back()->with('success', "Menu {$food->name} berhasil {$statusText}.");
    }

    /**
     * Delete menu item
     */
    public function destroy(int $id): RedirectResponse
    {
        $food = Food::findOrFail($id);
        $foodName = $food->name;
        $food->delete();

        return redirect()->route('admin.menu.index')->with('success', "Menu '{$foodName}' berhasil dihapus.");
    }
}
