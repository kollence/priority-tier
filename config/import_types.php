<?php
return [
    'orders' => [
        'label' => 'Import Orders',
        'permission_required' => 'import-orders',
        'files' => [
            'file1' => [
                'label' => 'File 1',
                'headers_to_db' => [
                    'order_date' => [
                        'label' => 'Order Date',
                        'type' => 'date',
                        'validation' => [ 'required' ]
                    ],
                    'channel' => [
                        'label' => 'Channel',
                        'type' => 'string',
                        'validation' => [ 'required', 'in' => [ 'PT', 'Amazon' ] ]
                    ],
                    'sku' => [
                        'label' => 'SKU',
                        'type' => 'string',
                        'validation' => [ 'required', 'exists' => [ 'table' => 'products', 'column' => 'sku' ] ]
                    ],
                    'item_description' => [
                        'label' => 'Item Description',
                        'type' => 'string',
                        'validation' => [ 'nullable' ]
                    ],
                    'origin' => [
                        'label' => 'Origin',
                        'type' => 'string',
                        'validation' => [ 'required' ]
                    ],
                    'so_num' => [
                        'label' => 'SO#',
                        'type' => 'string',
                        'validation' => [ 'required' ]
                    ],
                    'cost' => [
                        'label' => 'Cost',
                        'type' => 'double',
                        'validation' => [ 'required' ]
                    ],
                    'shipping_cost' => [
                        'label' => 'Shipping Cost',
                        'type' => 'double',
                        'validation' => [ 'required' ]
                    ],
                    'total_price' => [
                        'label' => 'Total Price',
                        'type' => 'double',
                        'validation' => [ 'required' ]
                    ],
                ],
                'update_or_create' => [ 'so_num', 'sku' ]
            ]
        ],
    ],
    'products' => [
        'label' => 'Import Products',
        'permission_required' => 'import-products',
        'files' => [
            'file1' => [
                'label' => 'File 1',
                'headers_to_db' => [
                    'sku' => [
                        'label' => 'SKU',
                        'type' => 'string',
                        'validation' => [ 'required' ]
                    ],
                    'description' => [
                        'label' => 'Description',
                        'type' => 'string',
                        'validation' => [ 'required' ]
                    ],
                    'cost' => [
                        'label' => 'Cost',
                        'type' => 'double',
                        'validation' => [ 'required' ]
                    ],
                    'price' => [
                        'label' => 'Price',
                        'type' => 'double',
                        'validation' => [ 'required' ]
                    ],
                    'stock' => [
                        'label' => 'Stock',
                        'type' => 'integer',
                        'validation' => [ 'required' ]
                    ],
                ],
                'update_or_create' => [ 'sku' ]
            ],
            'file2' => [
                'label' => 'File 2',
                'headers_to_db' => [
                    'sku' => [
                        'label' => 'SKU',
                        'type' => 'string',
                        'validation' => [ 'required' ]
                    ],
                    'term_of_use' => [
                        'label' => 'Term of Use',
                        'type' => 'string',
                        'validation' => [ 'required' ]
                    ],
                    'cost' => [
                        'label' => 'Cost',
                        'type' => 'double',
                        'validation' => [ 'required' ]
                    ],
                    'price' => [
                        'label' => 'Price',
                        'type' => 'double',
                        'validation' => [ 'required' ]
                    ],
                    'stock' => [
                        'label' => 'Stock',
                        'type' => 'integer',
                        'validation' => [ 'required' ]
                    ],
                ],
                'update_or_create' => [ 'sku' ]
            ]
        ],
    ],
    'customers' => [
        'label' => 'Import Customers',
        'permission_required' => 'import-customers',
        'files' => [
            'file1' => [
                'label' => 'File 1',
                'headers_to_db' => [
                    'name' => [
                        'label' => 'Name',
                        'type' => 'string',
                        'validation' => [ 'required' ]
                    ],
                    'email' => [
                        'label' => 'Email',
                        'type' => 'email',
                        'validation' => [ 'required' ]
                    ],
                    'phone' => [
                        'label' => 'Phone',
                        'type' => 'string',
                        'validation' => [ 'required' ]
                    ],
                    'address' => [
                        'label' => 'Address',
                        'type' => 'string',
                        'validation' => [ 'required' ]
                    ],
                ],
                'update_or_create' => [ 'email' ]
            ]
        ],
    ],
];