<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:المنتجات', ['only' => ['index']]);
        $this->middleware('permission:اضافة منتج', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل منتج', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف منتج', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();
        $products = Product::all();
        return view('products.products', compact('sections', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|unique:products|max:255',
        ],[
            'product_name.required' => 'يرجى إدخال اسم القسم.',
            'product_name.unique' => 'اسم القسم موجود مسبقاً',
        ]);

        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);
        session()->flash("Add", 'تمت إضافة القسم بنجاح');
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $section_id = Section::where('section_name', $request->section_name)->first()->id;
        $validated = $request->validate([
            'product_name' => 'required|max:255',
            'section_name' => 'required|max:255'.$section_id,
        ],[
            'product_name.required' => 'يرجى إدخال اسم القسم.',
            'product_name.unique' => 'اسم القسم موجود مسبقاً',
        ]);

        Product::where('id', $request->product_id)->update([
            'product_name' => $request->product_name,
            'section_id' => $section_id,
            'description' => $request->description,
        ]);
        session()->flash('edit', 'تم تعديل القسم بنجاح');
        return redirect('products');
    }

    public function destroy(Request $request)
    {
        Product::where('id', $request->id)->delete();
        session()->flash('delete', 'تم حذف القسم بنجاح');
        return redirect('products');
    }
}
