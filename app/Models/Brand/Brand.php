<?php

namespace App\Models\Brand;

use App\Models\CarModel\CarModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\ArrayShape;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = [
        'name',
        'image'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
        'deleted_at' => 'datetime:d/m/Y H:i:s'
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
            'image' => 'required|file|image'
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
            'image.image' => 'The file must be an image of types: jpg, jpeg, png, bmp, gif, svg, or webp.'
        ];
    }

    public function carModels(): HasMany
    {
        return $this->hasMany(CarModel::class);
    }
}
