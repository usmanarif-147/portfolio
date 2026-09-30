<?php
namespace App\Models\IndusGas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Expense extends Model { protected $table = 'indus_gas_expenses'; protected $fillable=['expense_category_id','expense_type_id','responsible_staff_id','expense_date','amount','payer_amounts','form_data']; protected function casts(): array { return ['expense_date'=>'date','payer_amounts'=>'array','form_data'=>'array']; } public function category(): BelongsTo { return $this->belongsTo(ExpenseCategory::class,'expense_category_id'); } public function type(): BelongsTo { return $this->belongsTo(ExpenseType::class,'expense_type_id'); } public function responsibleStaff(): BelongsTo { return $this->belongsTo(Staff::class, 'responsible_staff_id'); } }
