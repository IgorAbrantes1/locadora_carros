<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Brand\Brand;
use App\Repositories\Brand\BrandRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BrandController extends Controller
{
    private Brand $model;

    /**
     * @param Brand $model
     */
    public function __construct(Brand $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $repository = new BrandRepository($this->model);

        if ($request->has('attributes_carModels')) {
            $attributes_carModels = 'carModels:id,brand_id,' . $request->get('attributes_carModels');
            $repository->selectAttributesRelatedRecords($attributes_carModels);
        } else {
            $repository->selectAttributesRelatedRecords('carModels');
        }

        if ($request->has('filter')) {
            $repository->filter($request->get('filter'));
        }

        if ($request->has('attributes')) {
            $repository->selectAttributes($request->get('attributes'));
        }
        return response()->json(['brands' => $repository->getResult(), 'status' => Response::HTTP_OK], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $brand = $request->validate($this->model->rules(), $this->model->feedback());

        $image = $request->file('image');
        $imageUrn = $image->store('brands/images', config('filesystems.default'));
        $brand['image'] = $imageUrn;

        $brand = $this->model->create($brand);

        return response()->json(['brand' => $brand, 'status' => Response::HTTP_CREATED, 'message' => 'Brand created successfully!'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Integer $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $brand = $this->model->with('carModels')->find($id);
        if ($brand === null) {
            return response()->json(['error' => ['brand' => 'This brand does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['brand' => $brand, 'status' => Response::HTTP_OK], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Integer $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Integer $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $brand = $this->model->find($id);
        if ($brand === null) {
            return response()->json(['error' => ['brand' => 'This brand does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = [];

            foreach ($brand->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }

            $data = $request->validate($dynamicRules, $brand->feedback());

        } else {
            $data = $request->validate($brand->rules(), $brand->feedback());
        }

        if ($request->file('image')) {
            Storage::disk('public')->delete($brand->image);
            $image = $request->file('image');
            $imageUrn = $image->store('brands/images', config('filesystems.default'));
            $data['image'] = $imageUrn;
        }

        $brand->update($data);
        $brand->save();

        return response()->json(['brand' => $brand->getAttributes(), 'status' => Response::HTTP_OK, 'message' => 'Brand updated successfully!'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Integer $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $brand = $this->model->find($id);
        if ($brand === null) {
            return response()->json(['error' => ['brand' => 'This brand does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }
        $brand->delete();
        Storage::disk('public')->delete($brand->image);
        return response()->json(['brand' => $brand, 'status' => Response::HTTP_OK, 'message' => 'Brand removed successfully!'], Response::HTTP_OK);
    }
}
