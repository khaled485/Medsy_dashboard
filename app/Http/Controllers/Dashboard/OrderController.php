<?php

namespace App\Http\Controllers\Dashboard;

use App\Client;
use App\Order;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
   public function index(Request $request, Client $client){

    $orders= order::wherehas('client',function($q) use($request){

        return $q->where('first_name','like','%' . $request->search .'%');

    })->latest()->paginate(4);
    

     return view('Dashboard.orders.index',compact('orders'));

   }//end of store


   public function products(order $order){

       $products = $order->products;
       
      return view('dashboard.orders._products', compact('order', 'products'));
      
       
  
     }//end of product

     public function destroy(order $order){
        
      foreach($order->products as $product){

         $product->update([

                'stock' => $product->stock + $product->pivot->quantity

            ]);
          $order->delete();

          session()->flash('success', __('site.deleted_successfully'));
          return redirect()->route('dashboard.orders.index');

      }
     
      
 
    }//end of destroy
}//end of controller
