<?php

namespace App\Http\Controllers;

use App\DataTables\playgroundsDataTable;
use App\Http\Controllers\AppBaseController;
use App\Repositories\playgroundsRepository;
use App\Models\pg_news;
use App\Models\pgtimes;
use DB;
use Flash;
use Illuminate\Http\Request;
use Response;
use Auth;
class playgroundsController extends AppBaseController {
	/** @var  playgroundsRepository */
	private $playgroundsRepository;

	public function __construct(playgroundsRepository $playgroundsRepo) {
		$this->playgroundsRepository = $playgroundsRepo;
	}

	/**
	 * Display a listing of the playgrounds.
	 *
	 * @param playgroundsDataTable $playgroundsDataTable
	 * @return Response
	 */
	public function index(playgroundsDataTable $playgroundsDataTable) {
		return $playgroundsDataTable->render('playgrounds.index');
	} 
	public function web(Request $request) {
		$name = $request->pg_name;
		$address = $request->address;
		$playgrounds = DB::table('playgrounds')
		 	->where('deleted_at',null)
			->when($name, function ($query) use ($name) {
                    	return $query->where('pg_name','LIKE','%'.$name.'%');
                		})
			->when($address, function ($query) use ($address) {
                    	return $query->where('address','LIKE','%'.$address.'%');
                		})
			->paginate(6);

		if (empty($playgrounds)) {
			Flash::error('Playgrounds not found');

			return redirect('/palyground');
		}

		$asset = function( $img ){
			if ( empty($img) ){
	//	return asset('web_asset/images/court_new.jpg');
	return asset('web_asset/images/mostreserved-bg.jpg');	
	return asset($img);
			}else {
				return asset('web_asset/images/$playgrounds->image');
			}
		
		};

		return view('playground', compact('playgrounds', 'asset'))->with('i', ($request->input('page', 1) - 1) * 6);
	}
	/**
	 *
	 * show specific playground
	 */
	public function display($id) {

		$playgrounds = $this->playgroundsRepository->findWithoutFail($id);

		// debug($playgrounds);die;
		$images  = DB::table('pgimages')->where('pg_id', $id)->get();
		$pg_imgs = [];
		foreach ($images as $image) {
			$pg_imgs[] = $image->image;
		}

		if (empty($playgrounds)) {
			Flash::error('Playgrounds not found');

			return redirect(route('details'));
		}
		$playgrounds->pg_images = $pg_imgs;

        $current_datetime = date("Y-m-d H:i");
		$dates = DB::table('pgtimes')->where('pg_id', '=', "$id")
			->where('booked', '=', 0)
			->where('parent_id', '!=', 0)
			->where('deleted_at', '=', null)
			->where('from_datetime', '>=', $current_datetime)
			->select(DB::raw('DATE(from_datetime) as date'), DB::raw('count(*) as views'))
			->groupBy('date')
			->get()->toarray();
		

		$bookeddates = DB::table('pgtimes')->where('pg_id', '=', "$id")->where('booked', '=', 1)->where('parent_id', '!=', 0)
			->where('deleted_at', '=', null)->where('from_datetime', '>=', $current_datetime)
			->select(DB::raw('DATE(from_datetime) as date'), DB::raw('count(*) as views'))
			->groupBy('date')
			->get()->toarray();
		$i = 0;
		foreach ($dates as $date) {
			$data['times'][$i]['date']        = $date->date;
			$data['times'][$i]['times']['am'] = pgtimes::where('pg_id', '=', "$id")

			// ->whereDate('from_datetime','>=',$current_datetime)
				->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d %H:%i') >= '$current_datetime'")
				->where('booked', '=', 0)
				->havingRaw("DATE_FORMAT(`from_datetime`,'%p') = 'am'")
			// ->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d') = '$date->date'")
				->whereDate('from_datetime', '=', $date->date)
				->get()
				->toarray();

			$data['times'][$i]['times']['pm'] = pgtimes::where('pg_id', '=', "$id")
				->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d %H:%i') >= '$current_datetime'")
			// ->whereDate('from_datetime','>=',$current_datetime)
				->where('booked', '=', 0)
				->havingRaw("DATE_FORMAT(`from_datetime`,'%p') = 'pm'")
			// ->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d') = '$date->date'")
				->whereDate('from_datetime', '=', $date->date)
				->get()
				->toarray();
			$i++;
		}
        $news = pg_news::where('pg_id','=',"$id")->get();

		if (!empty($times_am)) {
			$data['times']['am'] = $times_am->toarray();
		}

		if (!empty($times_pm)) {
			$data['times']['pm'] = $times_pm->toarray();
		}

		if (!empty($news)) {
			$data['news'] = $news->toarray();
		}

		$times_msg = '';
		if (empty($data['times']) and empty($bookeddates)) {
			$times_msg = "No times for this playground yet ,";
		} elseif (empty($data['times']) and !empty($bookeddates)) {
			$times_msg = 'sorry , all times for this playground were booked';
		}

		$data['info']['ground_type']        = $pginfo['ground_type'];
		$data['info']['light_available']    = $pginfo['light_available'];
		$data['info']['football_available'] = $pginfo['football_available'];
		$data['info']['pg_numberoffields']  = $pginfo['pg_numberoffields'];
		$data['info']['times_msg']          = $times_msg;
        $playgrounds->pgtimes = $data;
        // debug($playgrounds);die;
        // debug($playgrounds);die;
		return view('details')->with('playgrounds', $playgrounds);

	}

	/**
	 * Show the form for creating a new playgrounds.
	 *
	 * @return Response
	 */
	public function create() {
		return view('playgrounds.create');
	}

	/**
	 * Store a newly created playgrounds in storage.
	 *
	 * @param CreateplaygroundsRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateplaygroundsRequest $request) {
		$input = $request->all();

		$playgrounds = $this->playgroundsRepository->create($input);

		Flash::success('Playgrounds saved successfully.');

		return redirect(route('playgrounds.index'));
	}

	/**
	 * Display the specified playgrounds.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id) {
		$playgrounds = $this->playgroundsRepository->findWithoutFail($id);

		if (empty($playgrounds)) {
			Flash::error('Playgrounds not found');

			return redirect(route('playgrounds.index'));
		}

		return view('playgrounds.show')->with('playgrounds', $playgrounds);
	}

	/**
	 * Show the form for editing the specified playgrounds.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id) {
		$playgrounds = $this->playgroundsRepository->findWithoutFail($id);

		if (empty($playgrounds)) {
			Flash::error('Playgrounds not found');

			return redirect(route('playgrounds.index'));
		}

		return view('playgrounds.edit')->with('playgrounds', $playgrounds);
	}

	/**
	 * Update the specified playgrounds in storage.
	 *
	 * @param  int              $id
	 * @param UpdateplaygroundsRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateplaygroundsRequest $request) {
		$playgrounds = $this->playgroundsRepository->findWithoutFail($id);

		if (empty($playgrounds)) {
			Flash::error('Playgrounds not found');

			return redirect(route('playgrounds.index'));
		}

		$playgrounds = $this->playgroundsRepository->update($request->all(), $id);

		Flash::success('Playgrounds updated successfully.');

		return redirect(route('playgrounds.index'));
	}

	/**
	 * Remove the specified playgrounds from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id) {
		$playgrounds = $this->playgroundsRepository->findWithoutFail($id);

		if (empty($playgrounds)) {
			Flash::error('Playgrounds not found');

			return redirect(route('playgrounds.index'));
		}

		$this->playgroundsRepository->delete($id);

		Flash::success('Playgrounds deleted successfully.');

		return redirect(route('playgrounds.index'));
	}

	/**
	 * Remove the specified playgrounds from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function bookTime(Request $request) {
		if($request->ajax()){
        		if(! $user = Auth::user()){
        			return $this->sendError('User not logged in.');
        		}
        		if ( ! empty($request->time_ids) && is_array( $request->time_ids ) ){
        			foreach ($request->time_ids as $time_id) {
		        		$insertment =  DB::table('reservations')->insertGetId([
		        			'pg_id' => $request->pg_id,
		        			'time_id' => $time_id,
		        			'player_id' => Auth::user()->id
					]);
					if ( $insertment ){
						DB::table('pgtimes')
					            ->where('id', $request->time_id)
					            ->update(['booked' => 1]);
					}
        			}
        			return $this->sendResponse(Auth::user()->id, 'Reservation has been created successfully');
        		}

    		}
	}

}
