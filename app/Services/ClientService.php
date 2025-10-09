<?php

namespace App\Services;

use App\Models\Client;
use Yajra\DataTables\Facades\DataTables;

class ClientService
{
    public function saveClient(array $data): \Illuminate\Http\JsonResponse
    {
        if(Client::create($data))
        {
            return response()->json([
                'success' => true,
                'message' => 'Client added successfully',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Client not added'
        ]);
    }

    public function updateClient(array $data, string $id): \Illuminate\Http\JsonResponse
    {
        $client = Client::findOrFail($id)->fill($data);
        if($client->isDirty())
        {
            if($client->save())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Client updated successfully!'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Client was not updated!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No changes were made!'
        ]);
    }

    public function all_clients_in_table_lists()
    {
        $clients = Client::all();
        return DataTables::of($clients)
            ->editColumn('created_at', function ($client) {
                return $client->created_at->format('M/d/Y');
            })
            ->editColumn('address', function ($client) {
                return ucwords($client->address);
            })
           ->addColumn('action', function ($client) {
               $action = '';
               if(auth()->user()->can('edit client'))
               {
                   $action .= '<a href="#" class="btn btn-xs btn-primary edit-client-btn mr-1 mb-1" id="'.$client->id.'">Edit</a>';
               }
               if(auth()->user()->can('delete client'))
               {
                   $action .= '<a href="#" class="btn btn-xs btn-danger delete-client-btn mr-1 mb-1" id="'.$client->id.'">Delete</a>';
               }
               return $action;
           })
            ->rawColumns(['action'])
            ->make(true);
    }
}
