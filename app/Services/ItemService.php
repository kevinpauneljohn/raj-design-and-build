<?php

namespace App\Services;

use App\Models\Item;
use Yajra\DataTables\Facades\DataTables;

class ItemService
{
    public function saveItem(array $data)
    {
        if(Item::create($data))
        {
            return response()->json([
                'success' => true,
                'message' => 'Item added successfully',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Item not added'
        ]);
    }

    public function all_items_in_table_lists($supplierId)
    {
        if($supplierId != null)
        {
            $items = Item::where('supplier_id',$supplierId)->get();
        }else{
            $items = Item::all();
        }

        return DataTables::of($items)
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('M-d-Y h:i A');
            })
            ->addColumn('company_name', function ($item) {
                return '<a href="'.route('supplier.show',['supplier' => $item->supplier_id]).'">' . ucwords($item->supplier->company_name) . '</a>';
            })
            ->editColumn('unit_price', function ($item) {
                return number_format($item->unit_price, 2);
            })
            ->addColumn('action', function ($item) {
                $action = '<div class="btn-group" role="group">
                        <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                          </a>
                        <div class="dropdown-menu" role="menu">';
                if(auth()->user()->can('view item'))
                {
                    $action .= '<a href="'.route('supplier.show',['supplier' => $item->id]).'" class="dropdown-item view-item-btn text-green" id="'.$item->id.'"><i class="fa fa-eye" aria-hidden="true"></i> View</a>';
                }
                if(auth()->user()->can('edit item'))
                {
                    $action .= '<a href="#" class="dropdown-item edit-item-btn text-blue" id="'.$item->id.'"><i class="fa fa-pencil-alt" aria-hidden="true"></i> Edit</a>';
                }
                if(auth()->user()->can('delete item'))
                {
                    $action .= '<a href="#" class="dropdown-item delete-item-btn text-red" id="'.$item->id.'"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['action','company_name'])
            ->make(true);
    }

    public function updateSupplier(array $data, string $id): \Illuminate\Http\JsonResponse
    {
        $item = Item::findOrFail($id)->fill($data);
        if($item->isDirty())
        {
            if($item->save())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Item updated successfully!'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Item was not updated!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No changes were made!'
        ]);
    }
}
