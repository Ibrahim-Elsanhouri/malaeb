<?php namespace App\Http\Controllers;

use CRUDBooster;
use DB;

class AdminReservationsController extends \crocodicstudio\crudbooster\controllers\CBController {

	public function cbInit() {

		$current_user = CRUDBooster::first('users',CRUDBooster::myId());				
		$this->user = $current_user->id_cms_privileges;
		$this->user_id = $current_user->id;

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = false;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "reservations";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"الملعب","name"=>"pg_id","join"=>"playgrounds,pg_name"];
			$this->col[] = ["label"=>"الوقت","name"=>"time_id","join"=>"pgtimes,time"];
			$this->col[] = ["label"=>"اللاعب","name"=>"player_id","join"=>"users,name"];
			$this->col[] = ["label"=>"مؤكد","name"=>"confirmed"];
			$this->col[] = ["label"=>"تم الحضور","name"=>"attendance"];
			$this->col[] = ["label"=>"تاريخ الحجز","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'الملعب','name'=>'pg_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'playgrounds,pg_name'];
			$this->form[] = ['label'=>'الوقت','name'=>'time_id','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'pgtimes,time','datatable_where'=>'pgtimes.deleted_at = null','parent_select'=>'pg_id'];
			$this->form[] = ['label'=>'اللاعب','name'=>'player_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'users,name','datatable_where'=>'users.type=\'player\''];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'الملعب','name'=>'pg_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'playgrounds,pg_name'];
			//$this->form[] = ['label'=>'الوقت','name'=>'time_id','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'pgtimes,time','datatable_where'=>'pgtimes.deleted_at = null','parent_select'=>'pg_id'];
			//$this->form[] = ['label'=>'اللاعب','name'=>'player_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'users,name','datatable_where'=>'users.type=\'player\''];
			# OLD END FORM

			/*
			        | ----------------------------------------------------------------------
			        | Sub Module
			        | ----------------------------------------------------------------------
					| @label          = Label of action
					| @path           = Path of sub module
					| @foreign_key 	  = foreign key of sub table/module
					| @button_color   = Bootstrap Class (primary,success,warning,danger)
					| @button_icon    = Font Awesome Class
					| @parent_columns = Sparate with comma, e.g : name,created_at
			        |
		*/
		$this->sub_module = array();

		/*
			        | ----------------------------------------------------------------------
			        | Add More Action Button / Menu
			        | ----------------------------------------------------------------------
			        | @label       = Label of action
			        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
			        | @icon        = Font awesome class icon. e.g : fa fa-bars
			        | @color 	   = Default is primary. (primary, warning, succecss, info)
			        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
			        |
		*/
		$this->addaction = array();

		/*
			        | ----------------------------------------------------------------------
			        | Add More Button Selected
			        | ----------------------------------------------------------------------
			        | @label       = Label of action
			        | @icon 	   = Icon from fontawesome
			        | @name 	   = Name of button
			        | Then about the action, you should code at actionButtonSelected method
			        |
		*/
		$this->button_selected = array();

		/*
			        | ----------------------------------------------------------------------
			        | Add alert message to this module at overheader
			        | ----------------------------------------------------------------------
			        | @message = Text of message
			        | @type    = warning,success,danger,info
			        |
		*/
		$this->alert = array();

		/*
			        | ----------------------------------------------------------------------
			        | Add more button to header button
			        | ----------------------------------------------------------------------
			        | @label = Name of button
			        | @url   = URL Target
			        | @icon  = Icon from Awesome.
			        |
		*/
		$this->index_button = array();

		/*
			        | ----------------------------------------------------------------------
			        | Customize Table Row Color
			        | ----------------------------------------------------------------------
			        | @condition = If condition. You may use field alias. E.g : [id] == 1
			        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
			        |
		*/
		$this->table_row_color = array();

		/*
			        | ----------------------------------------------------------------------
			        | You may use this bellow array to add statistic at dashboard
			        | ----------------------------------------------------------------------
			        | @label, @count, @icon, @color
			        |
		*/
		$this->index_statistic = array();

		/*
			        | ----------------------------------------------------------------------
			        | Add javascript at body
			        | ----------------------------------------------------------------------
			        | javascript code in the variable
			        | $this->script_js = "function() { ... }";
			        |
		*/
		$this->script_js = "
			$(function() {

				$(document).on('click', 'a[data-name=delete]', function(event) {
			    	if ($('input:checkbox:checked').length <= 5)
					{
					sweetAlert('خطأ', 'لا يوجد محدد.', 'error');
					}
			    });
			});

			";

		/*
			        | ----------------------------------------------------------------------
			        | Include HTML Code before index table
			        | ----------------------------------------------------------------------
			        | html code to display it before index table
			        | $this->pre_index_html = "<p>test</p>";
			        |
		*/
		$this->pre_index_html = null;

		/*
			        | ----------------------------------------------------------------------
			        | Include HTML Code after index table
			        | ----------------------------------------------------------------------
			        | html code to display it after index table
			        | $this->post_index_html = "<p>test</p>";
			        |
		*/
		$this->post_index_html = null;

		/*
			        | ----------------------------------------------------------------------
			        | Include Javascript File
			        | ----------------------------------------------------------------------
			        | URL of your javascript each array
			        | $this->load_js[] = asset("myfile.js");
			        |
		*/
		$this->load_js = array();

		/*
			        | ----------------------------------------------------------------------
			        | Add css style at body
			        | ----------------------------------------------------------------------
			        | css code in the variable
			        | $this->style_css = ".style{....}";
			        |
		*/
		$this->style_css = NULL;

		/*
			        | ----------------------------------------------------------------------
			        | Include css File
			        | ----------------------------------------------------------------------
			        | URL of your css each array
			        | $this->load_css[] = asset("myfile.css");
			        |
		*/
		$this->load_css = array();

	}

	/*
		    | ----------------------------------------------------------------------
		    | Hook for button selected
		    | ----------------------------------------------------------------------
		    | @id_selected = the id selected
		    | @button_name = the name of button
		    |
	*/
	public function actionButtonSelected($id_selected, $button_name) {
		//Your code here

	}

	/*
		    | ----------------------------------------------------------------------
		    | Hook for manipulate query of index result
		    | ----------------------------------------------------------------------
		    | @query = current sql query
		    |
	*/
	public function hook_query_index(&$query) {

		if( $this->user == 3 ){
			$query->where('playgrounds.user_id', $this->user_id);
		}
	}

	public function custom_condtion() {
		return 'pg_id';

	}



	/*
		    | ----------------------------------------------------------------------
		    | Hook for manipulate row of index table html
		    | ----------------------------------------------------------------------
		    |
	*/
	public function hook_row_index($column_index, &$column_value) {
		//Your code here
	}

	/*
		    | ----------------------------------------------------------------------
		    | Hook for manipulate data input before add data is execute
		    | ----------------------------------------------------------------------
		    | @arr
		    |
	*/
	public function hook_before_add(&$postdata) {
		//Your code here

	}

	/*
		    | ----------------------------------------------------------------------
		    | Hook for execute command after add public static function called
		    | ----------------------------------------------------------------------
		    | @id = last insert id
		    |
	*/
	public function hook_after_add($id) {
		//Your code here

	}

	/*
		    | ----------------------------------------------------------------------
		    | Hook for manipulate data input before update data is execute
		    | ----------------------------------------------------------------------
		    | @postdata = input post data
		    | @id       = current id
		    |
	*/
	public function hook_before_edit(&$postdata, $id) {
		//Your code here

	}

	/*
		    | ----------------------------------------------------------------------
		    | Hook for execute command after edit public static function called
		    | ----------------------------------------------------------------------
		    | @id       = current id
		    |
	*/
	public function hook_after_edit($id) {
		//Your code here

	}

	/*
		    | ----------------------------------------------------------------------
		    | Hook for execute command before delete public static function called
		    | ----------------------------------------------------------------------
		    | @id       = current id
		    |
	*/
	public function hook_before_delete($id) {
		//Your code here

		$pgtimeID = DB::table('reservations')->find($id);
		$pgtime   = DB::table('pgtimes')->find($pgtimeID->time_id);
		$pg       = DB::table('playgrounds')->find($pgtimeID->pg_id);

		$onTime = date('Y-d-m h:m a', strtotime($pgtime->from_datetime));

		$arrEn   = array('am', 'pm');
		$arrAr   = array('ص', 'م');
		$fcmData = array(
			'title' => 'تم الغاء حجز الملعب',
			'body'  => ' تم الغاء حجز ملعب "' . $pg->pg_name . '" بتاريخ ' . str_replace($arrEn, $arrAr, $onTime),
		);

		$optionBuiler = new OptionsBuilder();
		$optionBuiler->setTimeToLive(60 * 20);

		$notificationBuilder = new PayloadNotificationBuilder($fcmData['title']);
		$notificationBuilder->setBody($fcmData['body'])
			->setSound('default');

		$dataBuilder = new PayloadDataBuilder();
		if (isset($fcmData['data']) && !empty($data['data'])) {
			foreach ($fcmData['data'] as $key => $value) {
				$dataBuilder->addData([$key => $value]);
			}
		}

		$option       = $optionBuiler->build();
		$notification = $notificationBuilder->build();
		$data         = $dataBuilder->build();
		$tokens_array = DB::table('users')
			->select('device_tokens')
			->whereNotNull('device_tokens')
			->where('id', $pgtimeID->player_id)
			->pluck('device_tokens')->toArray();

		$tokens = @unserialize($tokens_array[0]);
		if ($tokens === false && $tokens_array[0] !== 'b:0;' || empty($tokens)) {
		} else {
			$downstreamResponse = FCM::sendTo($tokens, $option, $notification);
		}

	}

	/*
		    | ----------------------------------------------------------------------
		    | Hook for execute command after delete public static function called
		    | ----------------------------------------------------------------------
		    | @id       = current id
		    |
	*/
	public function hook_after_delete($id) {
		//Your code here

	}

	public function getDetail($id) {
		//Create an Auth
		if (!CRUDBooster::isRead() && $this->global_privilege == FALSE || $this->button_edit == FALSE) {
			CRUDBooster::redirect(CRUDBooster::adminPath(), trans("crudbooster.denied_access"));
		}
		$reservation = DB::table('reservations')->where('reservations.id', $id)
			->join('playgrounds', 'playgrounds.id', '=', 'reservations.pg_id')
			->join('pgtimes', 'pgtimes.id', '=', 'reservations.time_id')
			->join('users', 'users.id', '=', 'reservations.player_id')
			->first();
		$data                = [];
		$data['page_title']  = 'تفاصيل الحجز';
		$data['row']         = $reservation;
		$data['pgname']      = $reservation->pg_name;
		$data['time']        = ' من ' . date("g:ia j/m/Y", strtotime($reservation->from_datetime)) . ' الى ' . date("g:ia j/m/Y", strtotime($reservation->to_datetime));
		$data['player']      = $reservation->name;
		$data['ground_type'] = $reservation->ground_type;
		$data['confirmed']   = ($reservation->confirmed) ? 'نعم' : 'لا';
		$data['attendance']  = ($reservation->attendance) ? 'نعم' : 'لا';
		$data['created_at']  = date("g:i a j/m/Y", strtotime($reservation->created_at));
		$data['command']     = 'detail';
		$this->cbView('crudbooster::reservations.form', $data);
	}

	protected function get_date( $id ){
		$time = DB::table('pgtimes')->where('id',$id)->first();
		$date_f = date("j/m/Y", strtotime($time->from_datetime));
		$date_a = date("a", strtotime($time->from_datetime));
		return $date_f."/ ".$time->time." ".$date_a;
	}

	//By the way, you can still create your own method in here... :)

}