<?php

namespace App\Http\Controllers\Dashboard;

use App\product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(product $proudect,Request $request)
    {

        $products = $proudect->when($request->search,function($q) use($request){

         return $q->where('name', 'like', '%' . $request->search . '%');

        })->when($request-> category_id,function($query)use($request){
          return $query->where('category_id', $request->category_id);


        })->latest()->paginate(2);
        $categories = Category::all();
        return view('dashboard.products.index',compact('products','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(product $proudect)
    {
        $categories = category::all();
        return view('dashboard.products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'image' => 'image',
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'stock' => 'required',
            
        ]);

        $request_data = $request->except('image');
        if ($request->image) {

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();

        }//end of if
         product::create($request_data);
        
        

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.products.index');


      
      
    }//end of stor

    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\proudect  $proudect
     * @return \Illuminate\Http\Response
     */
    public function edit(product $product)
    {
        $categories = Category::all();
       return view('dashboard.products.edit',compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\proudect  $proudect
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product $product)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'image' => 'image',
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'stock' => 'required',
            
        ]);

        $request_data = $request->except('');
        if ($request->image) {

            if ($product->image != 'default.png') {

                Storage::disk('public_uploads')->delete('/product_images/' . $product->image);
    
            }//end of if
    

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();

        }//end of if
        $product->update($request_data);
        
        
        

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.products.index');


        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\proudect  $proudect
     * @return \Illuminate\Http\Response
     */
    public function destroy(product $product)
    {
        if ($product->image != 'default.png') {

            Storage::disk('public_uploads')->delete('/product_images/' . $product->image);

        }//end of if

        $product->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.products.index');
    }
}
