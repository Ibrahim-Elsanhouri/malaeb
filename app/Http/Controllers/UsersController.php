<?php

namespace App\Http\Controllers;

use App\DataTables\usersDataTable;
use App\Http\Requests;
use Illuminate\Contracts\Auth\Guard;
use App\Library\MobilySmsService;
use App\Http\Requests\CreateusersRequest;
use App\Http\Requests\UpdateusersRequest;
use App\Models\reservations;
use App\Models\teams;
use App\Repositories\usersRepository;
use App\User;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth;
use DB;
use Illuminate\Http\Request;



class UsersController extends AppBaseController
{
    /** @var  usersRepository */
    private $usersRepository;

    public function __construct(usersRepository $usersRepo)
    {
        $this->usersRepository = $usersRepo;
    }

    /**
     * Display a listing of the users.
     *
     * @param usersDataTable $usersDataTable
     * @return Response
     */
    public function index(usersDataTable $usersDataTable)
    {
        return $usersDataTable->render('users.index');
    }

    /**
     * Show the form for creating a new users.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created users in storage.
     *
     * @param CreateusersRequest $request
     *
     * @return Response
     */
    public function store(CreateusersRequest $request)
    {
        $input = $request->all();

        $users = $this->usersRepository->create($input);

        Flash::success('Users saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified users.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $users = $this->usersRepository->findWithoutFail($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('users', $users);
    }

    /**
     * Show the form for editing the specified users.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $users = $this->usersRepository->findWithoutFail($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('users', $users);
    }

    /**
     * Update the specified users in storage.
     *
     * @param  int              $id
     * @param UpdateusersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateusersRequest $request)
    {
        $users = $this->usersRepository->findWithoutFail($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        $users = $this->usersRepository->update($request->all(), $id);

        Flash::success('Users updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified users from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $users = $this->usersRepository->findWithoutFail($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        $this->usersRepository->delete($id);

        Flash::success('Users deleted successfully.');

        return redirect(route('users.index'));
    }


    public function profile($id){

       // $reser = reservations::where('player_id',$id)->get();

        $reser = reservations::where('player_id',$id)
            ->join('playgrounds', 'reservations.pg_id', '=', 'playgrounds.id')
            ->join('pgtimes', 'reservations.time_id', '=', 'pgtimes.id')
            ->select('reservations.id as ID','reservations.*', 'playgrounds.*', 'pgtimes.*')
            ->get();
        return view('profile', ['reser' => $reser]);
    }

    public function DeleteResevation($id){

       // $reser = reservations::where('player_id',$id)->get();

        $reser = reservations::find($id)->delete();
        $message = 'تم الغاء الحجز بنجاح';
        return redirect('profile/' . Auth::id() )->withMessage($message);
    }



    public function edit_web( $id , Request $request)
    {    

        $data['user'] = User::find($id);
        if (!$data['user']) {
            return redirect('/');
        }

        if ( ! $request->user() || $data['user']->id != $request->user()->id) 
            return redirect('/');
          if($request->isMethod('post')){
            $userData = User::find($id);

            If($request->hasFile('image')){

                $file = $request->file('image');
                $destinationPath = public_path(). '/uploads/users/';
                $filename = $file->getClientOriginalName();
                $request->file('image')->move($destinationPath, $filename);
                $userData->image='uploads/users/'.$filename;
              }
           $userData->name=$request->input('name');
            $userData->mobile=$request->input('mobile');
            $userData->city=$request->input('city');
              $userData->area=$request->input('area');
              $userData->team=$request->input('teams');
              $userData->birth_date=$request->input('birth_date');
            $userData->save();

            return redirect("user/".$userData->id);
        }else{
            $users = $this->usersRepository->findWithoutFail($id);

            if (empty($users)) {
                Flash::error('Users not found');

                return redirect(route('edit_profile'));
            }
            $teams= teams::all();
            $cities = DB::table('cities')->get();
            $cities_array = ['اختر المدينة'];
              foreach ($cities as $key => $value) {
                  $cities_array[$value->id] =  $value->name_ar;
             }
        
            return view('edit_profile', ['users' => $users ,'teams' =>$teams,'cities' =>$cities_array]);
        }


        //return view('edit_profile')->with('users', $users);
    }

    /**
     * Confirm user view
     *
     * @param  array  $data
     * @return User
     */
    public function confirmUser( Request $request )
    {
        if ( !isset( $request->id ) ){
            return view('auth.confirm', ['message' =>__('users.confirmNoID'), 'alertType' =>'danger'] );     
        }

        if ( isset( $request->id ) ){
            $user = User::find( $request->id );
            if ( $user->confirmed == 1 )
                return view('auth.confirm', ['message' =>__('users.confirmAlreadyConfirmed'), 'alertType' =>'info'] );     
        }
        if ($request->isMethod('post')) {
            $user = User::find( $request->id );
            if ( $user->confirmed == $request->code ){
                $user->confirmed = 1;
                $user->save();
                $this->guard()->login($user);
                return  redirect('/?type=success');
            }else{
                return view('auth.confirm', ['user_id' =>$request->id, 'error' =>__('users.confirmCodeError')] );
            }
        }
        if ( isset( $request->success ) )
            return view('auth.confirm', ['user_id' =>$request->id, 'success' =>1] );
            
       return view('auth.confirm', ['user_id' =>$request->id] );
    }

    protected function guard()
    {
        return Auth::guard();
    }


     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function passwordFrom(Request $request)
    {   
        if ( $request->isMethod('post') ){
            if ( isset($request->mobile) && 
                preg_match("/^(009665|9665|\\+9665|05|5)?(5|0|3|6|4|9|1|8|7)([0-9]{7})$/", $request->mobile)
                ) {
                $user = User::where( 'mobile',$request->mobile )->first();
                if ( $user ){
                    $new_password = 115599;
                    $password = \Hash::make($new_password);
                    $user->password = $password;
                    $user->save();
                   // $smsSent = MobilySmsService::send( $request->mobile, $new_password );
                   // 
                   return  redirect('/reset-password?success=1');
                }else{
                    return view('auth.resetPassword', ['message' => __('users.passwordUserNotfound'), 'alertType' => 'warning']);
                }
            }else{
                return view('auth.resetPassword', ['message' => __('users.passwordNotVaild'), 'alertType' => 'danger']);
            }
        }
        return view('auth.resetPassword');
    }

}
