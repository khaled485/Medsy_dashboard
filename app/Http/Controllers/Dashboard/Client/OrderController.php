<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Category;
use App\Client;
use App\Order;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){


    }// end of index

    public function create(Client $client,Request $request){
        // $categories = Category::with('products')->get();

        // if($request -> ajax()){

        //     $output="";
        //     $categories = Category::with('products')->when($request->search,function($q)use($request){

        //         return $q->where('name','like','%' . $request->search . '%');
        //     })->latest()->paginate(2);
        //     if($categories)
        //     {
        //     foreach ($categories as $key => $category) {
        //     $output.='<tr>'.
        //     '<td>'.$category->id.'</td>'.
        //     '<td>'.$category->title.'</td>'.
        //     '<td>'.$category->description.'</td>'.
        //     '<td>'.$category->price.'</td>'.
        //     '</tr>';
        //     }
        //     return Response($output);
        //        }
        //        }
    
        // else{}
        $categories = Category::with('products')->when($request->search,function($q)use($request){

            return $q->where('name','like','%' . $request->search . '%');
        })->latest()->paginate(2);
        return view('dashboard.clients.orders.create',compact('client','categories'));
    }// end of create


    public function store(Client $client, Request $request,Order $order){

        $order = $client->orders()->create([]); 
        $order->products()->attach($request->products);

        // get the area of client
        $area= $order->client()->get('area');
        $client_area= $area[0]->area;
        // end of area

        //calculate the total price and update it
        $total_price=0;
        foreach($request->products as $id=>$quantity){
             
            $price_product = Product::FindOrFail($id)->sale_price;
            $total_price += $price_product * $quantity['quantity'];
           
            Product::FindOrFail($id)->update(['stock' => Product::FindOrFail($id)->stock - $quantity['quantity']]);
         }
         $order->update(['price' => $total_price , 'area' => $client_area]);
        // end of total price 
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');
    }//end of store

    public function edit(Client $client,Order $order){

        $categories = Category::with('products')->get();
        $orders = $client->orders()->with('products')->paginate(5);
        
        return view('dashboard.clients.orders.edit',compact(['categories','orders','client','order']));
       

    }//end of edit

    public function update(Request $request,Client $client,Order $order){
         $request->validate([
           'products'=> 'required|array',

         ]);

        // get the area of client
        $area= $order->client()->get('area');
        $client_area= $area[0]->area;
        // end of area

         $this->delete_order($order);    
         $this->attach_order($client, $request);
         $total_price = $this->total_price_order($request,$order);
         $order->latest()->first()->update(['price' => $total_price, 'area' => $client_area]);
         session()->flash('success', __('site.updated_successfully'));
         return redirect()->route('dashboard.orders.index');

    }//end of update

    private function attach_order($client, $request){
       $order = $client->orders()->create([]);
       $order->products()->attach($request->products);

    }// end of Attach_order
     
    private function total_price_order($request,$order){
        $total_price=0;
        foreach($order->products as $index=>$product ){
        $total_price += $product->sale_price * $request->products[$index+1]['quantity'];
        }
        return $total_price;
       
 
    }// end of total_price_order

    private function delete_order($order){
        foreach($order->products as $product){
              
            $product->update(['stock'=> $product->stock + $product->pivot->quantity]);
       }
       $order->delete();
    }

    
}//end of Controller
