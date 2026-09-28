<?php
namespace App\Models\IndusGas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Expense extends Model { protected $fillable=['expense_category_id','expense_type_id','expense_date','amount','cylinder_quantity','distance_km','fuel_average_km_per_litre','fuel_rate_per_litre','driver','payer_amounts']; protected function casts(): array { return ['expense_date'=>'date','payer_amounts'=>'array']; } public function category(): BelongsTo { return $this->belongsTo(ExpenseCategory::class,'expense_category_id'); } public function type(): BelongsTo { return $this->belongsTo(ExpenseType::class,'expense_type_id'); } }
