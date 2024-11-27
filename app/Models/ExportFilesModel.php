<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExportFilesModel extends Model
{
    protected $table = 'export_files';
    protected $primaryKey = 'exp_id';
    protected $fillable = [
        'file_name',
        'queue_id',
    ];

}
