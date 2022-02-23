<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusTask extends Model
{
    use HasFactory;

    protected $table = 'status_task';

    protected $primaryKey = 'id';

    protected $fillable = ['status_id', 'task_id', 'task_description', 'task_remark'];
}
