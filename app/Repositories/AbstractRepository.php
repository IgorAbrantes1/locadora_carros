<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

abstract class AbstractRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function filter(string $filters)
    {
        $filters = explode(';', $filters);

        foreach ($filters as $filter) {
            $data = explode(':', $filter);
            $this->model = $this->model->all()->where($data[0], $data[1], $data[2]);
        }
    }

    public function selectAttributes(string $attributes)
    {
        $this->model = $this->model->selectRaw($attributes);
    }

    public function getResult()
    {
        return $this->model->get();
    }

    public function getResultPaginated(int $recordsPerPage)
    {
        return $this->model->paginate($recordsPerPage)->onEachSide(1);
    }

    public function attributesCarModels(Request $request)
    {
        if ($request->has('attributes_carModels')) {
            $attributes = 'carModels:id,brand_id,' . $request->get('attributes_carModels');
            $this->selectAttributesRelatedRecords($attributes);
        } else {
            $this->selectAttributesRelatedRecords('carModels');
        }
    }

    public function selectAttributesRelatedRecords(string $attributes)
    {
        $this->model = $this->model->with($attributes);
    }

    public function attributesCars(Request $request)
    {
        if ($request->has('attributes_cars')) {
            $attributes = 'cars:id,car_model_id,' . $request->get('attributes_cars');
            $this->selectAttributesRelatedRecords($attributes);
        } else {
            $this->selectAttributesRelatedRecords('cars');
        }
    }

    public function attributesCar(Request $request)
    {
        if ($request->has('attributes_car')) {
            $attributes = 'car:id,car_model_id,' . $request->get('attributes_car');
            $this->selectAttributesRelatedRecords($attributes);
        } else {
            $this->selectAttributesRelatedRecords('car');
        }
    }

    public function attributesCustomer(Request $request)
    {
        if ($request->has('attributes_customer')) {
            $attributes = 'customer:id,' . $request->get('attributes_customer');
            $this->selectAttributesRelatedRecords($attributes);
        } else {
            $this->selectAttributesRelatedRecords('customer');
        }
    }

    public function attributesRentals(Request $request)
    {
        if ($request->has('attributes_rentals')) {
            $attributes = 'rentals:id,' . $request->get('attributes_rentals');
            $this->selectAttributesRelatedRecords($attributes);
        } else {
            $this->selectAttributesRelatedRecords('rentals');
        }
    }

    public function attributesCarModel(Request $request)
    {
        if ($request->has('attributes_carModel')) {
            $attributes = 'carModel:id,brand_id,' . $request->get('attributes_carModel');
            $this->selectAttributesRelatedRecords($attributes);
        } else {
            $this->selectAttributesRelatedRecords('carModel');
        }
    }

    /**
     * Resizes an image using the InterventionImage package.
     *
     * @param object $file
     * @param string $path
     * @return string
     */
    public function resizeImage(object $file, string $path = ''): string
    {
        $resize = Image::make($file)->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('png');

        $hash = md5($resize->__toString());
        $image = $path . $hash . date('__Y_m_d_H_i_s') . '.png';

        $save = Storage::disk(config('filesystems.default'))->put($image, $resize->__toString());

        return $image;
    }
}
