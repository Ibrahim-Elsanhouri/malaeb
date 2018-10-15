<?php
namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use DB;

class PageController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function getPage($slug)
    {   
        debug($slug);die;
        $pages = DB::table('pages')->get();
        foreach ($pages as $key => $page) {

            if ( $page->slug == $id  ){
                return view('pages.'.$page->template);
            }
        }
        // return  redirect('/');
        
    }

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function terms()
    {

        // $terms = DB::table('pages')->where('slug','terms')->get();
        
        $terms = DB::table('pages')->where('id','8')->first();
        return view('pages.index', ['data'=>$terms]);
        // return view('pages.terms');
        
        
    }

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function agreement()
    {

        // $terms = DB::table('pages')->where('slug','terms')->get();
        
        $agreement = DB::table('pages')->where('id','7')->first();
        return view('pages.index', ['data'=>$agreement]);
        // return view('pages.terms');
        
        
    }


    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function privacy()
    {

        $privacy = DB::table('pages')->where('id','3')->first();
      
        return view('pages.index', ['data'=>$privacy]);
                
    }
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function about_us()
    {

        $about_us = DB::table('pages')->where('id','2')->first();
      
        return view('pages.index', ['data'=>$about_us]);
                
    }

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function offers()
    {
        $offers = DB::table('offers')
                   ->join('playgrounds', 'offers.pg_id', '=', 'playgrounds.id')
                   ->select('playgrounds.pg_name as name','offers.title as title','offers.details as details','offers.image as image','offers.url as url' ,'offers.created_at as created_at'   )
                   ->where('active',1)
                   ->get();
            return view('pages.offers', compact('offers'));
                
    }
}
