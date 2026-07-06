<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HelpPage extends Model
{
    protected $table = 'help_pages';
    protected $fillable = ['support_text', 'inventory_sop_file', 'dct_sop_file'];
}
