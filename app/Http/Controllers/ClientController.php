<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;

class ClientController extends Controller
{
    /**
     * 	Insert a new client
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'address'           => 'required',
            'phone'             => 'required',
            'capitalization'    => 'required|numeric',
            'loan'              => 'required|numeric'
        ]);

        return Client::create($request->all());
    }

    /**
     * Displays the record of a specific client
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($client)
    {
        return Client::find($client);
    }


    /**
     * 	Display all the clients with an outstanding loan balance arranged by name
     *
     * @return \Illuminate\Http\Response
     */
    public function balance(){
        return Client::where('loan', '>', 0)->get(['name', 'loan'])->sortBy('name');
    }

    /**
     * 	Display all the clients' names and dividends
     *  the dividend is computed by 2.3% of their total capitalization
     *
     * @return \Illuminate\Http\Response
     */
    public function dividend(){
        return
            Client::query()->select('name', 'capitalization')
                ->get()->sortBy('name')
                ->map(function($client){
                    $dividend = round($client->capitalization * 0.023, 2);
                    $client->setRelation('dividend', $dividend);
                    return $client->only(['name', 'capitalization', 'dividend']);
                });
        
    }   

    /**
     * 	Adds the {amount} to the capitalization of a particular {client}.
     *
     * @return \Illuminate\Http\Response
     */
    public function deposit(Request $request, $client){
        $selected = Client::find($client);
        $selected->update(['capitalization' => $selected->capitalization + $request->amount]);
        return $client;
    }
}
