<?php

namespace Database\Factories\Brand;

use App\Models\Brand\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
        $image = $this->faker->randomElements([
            'bmw.png',
            'chevrolet.png',
            'ford.png',
            'honda.png',
            'hyundai.png',
            'kia.png',
            'nissan.png',
            'toyota.png',
            'volkswagen.png'
        ], 1, true);

        return [
            'name' => $this->faker->unique()->lastName,
            'image' => $this->resizeImage($image[0])
        ];
    }

    /**
     * Resizes an image using the InterventionImage package.
     *
     * @param string $random
     * @return string
     */
    private function resizeImage(string $random): string
    {
        $image = 'D:\Downloads\imagens_marcas\\' . $random;
        $resize = Image::make($image)->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('png');

        $hash = md5($resize->__toString());
        $image = 'brands/images/' . $hash . date('__Y_m_d_H_i_s') . '.png';

        Storage::disk(config('filesystems.default'))->put($image, $resize->__toString());
        return $image;
    }
}
