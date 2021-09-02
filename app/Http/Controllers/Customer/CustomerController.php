<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Repositories\Customer\CustomerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    /**
     * @var Customer
     */
    private Customer $model;

    /**
     * @var CustomerRepository
     */
    private CustomerRepository $repository;

    /**
     * @param Customer $model
     */
    public function __construct(Customer $model)
    {
        $this->model = $model;
        $this->repository = new CustomerRepository($this->model);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->repository->attributesRentals($request);

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
        $customer = $request->validate($this->model->rules());

        $customer = $this->model->create($customer);

        return response()->json(['customer' => $customer, 'status' => Response::HTTP_CREATED, 'message' => 'Customer created successfully!'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Integer $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $customer = $this->model->find($id);
        if ($customer === null) {
            return response()->json(['error' => ['customer' => 'This customer does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['customer' => $customer, 'status' => Response::HTTP_OK], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @return JsonResponse
     */
    public function edit(Customer $customer): JsonResponse
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
        $customer = $this->model->find($id);
        if ($customer === null) {
            return response()->json(['error' => ['customer' => 'This customer does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = [];
            foreach ($customer->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            $data = $request->validate($dynamicRules);
        } else {
            $data = $request->validate($customer->rules());
        }

        $customer->update($data);
        $customer->save();

        return response()->json(['customer' => $customer->getAttributes(), 'status' => Response::HTTP_OK, 'message' => 'Customer updated successfully!'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Integer $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $customer = $this->model->find($id);
        if ($customer === null) {
            return response()->json(['error' => ['customer' => 'This customer does not exist.'], 'status' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
        }
        $customer->delete();
        return response()->json(['customer' => $customer, 'status' => Response::HTTP_OK, 'message' => 'Customer removed successfully!'], Response::HTTP_OK);
    }
}
