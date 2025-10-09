<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportItemRequest;
use App\Imports\ItemsImport;
use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Services\ItemService;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view item')->only(['index','allItems']);
        $this->middleware('permission:add item')->only(['store']);
        $this->middleware('permission:edit item')->only(['edit','update']);
        $this->middleware('permission:delete item')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreItemRequest $request, ItemService $itemService)
    {
        return $itemService->saveItem($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return $item;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, int $item, ItemService $itemService)
    {
        return $itemService->updateSupplier($request->all(), $item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        return $item->delete() ?
            response()->json(['success' => true, 'message' => 'Item deleted'], 200) :
            response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }

    public function allItems(ItemService $itemService, $supplierId): \Illuminate\Http\JsonResponse
    {
        return $itemService->all_items_in_table_lists($supplierId);
    }

    public function import(ImportItemRequest $request): \Illuminate\Http\JsonResponse
    {
        if(Excel::import(new ItemsImport($request->supplier_id), $request->file('import_item'))){
            return response()->json(['success' => true, 'message' => 'Items imported successfully'], 200);
        }
        return response()->json(['success' => false, 'message' => 'Items was not imported'], 404);

    }
}
