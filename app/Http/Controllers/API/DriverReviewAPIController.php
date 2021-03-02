<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\CreateDriverReviewRequest;
use App\Models\DriverReview;
use App\Repositories\DriverReviewRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class DriverReviewController
 * @package App\Http\Controllers\API
 */

class DriverReviewAPIController extends Controller
{
    /** @var  DriverReviewRepository */
    private $driverReviewRepository;

    public function __construct(DriverReviewRepository $driverReviewRepo)
    {
        $this->driverReviewRepository = $driverReviewRepo;
    }

    /**
     * Display a listing of the DriverReview.
     * GET|HEAD /driverReviews
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->driverReviewRepository->pushCriteria(new RequestCriteria($request));
            $this->driverReviewRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            Flash::error($e->getMessage());
        }
        $driverReviews = $this->driverReviewRepository->all();

        return $this->sendResponse($driverReviews->toArray(), 'Driver Reviews retrieved successfully');
    }

    /**
     * Display the specified RestaurantReview.
     * GET|HEAD /restaurantReviews/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var DriverReview $driverReview */
        if (!empty($this->driverReviewRepository)) {
            $driverReview = $this->driverReviewRepository->findWithoutFail($id);
        }

        if (empty($driverReview)) {
            return $this->sendError('Driver Review not found');
        }

        return $this->sendResponse($driverReview->toArray(), 'Driver Review retrieved successfully');
    }

    /**
     * Store a newly created RestaurantReview in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $uniqueInput = $request->only("user_id","driver_id");
        $otherInput = $request->except("user_id","driver_id");
        try {
            $driverReview = $this->driverReviewRepository->updateOrCreate($uniqueInput,$otherInput);
        } catch (ValidatorException $e) {
            return $this->sendError('Restaurant Review not found');
        }

        return $this->sendResponse($driverReview->toArray(),__('lang.saved_successfully',['operator' => __('lang.restaurant_review')]));
    }
}
