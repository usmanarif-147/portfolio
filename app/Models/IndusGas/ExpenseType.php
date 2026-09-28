<?php
namespace App\Models\IndusGas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ExpenseType extends Model { protected $fillable=['expense_category_id','name','slug','is_active']; public function category(): BelongsTo { return $this->belongsTo(ExpenseCategory::class,'expense_category_id'); } }
