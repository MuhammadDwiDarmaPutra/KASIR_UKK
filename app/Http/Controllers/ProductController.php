<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('Product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Product.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:png,jpg,jpeg'
        ]);
            $imagePath = $request->file('image')->store('product', 'public');

        product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image' => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Berhasil menambahkan product!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::find($id);

        return view('Product.edit', compact('product'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:' . $product->stock . '|max:1000', // Tambahkan max:1000
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);        

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete('public/'. $product->image);
            }
            $imagePath = $request->file('image')->store('product', 'public');
            $validated['image'] = $imagePath;
        } else {
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Data berhasil diubah');
    }

    public function updateStock(Request $request, string $id) {
        $request->validate([
            'stock' => 'required|integer|min:0|max:1000'
        ]);
    
        $product = Product::findOrFail($id);
        $product->stock = $request->stock;
        $product->save();
    
        return redirect()->back()->with('success', 'Berhasil update stock');
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Product::where('id',$id)->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus data');
    }
}
