<?php
/**
 * File name: SliderController.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers;

use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\DB;
use App\Repositories\RestaurantRepository;

class ExtraMultiController extends Controller
{
    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;
    /**
     * @var RestaurantRepository
     */
    private $restaurantRepository;

    public function __construct(CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo, RestaurantRepository $restaurantRepo)
    {
        parent::__construct();
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->restaurantRepository = $restaurantRepo;
    }

    public function multi()
    {   
        $user_id = auth()->id();
        return view('extras.multi')->with("user_id", $user_id);
    }

    public function multipleextras(Request $request)
    {
        $extras = explode(',',$request->name);
 
        foreach($extras as $extra) {
            for ($i=0;$i<count($request['producto']);$i++)    
            {   
                $now = new \DateTime();
                DB::table('extras')->insert([
                    ['name' => $extra,
                    'description' => $request['description'],
                    'price' => $request['price'],
                    'food_id' => $request['producto'][$i],
                    'active' => $request['active'],
                    'extra_group_id' => $request['extra_group_id'],
                    'created_at' => $now->format('Y-m-d H:i:s'),
                    'updated_at' => $now->format('Y-m-d H:i:s')],
                ]);
                // echo "<br> Cerveza " . $i . ": " . $request['producto'][$i];    
            }
        }
        Flash::success('Extras multiples agregados exitosamente.');
        return redirect()->back();

    }
}