<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionModel extends Model
{
    use SoftDeletes;

    protected $table = 'permission';
    protected $primaryKey = 'id';
    protected $fillable = [
        'slug',
        'view',
        'add',
        'delete',
        'edit',
        'other',
        'id_role',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
