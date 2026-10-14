<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Get list of foods with filtering and sorting
     */
    public function index(Request $request): JsonResponse
    {
        $query = Food::query();

        // Filter by category slug or name (e.g. Burgers, Pizza, etc.)
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Search by keyword (name or description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter popular
        if ($request->boolean('popular')) {
            $query->where('popular', true);
        }

        // Sorting
        $sortBy = $request->get('sort', 'popular');
        switch ($sortBy) {
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
            default:
                $query->orderBy('popular', 'desc')->orderBy('rating', 'desc');
                break;
        }

        $foods = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar menu berhasil diambil',
            'total' => $foods->count(),
            'data' => $foods,
        ]);
    }

    /**
     * Get single food detail
     */
    public function show(int $id): JsonResponse
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Menu makanan tidak ditemukan',
            ], 404);
        }

        // Similar foods in the same category
        $related = Food::where('category', $food->category)
            ->where('id', '!=', $food->id)
            ->take(3)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Detail menu berhasil diambil',
            'data' => $food,
            'related' => $related,
        ]);
    }

    /**
     * Store new food item
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'rating' => 'nullable|numeric|between:0,5',
            'reviews' => 'nullable|integer|min:0',
            'image' => 'required|string',
            'ingredients' => 'nullable|array',
            'prepTime' => 'nullable|string|max:50',
            'calories' => 'nullable|string|max:50',
            'popular' => 'nullable|boolean',
        ]);

        // Auto link category_id if exists
        $cat = Category::where('slug', $validated['category'])->orWhere('name', $validated['category'])->first();
        if ($cat) {
            $validated['category_id'] = $cat->id;
        }

        $food = Food::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan',
            'data' => $food,
        ], 201);
    }
}
