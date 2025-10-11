<?php

namespace App\Imports;

use App\Models\Item;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ItemsImport implements ToModel, WithHeadingRow
{
    use Importable;
    private string $supplierId;
    public function __construct(string $supplierId)
    {
        $this->supplierId = $supplierId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Item([
            'supplier_id' => $this->supplierId,
            'item_name' => $row['item_name'],
            'quantity' => $row['quantity'],
            'unit' => $row['unit'],
            'unit_price' => $row['unit_price'],
        ]);
    }
}
