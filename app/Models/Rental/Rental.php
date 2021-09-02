<?php

namespace App\Models\Rental;

use App\Models\Car\Car;
use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    use HasFactory;

    protected $table = 'rentals';

    protected $fillable = [
        'customer_id',
        'car_id',
        'start_date_period',
        'end_date_expected_period',
        'end_date_performed_period',
        'daily_value',
        'km_initial',
        'km_final',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'start_date_period' => 'datetime: d/m/Y H:i:s',
        'end_date_expected_period' => 'datetime: d/m/Y H:i:s',
        'end_date_performed_period' => 'datetime: d/m/Y H:i:s',
        'created_at' => 'datetime: Y-m-d H:i:s',
        'updated_at' => 'datetime: Y-m-d H:i:s',
        'deleted_at' => 'datetime: Y-m-d H:i:s'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'car_id' => 'required|exists:cars,id',
            'start_date_period' => 'required|datetime',
            'end_date_expected_period' => 'required',
            'end_date_performed_period' => 'required',
            'daily_value' => 'required',
            'km_initial' => 'required',
            'km_final' => 'required',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
