<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'item_name',
        'category',
        'description',
        'image',
        'location',
        'date',
        'status'
    ];

    protected $useTimestamps = false;
}