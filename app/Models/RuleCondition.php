<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuleCondition extends Model
{
    protected $table = 'rule_conditions';

    protected $fillable = [
        'rule_id',
        'product_selector',
        'operator',
        'value',
    ];
    
    public function rule()
    {
        return $this->belongsTo(Rule::class);
    }
}
