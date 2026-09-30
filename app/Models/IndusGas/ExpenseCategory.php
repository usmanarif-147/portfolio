<?php
namespace App\Models\IndusGas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ExpenseCategory extends Model { protected $table = 'indus_gas_expense_categories'; protected $fillable=['name','slug','description','form_fields','is_active']; protected function casts(): array { return ['is_active'=>'boolean','form_fields'=>'array']; } public function types(): HasMany { return $this->hasMany(ExpenseType::class); } public function expenses(): HasMany { return $this->hasMany(Expense::class); } public function staff(): BelongsToMany { return $this->belongsToMany(Staff::class, 'indus_gas_expense_category_staff')->withTimestamps(); } }
