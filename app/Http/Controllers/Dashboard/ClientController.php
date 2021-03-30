<?php

namespace App\Http\Controllers\Dashboard;

use App\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients=Client::when($request->search,function($q) use ($request){

            return $q->where('first_name','like','%'. $request->search . '%')->orwhere('last_name','like','%'. $request->search . '%');

        })->latest()->paginate(5);
        
        return view('dashboard.clients.index',compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.clients.create');
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

          'first_name'  => 'required',
           'last_name' => 'required',
           'email' => 'required|unique:clients',
           'pass_word' => 'required',
           'location' => 'required',
           'address' => 'required',
           'area' => 'required',
           'phon_number' => 'required',
           'mobile_number' => 'required',
        ]);
        $request_data = $request->except('');
        Client::create($request_data);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.clients.index');

        
    }//end of store

  
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('dashboard.clients.edit',compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([

             'first_name'  => 'required',
             'last_name' => 'required',
             'email' => ['required',Rule::unique('clients')->ignore($client->id),],
             'pass_word' => 'required',
             'location' => 'required',
             'address' => 'required',
             'area' => 'required',
             'phon_number' => 'required',
             'mobile_number' => 'required',
          ]);
          $request_data = $request->except('');
          $client->update($request_data);
          session()->flash('success', __('site.updated_successfully'));
          return redirect()->route('dashboard.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.clients.index');
    }
}
