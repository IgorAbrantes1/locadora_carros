<?php

namespace App\Repositories\Brand;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Model model
 */
class BrandRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAttributesRelatedRecords(string $attributes)
    {
        $this->model = $this->model->with($attributes);
    }

    public function filter(string $filters)
    {
        $filters = explode(';', $filters);

        foreach ($filters as $filter) {
            $data = explode(':', $filter);
            $this->model = $this->model->where($data[0], $data[1], $data[2]);
        }
    }

    public function selectAttributes(string $attributes)
    {
        $this->model = $this->model->selectRaw('id,' . $attributes);
    }

    public function getResult()
    {
        return $this->model->get();
    }
}
