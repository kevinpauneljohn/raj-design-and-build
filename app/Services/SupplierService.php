<?php

namespace App\Services;

use App\Models\Supplier;
use Yajra\DataTables\Facades\DataTables;

class SupplierService
{
    public function saveSupplier(array $data)
    {
        if(Supplier::create($data))
        {
            return response()->json([
                'success' => true,
                'message' => 'Supplier added successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Supplier not added'
        ]);
    }

    public function updateSupplier(array $data, string $id): \Illuminate\Http\JsonResponse
    {
        $supplier = Supplier::findOrFail($id)->fill($data);
        if($supplier->isDirty())
        {
            if($supplier->save())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier updated successfully!'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Supplier was not updated!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No changes were made!'
        ]);
    }

    public function all_suppliers_in_table_lists(): \Illuminate\Http\JsonResponse
    {
        $suppliers = Supplier::all();
        return DataTables::of($suppliers)
            ->editColumn('created_at', function ($supplier) {
                return $supplier->created_at->format('M/d/Y');
            })
            ->editColumn('company_name', function ($supplier) {
                return '<a href="'.route('supplier.show',['supplier' => $supplier->id]).'">' . ucwords($supplier->company_name) . '</a>';
            })
            ->editColumn('company_address', function ($supplier) {
                return ucwords($supplier->company_address);
            })
            ->addColumn('action', function ($supplier) {
                $action = '<div class="btn-group" role="group">
                        <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                          </a>
                        <div class="dropdown-menu" role="menu">';
                if(auth()->user()->can('manage supplier'))
                {
                    $action .= '<a href="'.route('supplier.show',['supplier' => $supplier->id]).'" class="dropdown-item manage-supplier-btn" id="'.$supplier->id.'"><i class="fa fa-folder-open" aria-hidden="true"></i> Manage</a>
                                <div class="dropdown-divider"></div>';
                }
                if(auth()->user()->can('edit supplier'))
                {
                    $action .= '<a href="#" class="dropdown-item edit-supplier-btn text-blue" id="'.$supplier->id.'"><i class="fa fa-pencil-alt" aria-hidden="true"></i> Edit</a>';
                }
                if(auth()->user()->can('delete supplier'))
                {
                    $action .= '<a href="#" class="dropdown-item delete-supplier-btn text-red" id="'.$supplier->id.'"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['action','company_name'])
            ->make(true);
    }
}
