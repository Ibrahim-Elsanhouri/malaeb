<?php

namespace App\Http\Controllers;

use App\Models\playgrounds;
use App\Models\reservations;
use App\Models\statistics;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends AppBaseController {



   public function contact_us( Request $request ){
    if($request->ajax()){
        $contact_us = new ContactUs;
          $contact_us->name = $request->name;
          $contact_us->email = $request->email;
          $contact_us->message = $request->message;
          $contact_us->save();
          
              return $this->sendResponse(1, 'Reservation has been created successfully');
        }
        

    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
      // public function __construct()
      // {
      //   $this->middleware('auth');
      // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
      // debug($users = Tracker::onlineUsers());die;
      // debug(Tracker::pageViews(60 * 24 * 30));die;
        $playgrounds = playgrounds::orderBy('id', 'desc')->take(6)->get();

        $play_ground_count = playgrounds::all()->count();

        $reserv = reservations::all()->count();

        $statistics = statistics::orderBy('id', 'desc')
            ->take(1)
            ->get();

        $lasr_reserv = reservations::all();

        $users = reservations::select(DB::raw('count(*) as reservations, pg_id'))
            ->groupBy('pg_id')
            ->orderBy('reservations', 'desc')
            ->take(3)
            ->get();

        $cities = DB::table('cities')->get();
        $cities_array = ['اختر المدينة'];
        foreach ($cities as $key => $value) {
          $cities_array[$value->name_ar] =  $value->name_ar;
        }

        for ($x = 0; $x < count($users); $x++) {
            $laster[] = playgrounds::where('id', $users[$x]->pg_id)->get();
        }


        $homeParams = [
            'playground' => $playgrounds,
            'play_ground_count' => $play_ground_count,
            'reserv' => $reserv,
            'statistics' => $statistics,
            'cities' => $cities_array,
            'laster' => $laster];
        if ( $request->type ){
            $homeParams['type'] = 1;
        }

        $homeParams['asset'] = function( $img ){
            if ( empty($img) )
              return asset('web_asset/images/court_new.jpg');
            return asset($img);
          };

        $homeParams['asset_latest'] = function( $img ){
            if ( empty($img) )
              return asset('web_asset/images/elmala3eb1.jpg');
            return asset($img);
          };

        return view('home', $homeParams);
    }



}
