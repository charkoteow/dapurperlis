<?php
/**
 * File name: RestaurantReviewsOfUserCriteria.php
 * Last modified: 2020.05.04 at 09:04:18
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Criteria\DriverReviews;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class RestaurantReviewsOfUserCriteria.
 *
 * @package namespace App\Criteria\RestaurantReviews;
 */
class DriverReviewsOfUserCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    private $userId;

    /**
     * RestaurantReviewsOfUserCriteria constructor.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->select('driver_reviews.*');
    }
}
