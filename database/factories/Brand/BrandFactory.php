<?php

namespace Database\Factories\Brand;

use App\Models\Brand\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->lastName,
            'image' => $this->resizeImage()
        ];
    }

    /**
     * Resizes an image using the InterventionImage package.
     *
     * @param string $random
     * @return string
     */
    private function resizeImage(): string
    {
        $image = public_path('images/default/') . 'bmw.png';
        $resize = Image::make($image)->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('png');

        $hash = Hash::make($resize->__toString());
        $image = 'brands/images/' . $hash . date('__Y_m_d_H_i_s') . '.png';

        Storage::disk(config('filesystems.default'))->put($image, $resize->__toString());
        return $image;
    }
}