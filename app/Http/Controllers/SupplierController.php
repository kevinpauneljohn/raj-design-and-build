<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Services\SupplierService;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view supplier')->only(['index','allSuppliers']);
        $this->middleware('permission:add supplier')->only(['store']);
        $this->middleware('permission:edit supplier')->only(['edit','update']);
        $this->middleware('permission:delete supplier')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.supplier.index');
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
    public function store(StoreSupplierRequest $request, SupplierService $supplierService)
    {
        return $supplierService->saveSupplier($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view('dashboard.supplier.profile',compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return $supplier;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, string $supplier, SupplierService $supplierService)
    {
        return $supplierService->updateSupplier($request->all(), $supplier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        return $supplier->delete() ?
            response()->json(['success' => true, 'message' => 'Supplier deleted'], 200) :
            response()->json(['success' => false, 'message' => 'Supplier not found'], 404);
    }

    public function allSuppliers(SupplierService $supplierService): \Illuminate\Http\JsonResponse
    {
        return $supplierService->all_suppliers_in_table_lists();
    }
}
