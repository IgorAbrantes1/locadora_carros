<?php

namespace App\Models\Brand;

use App\Models\CarModel\CarModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = [
        'name',
        'image'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:brands,name,' . $this->id . '|min:3',
            'image' => 'required|file|mimes:png,jpg,jpeg'
        ];
    }

    /**
     * @return array
     */
    public function feedback(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'name.unique' => 'The name of brand already exists.',
            'name.min' => 'The name must be at least 3 characters long.',
            'image.mimes' => 'The file must be an image of types: PNG, JPG or JPEG.'
        ];
    }

    public function carModels(): HasMany
    {
        return $this->hasMany(CarModel::class);
    }
}
