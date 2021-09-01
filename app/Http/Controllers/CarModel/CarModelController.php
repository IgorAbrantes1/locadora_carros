<?php

namespace App\Http\Controllers\CarModel;

use App\Http\Controllers\Controller;
use App\Models\CarModel\CarModel;
use App\Repositories\CarModel\CarModelRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CarModelController extends Controller
{
    /**
     * @var CarModel
     */
    private CarModel $model;

    public function __construct(CarModel $carModel)
    {
        $this->model = $carModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $repository = new CarModelRepository($this->model);

        if ($request->has('attributes_brand')) {
            $attributes_brand = 'brand:id,' . $request->get('attributes_brand');
            $repository->selectAttributesRelatedRecords($attributes_brand);
        } else {
            $repository->selectAttributesRelatedRecords('brand');
        }

        if ($request->has('filter')) {
            $repository->filter($request->get('filter'));
        }

        if ($request->has('attributes')) {
            $repository->selectAttributes($request->get('attributes'));
        }
        return response()->json(['carModels' => $repository->getResult(), 'status' => Response::HTTP_OK], Response::HTTP_OK);
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
        $carModel = $request->validate($this->model->rules());

        $image = $request->file('image');
        $imageUrn = $image->store('carModels/images', config('filesystems.default'));
        $carModel['image'] = $imageUrn;

        $carModel = $this->model->create($carModel);

        return response()->json(['brand' => $carModel, 'status' => Response::HTTP_CREATED, 'message' => 'Car model created successfully!'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Integer $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $carModel = $this->model->with('brand')->find($id);
        if ($carModel === null) {
            return response()->json(['error' => ['carModel' => 'This car model does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['carModel' => $carModel, 'status' => Response::HTTP_OK], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CarModel $carModel
     * @return JsonResponse
     */
    public function edit(CarModel $carModel): JsonResponse
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
        $carModel = $this->model->find($id);
        if ($carModel === null) {
            return response()->json(['error' => ['carModel' => 'This car model does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = [];

            foreach ($carModel->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }

            $data = $request->validate($dynamicRules);

        } else {
            $data = $request->validate($carModel->rules());
        }

        if ($request->file('image')) {
            Storage::disk('public')->delete($carModel->image);
            $image = $request->file('image');
            $data['image'] = $image->store('carModels/images', config('filesystems.default'));
        }

        $carModel->update($data);

        return response()->json(['carModel' => $carModel->getAttributes(), 'status' => Response::HTTP_OK, 'message' => 'Car model updated successfully!'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $carModel = $this->model->find($id);
        if ($carModel === null) {
            return response()->json(['error' => ['carModel' => 'This car model does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }
        $carModel->delete();
        Storage::disk('public')->delete($carModel->image);
        return response()->json(['carModel' => $carModel, 'status' => Response::HTTP_OK, 'message' => 'Car model removed successfully!'], Response::HTTP_OK);
    }
}
