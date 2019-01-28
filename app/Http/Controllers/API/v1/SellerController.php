<?php
declare(strict_types = 1);

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SellerResource;

use App\Services\Seller\Contracts\SellerServiceContract;
use App\Repositories\Seller\SellerRepository;

use App\Services\Seller\Validators\CreateSellerRequestValidator;
use App\Services\Seller\Validators\UpdateSellerRequestValidator;

use Illuminate\Validation\ValidationException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Seller\Exceptions\SellerAlreadyExistsException;

class SellerController extends Controller
{
    protected $sellerRepo;
    protected $sellerService;

    public function __construct(
        SellerRepository $sellerRepo,
        SellerServiceContract $sellerService
    ) {
        $this->sellerRepo = $sellerRepo;
        $this->sellerService = $sellerService;
    }


    /**
     * View company-seller
     * METHOD: get
     * URL: /seller/{id}
     * @param string $slug
     * @return JsonResponse
     */
    public function view(string $slug): JsonResponse
    {
        try {
            $seller = $this->sellerRepo->findBySlug($slug);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Seller not exist.'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Seller show error.'
            ], 500);
        }

        return response()->json([
            'status' => 'Success',
            'seller' => SellerResource::make($seller)
        ]);
    }


    /**
     * Create Seller
     * METHOD: post
     * URL: /seller/create
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $data = (new CreateSellerRequestValidator)->attempt($request);
            $seller = $this->sellerService->create($data);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->validator->errors()->first(),
            ], 400);
        } catch (SellerAlreadyExistsException $e) {
            return response()->json([
                'status' => 'Error',
                'message' =>$e->getMessage()
            ], 400);
        } catch (JWTException | Throwable $e) {
            return response()->json([
                'status' => 'Error',
                'message' => /*'Seller create error.'*/$e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'Success',
            'seller' => SellerResource::make($seller)
        ]);
    }


    /**
     * Update Seller
     * METHOD: put
     * URL: /seller/{id}/update
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $data = (new UpdateSellerRequestValidator)->attempt($request);
            $seller = $this->sellerService->update($data, $id);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->validator->errors()->first(),
            ], 400);
        } catch (SellerAlreadyExistsException $e) {
            return response()->json([
                'status' => 'Error',
                'message' =>$e->getMessage()
            ], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Seller not exist.'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Seller update error.'
            ], 500);
        }

        return response()->json([
            'status' => 'Success',
            'seller' => SellerResource::make($seller)
        ]);
    }


    /**
     * Delete Seller
     * METHOD: delete
     * URL: /seller/{id}/delete
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        try {
            $this->sellerService->delete($id);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Seller not exist.'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Seller delete error.'
            ], 500);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Seller successfully deleted.'
        ]);
    }

}
