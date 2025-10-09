<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view client')->only(['index','allClients']);
        $this->middleware('permission:add client')->only(['store']);
        $this->middleware('permission:edit client')->only(['edit','update']);
        $this->middleware('permission:delete client')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.clients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request, ClientService $clientService)
    {
        return $clientService->saveClient($request->all());
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
        return Client::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, string $id, ClientService $clientService)
    {
        return $clientService->updateClient($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Client::findOrFail($id)->delete() ?
            response()->json(['success' => true, 'message' => 'Client deleted'], 200) :
            response()->json(['success' => false, 'message' => 'Client not found'], 404);
    }

    public function allClients(ClientService $clientService)
    {
        return $clientService->all_clients_in_table_lists();
    }
}
