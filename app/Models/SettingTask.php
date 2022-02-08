<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingTask extends Model
{
    use HasFactory;

    protected $table = 'setting_task';

    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'team_id', 'answer'];
}
