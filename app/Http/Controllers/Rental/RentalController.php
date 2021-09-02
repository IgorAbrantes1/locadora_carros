<?php

namespace App\Http\Controllers\Rental;

use App\Http\Controllers\Controller;
use App\Models\Rental\Rental;
use App\Repositories\Rental\RentalRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RentalController extends Controller
{
    /**
     * @var Rental
     */
    private Rental $model;

    /**
     * @var RentalRepository
     */
    private RentalRepository $repository;

    /**
     * @param Rental $model
     */
    public function __construct(Rental $model)
    {
        $this->model = $model;
        $this->repository = new RentalRepository($this->model);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->repository->attributesCar($request);

        $this->repository->attributesCustomer($request);

        if ($request->has('filter')) {
            $this->repository->filter($request->get('filter'));
        }

        if ($request->has('attributes')) {
            $this->repository->selectAttributes($request->get('attributes'));
        }

        return response()->json(['customer' => $this->repository->getResult(), 'status' => Response::HTTP_OK], Response::HTTP_OK);
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
        $rental = $request->validate($this->model->rules());

        $rental = $this->model->create($rental);

        return response()->json(['rental' => $rental, 'status' => Response::HTTP_CREATED, 'message' => 'Rental created successfully!'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Integer $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $rental = $this->model->find($id);
        if ($rental === null) {
            return response()->json(['error' => ['rental' => 'This rental does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['rental' => $rental, 'status' => Response::HTTP_OK], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Rental $rental
     * @return JsonResponse
     */
    public function edit(Rental $rental): JsonResponse
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
        $rental = $this->model->find($id);
        if ($rental === null) {
            return response()->json(['error' => ['rental' => 'This rental does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = [];
            foreach ($rental->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            $data = $request->validate($dynamicRules);
        } else {
            $data = $request->validate($rental->rules());
        }

        $rental->update($data);
        $rental->save();

        return response()->json(['rental' => $rental->getAttributes(), 'status' => Response::HTTP_OK, 'message' => 'Rental updated successfully!'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rental $rental
     * @return JsonResponse
     */
    public function destroy(Rental $rental): JsonResponse
    {
        //
    }
}
