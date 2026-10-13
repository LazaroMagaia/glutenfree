<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\{ProductRequest,ProductUpdateRequest};
use App\Models\Admin\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = ProductCategory::with('products')->get();
        return view('Backend.Admin.Products.index', compact('products'));
    }
    public function create()
    {
        return view('Backend.Admin.Products.create');
    }
    public function edit($slug)
    {
        return view('Backend.Admin.Products.edit', compact('slug'));
    }
    public function show($slug)
    {
        return view('Backend.Admin.Products.show', compact('slug'));
    }
    public function store(Request $request)
    {
        // Implement product creation logic here
    }
    public function update(Request $request, $slug)
    {

    }
    public function destroy($slug)
    {
        // Implement product deletion logic here
    }
}
