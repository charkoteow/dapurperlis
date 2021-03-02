<?php
/**
 * File name: RestaurantReviewDataTable.php
 * Last modified: 2020.05.04 at 09:04:19
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Criteria\DriverReviews\DriverReviewsOfUserCriteria;
use App\Criteria\DriverReviews\OrderDriverReviewsOfUserCriteria;
use App\Models\CustomField;
use App\Models\DriverReview;
use App\Repositories\DriverReviewRepository;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

/**
 * Class RestaurantReviewDataTable
 * @package App\DataTables
 */
class DriverReviewDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */
    public static $customFields = [];

    /**
     * @var RestaurantReviewRepository
     */
    private $driverReviewRepo;

    private $myReviews;


    /**
     * RestaurantReviewDataTable constructor.
     * @param RestaurantReviewRepository $restaurantReviewRepo
     */
    public function __construct(DriverReviewRepository $driverReviewRepo)
    {
        $this->driverReviewRepo = $driverReviewRepo;
        $this->myReviews = $this->driverReviewRepo->getByCriteria(new DriverReviewsOfUserCriteria(auth()->id()))->pluck('id')->toArray();
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable = $dataTable
            ->editColumn('updated_at', function ($driver_review) {
                return getDateColumn($driver_review, 'updated_at');
            })->addColumn('action', function ($driver_review) {
                return view('driver_reviews.datatables_actions', ['id' => $driver_review->id, 'myReviews' => $this->myReviews])->render();
            })
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\RestaurantReview $model
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function query(DriverReview $model)
    {
        $this->driverReviewRepo->pushCriteria(new OrderDriverReviewsOfUserCriteria(auth()->id()));
        return  $model->newQuery()->with("user");

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true)
                ]
            ));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [
                'data' => 'review',
                'title' => trans('lang.driver_review_review'),

            ],
            [
                'data' => 'rate',
                'title' => trans('lang.driver_review_rate'),

            ],
            [
                'data' => 'user.name',
                'title' => trans('lang.driver_review_user_id'),

            ],
            [
                'data' => 'driver_id',
                'title' => trans('lang.driver_review_user_id'),

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.driver_review_updated_at'),
                'searchable' => false,
            ]
        ];

        $hasCustomField = in_array(DriverReview::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', DriverReview::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.drivert_review_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'driver_reviewsdatatable_' . time();
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }
}