<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all(); //select * from categories
        $selectedCategory = Request()->category_id;
     
     //methode de composition
//         $products = Product::with('category');
// if ($selectedCategory)
// {
// $products = $products->where('category_id', $selectedCategory);
// }

//    $products = $products->get();
//methode de redondance

// if ($selectedCategory)
//     $products = Product::with('category')->where('category_id', $selectedCategory)->get();
// else
//     $products = Product::with('category')->get();



        $products = Product::with('category')
            ->when($selectedCategory, function ($query) use($selectedCategory)  {
                return $query->where('category_id', $selectedCategory);
            })
            ->get();

            //dd($products->toSql());

        return view('products.index', compact('categories', 'products', 'selectedCategory'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('products.show', compact('product'));
    }

    public function search()
    {
        $q =Request('q');
        $categoryId = Request('category_id');
        
        // $products = Product::with('category');
        
        // if ($query) {
        //     $products = $products->where('name', 'like', '%' . $query . '%');
        // }
        
        // if ($categoryId) {
        //     $products = $products->where('category_id', $categoryId);
        // }
        
        // $products = $products->get();


        $products = Product::with('category')
        ->when($q, function ($query) use($q)  {
            return $query->where('name', 'like', '%' . $q . '%');
        })
        ->when($categoryId, function ($query) use($categoryId){
            return $query->where('category_id', $categoryId);
        })
        ->get();
        
        return view('products.partials._product-row', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
