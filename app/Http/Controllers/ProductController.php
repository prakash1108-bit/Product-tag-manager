<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(25);
        return view('product.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'sku' => 'required|string|max:255',
                'quantity' => 'required|integer',
                'type' => 'required|string|max:255',
                'vendor' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $imageName);
            }
            DB::beginTransaction();
            Product::create([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'price' => $request->price,
                'sku' => $request->sku,
                'quantity' => $request->quantity,
                'type' => $request->type,
                'vendor' => $request->vendor,
                'image' => $imageName ?? null,
            ]);
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('products.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('product.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'sku' => 'required|string|max:255',
                'quantity' => 'required|integer',
                'type' => 'required|string|max:255',
                'vendor' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $product = Product::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($product->image && file_exists(public_path('images/products/' . $product->image))) {
                    unlink(public_path('images/products/' . $product->image));
                }
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $imageName);
            } else {
                $imageName = $product->image;
            }
            DB::beginTransaction();
            $product->update([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'price' => $request->price,
                'sku' => $request->sku,
                'quantity' => $request->quantity,
                'type' => $request->type,
                'vendor' => $request->vendor,
                'image' => $imageName,
            ]);
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('products.index')->with('erorr', $th->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            $product->delete();
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('products.index')->with('erorr', $th->getMessage());
        }
    }
}
