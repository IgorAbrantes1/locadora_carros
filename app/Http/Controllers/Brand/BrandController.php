<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Brand\Brand;
use App\Repositories\Brand\BrandRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Response;

class BrandController extends Controller
{
    /**
     * @var Brand
     */
    private Brand $model;

    /**
     * @var BrandRepository
     */
    private BrandRepository $repository;

    /**
     * @param Brand $model
     */
    public function __construct(Brand $model)
    {
        $this->model = $model;
        $this->repository = new BrandRepository($this->model);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->repository->attributesCarModels($request);

        if ($request->has('filter')) {
            $this->repository->filter($request->get('filter'));
        }

        if ($request->has('attributes')) {
            $this->repository->selectAttributes('id,' . $request->get('attributes'));
        }

        return response()->json($this->repository->getResultPaginated(10), Response::HTTP_OK);
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

        $path = 'brands/images/';
        $file = $request->file('image');
        $image = $this->repository->resizeImage($file, $path);
        $brand['image'] = $image;

        $brand = $this->model->create($brand);

        Log::info('Create Brand!', $brand->toArray());

        return response()->json([$brand, 'message' => 'Brand created successfully!'], Response::HTTP_CREATED);
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
            return response()->json(['error' => 'This brand does not exist.'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($brand, Response::HTTP_OK);
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
        $brand = $this->model->all()->find($id);

        if ($brand === null) {
            return response()->json(['error' => 'This brand does not exist.'], Response::HTTP_NOT_FOUND);
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

        return response()->json([$brand->getAttributes(), 'message' => 'Brand updated successfully!'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Integer $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $brand = $this->model->all()->find($id);
        if ($brand === null) {
            return response()->json(['error' => 'This brand does not exist.'], Response::HTTP_NOT_FOUND);
        }
        Storage::disk('public')->delete($brand->image);
        $brand->delete();
        return response()->json(['message' => 'Brand removed successfully!'], Response::HTTP_OK);
    }
}
