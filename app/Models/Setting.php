<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $primaryKey = 'id';

    protected $fillable = [
        'progress_title',
        'is_active',
        'html_name'
    ];
}
