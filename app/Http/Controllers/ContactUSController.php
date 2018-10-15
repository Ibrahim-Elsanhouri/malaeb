<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\ContactUS;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
//use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator;

class ContactUSController extends Controller
{
/*
    public function index(Request $request){
     // return response()->json(array('msg'=> $msg), 200);

      //  $data = \Illuminate\Support\Facades\Request::all();
     //   return $data;

        $inputArray = $request->all();

        print_r ($inputArray['form']);

        // Set JSON Response array (status = success | error)
        $response = array ('status' => 'success',
            'msg' => 'Setting created successfully',);
        // Return JSON Response

        return response()->json($response);
    }
*/
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function contactUS()
    {
        return view('contactUS');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function contactUSPost(Request $request)
    {

         $this->validate($request, [
      'name'=>'required|Alpha|max:25|unique:contactus',
         'email' => 'required|email|unique:contactus',
          'message' => 'required']);



       ContactUS::create($request->all());
      //  return response()->json();
        return back()->with('success', 'شـكرا على رسالتك الينا .. !');
    }


    public function store(Request $request) {
        if ( Session::token() !== $request->input('_token') ) {
            return Response::json( array(
                'msg' => 'Erreur!'
            ) );
        }
        $response = array(
            'status' => 'success',
            'msg' => '!',
        );

         $rules = array(
            'name'=>'required|max:25|unique:contactus',
            'email' => 'required|email|unique:contactus',
            'message' => 'required'
                 );

        //$validator = Validator($request->all(), $rules);


        $validator = Validator::make($request->all(), [
            'name'=>'required|max:25',
            'email' => 'required|email|unique:contactus',
            'message' => 'required',
        ]);
        // $validator =    $this->validate($request->all(),$rules);
            return $this->validate();
        if ($validator->fails()) {
            if ($validator->errors->has('name'))
            {
                return 'Name is too long';
            }

                $response = array(
                'status' => 'error',
                'msg' => 'Inputs not correct',
                                 );

              // return Redirect::to('/#contact-us')
              //  ->with('subscribe_error','This email is already subscribed to us.')
               // ->withErrors($validator)->withInput();

            return \Response::json( $response );

                                 }else{

            $lead = new ContactUS();
            $lead->name       = Input::get('name');
            $lead->email        = Input::get('email');
            $lead->message        = Input::get('message');
            $lead->save();


            return \Response::json( $response );



        }
    }
}