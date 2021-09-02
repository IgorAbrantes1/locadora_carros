<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\Controller;
use App\Models\Car\Car;
use App\Repositories\Car\CarRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CarController extends Controller
{
    /**
     * @var Car
     */
    private Car $model;

    /**
     * @var CarRepository
     */
    private CarRepository $repository;

    /**
     * @param Car $model
     */
    public function __construct(Car $model)
    {
        $this->model = $model;
        $this->repository = new CarRepository($this->model);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->repository->attributesCarModel($request);

        if ($request->has('filter')) {
            $this->repository->filter($request->get('filter'));
        }

        if ($request->has('attributes')) {
            $this->repository->selectAttributes($request->get('attributes'));
        }
        return response()->json(['cars' => $this->repository->getResult(), 'status' => Response::HTTP_OK], Response::HTTP_OK);
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
        $car = $request->validate($this->model->rules());

        $car = $this->model->create($car);

        return response()->json(['car' => $car, 'status' => Response::HTTP_CREATED, 'message' => 'Car created successfully!'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Integer $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $car = $this->model->with('carModel')->find($id);
        if ($car === null) {
            return response()->json(['error' => ['car' => 'This car does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['car' => $car, 'status' => Response::HTTP_OK], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Car $car
     * @return JsonResponse
     */
    public function edit(Car $car): JsonResponse
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
        $car = $this->model->find($id);
        if ($car === null) {
            return response()->json(['error' => ['car' => 'This car does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = [];

            foreach ($car->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }

            $data = $request->validate($dynamicRules);

        } else {
            $data = $request->validate($car->rules());
        }

        $car->update($data);
        $car->save();

        return response()->json(['car' => $car->getAttributes(), 'status' => Response::HTTP_OK, 'message' => 'Car updated successfully!'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Integer $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $car = $this->model->find($id);
        if ($car === null) {
            return response()->json(['error' => ['car' => 'This car does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }
        $car->delete();
        return response()->json(['car' => $car, 'status' => Response::HTTP_OK, 'message' => 'Car removed successfully!'], Response::HTTP_OK);
    }
}
