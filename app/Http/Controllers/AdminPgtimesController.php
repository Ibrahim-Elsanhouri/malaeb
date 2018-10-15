<?php namespace App\Http\Controllers;

use App\Library\DatetimeHelper\DateTimeDiff;
use CRUDBooster;
use DB;
use Request;

// use App\Library\DateTimeDiff;

class AdminPgtimesController extends \crocodicstudio\crudbooster\controllers\CBController {
	public function cbInit() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$current_user = CRUDBooster::first('users',CRUDBooster::myId());				
		$this->user = $current_user->id_cms_privileges;
		$this->user_id = $current_user->id;
		$this->title_field         = "id";
		$this->limit               = "20";
		$this->orderby             = "id,desc";
		$this->global_privilege    = false;
		$this->button_table_action = true;
		$this->button_bulk_action  = true;
		$this->button_action_style = "button_icon";
		$this->button_add          = true;
		$this->button_edit         = false;
		$this->button_delete       = true;
		$this->button_detail       = false;
		$this->button_show         = FALSE;
		$this->button_filter       = true;
		$this->button_import       = false;
		$this->button_export       = false;
		$this->table               = "pgtimes";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"أسم الملعب","name"=>"pg_id","join"=>"playgrounds,pg_name"];
			$this->col[] = ["label"=>"التوقيت","name"=>"datetime_range"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'أسم الملعب','name'=>'pg_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'playgrounds,pg_name'];
			$this->form[] = ['label'=>'التوقيت','name'=>'datetime_range','type'=>'datetime','validation'=>'required','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'أسم الملعب','name'=>'pg_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'playgrounds,pg_name'];
			//$this->form[] = ['label'=>'التوقيت','name'=>'datetime_range','type'=>'datetime','validation'=>'required','width'=>'col-sm-10'];
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
		$this->index_button[] = ["label"=>"المواعيد المحجوزة","icon"=>"fa fa-clock-o","url"=>CRUDBooster::mainpath('?reserved')];
		$this->index_button[] = ["label"=>"المواعيد الغير محجوزة","icon"=>"fa fa-clock-o","url"=>CRUDBooster::mainpath('?non-reserved')];
		
		if ( isset($_GET['date']) ):
			$this->index_button[] = ["label"=>"المواعيد بتاريخ ".$_GET['date'],"icon"=>"fa fa-clock-o","url"=>CRUDBooster::mainpath('?reserved')];
		else:
			$this->index_button[] = ["label"=>"المواعيد بتاريخ","icon"=>"fa fa-clock-o","url"=>CRUDBooster::mainpath('?reserved')];
		endif;

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

	        $('#datetime_range').daterangepicker({
		        timePicker: true,
		        timePickerIncrement: 30,
		        'format':'DD-MM-YYYY h:mmA'
    			});
    			$('.datetimepicker').removeAttr('readonly');
    			    $(document).on('click', 'a[data-name=delete]', function(event) {
			    	if ($('input:checkbox:checked').length <= 3)
					{
					sweetAlert('خطأ', 'لا يوجد محدد.', 'error');
					}
			    });

			$('#almoaaayd-btarykh').click(function(event) {
				event.preventDefault();
				$('#choose-date').modal()
				$('.date-open').datepicker({
				    language: 'ar',
				    format: 'yyyy-mm-dd',
				    startDate: '01-01-1950',
				    rtl: true
				});
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
		$this->post_index_html = '<!-- Modal -->
<div class="modal fade" id="choose-date" role="dialog">
<form method="get" action="'.CRUDBooster::mainpath('?ondate').'" >
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">اختر التاريخ</h4>
            </div>
            <div class="modal-body">
	            <div class="form-group">
		            <div class="input-group date" id="datetimepicker7">
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		                <input type="text" name="date" class="date-open form-control">
		            </div>
		       </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default" >تصفية</button>
            </div>
        </div>
    </div>
   </form>
</div>';

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
		$this->style_css = null;

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
		$query->where('parent_id', '!=', 0);

		/**
		 * Date filter
		 */
		if ( isset( $_GET['date'] ) ){
			$query->whereBetween('from_datetime', [$_GET['date'].' 00:00:00', $_GET['date'].' 23:59:00']);
		}

		/**
		 * Reserved times filter
		 */
		if ( isset( $_GET['reserved'] ) ){
			$query->where('booked', 1);
		}

		/**
		 * Not Reserved times filter
		 */
		if ( isset( $_GET['non-reserved'] ) ){
			$query->where('booked', 0);
		}


		/**
		 * PG Owners
		 */
		$current_user = CRUDBooster::first('users',CRUDBooster::myId());
	       if ($current_user->id_cms_privileges == 3 )
	       	$query->where('user_id', $current_user->id);
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
		$datetime_range = $postdata['datetime_range'];
		$range          = explode(' - ', $datetime_range);
		$range          = array(
			'from' => strtotime($range[0]),
			'to'   => strtotime($range[1]),
		);

		$pgtime = DB::table('pgtimes')
			->where('parent_id', '!=', 0)
			->where('pg_id', '=', $postdata['pg_id'])
			->where('deleted_at', '=', null)
			->whereBetween('from_datetime', [date('Y-m-d H:i', $range['from']), date('Y-m-d H:i', $range['to'])])
			->first();
		if ($pgtime) {
			CRUDBooster::redirect(Request::server('HTTP_REFERER'), trans("crudbooster.alert_error_dublicat_pgtime"), 'error');
		}

		$postdata['datetime_range'] = serialize($range);
	}

	/*
		        | ----------------------------------------------------------------------
		        | Hook for execute command after add public static function called
		        | ----------------------------------------------------------------------
		        | @id = last insert id
		        |
	*/
	public function hook_after_add($id) {

		$pgtime = DB::table('pgtimes')->where('id', $id)->first();

		$datetime_range = unserialize($pgtime->datetime_range);
		$diff_hours     = DateTimeDiff::diffHours($datetime_range);
		$diff_days      = DateTimeDiff::diffDays($datetime_range);

		$date = date('Y-m-d H:i', $datetime_range['from']);
		$date = date('Y-m-d H:i', strtotime($date . ' +0 day'));

		for ($i = 0; $i < $diff_days + 1; $i++) {
			$added_days = ($i == 0) ? 0 : 1;
			$date       = date('Y-m-d H:i', strtotime($date . ' +' . $added_days . ' day'));

			for ($iH = 0; $iH < $diff_hours; $iH++) {
				$added_hours = ($iH == 0) ? 0 : 1;
				$time        = ($iH == 0) ? $date : $time;
				$time        = date('Y-m-d H:i', strtotime($time . ' +' . $added_hours . ' hour'));
				$to_time     = date('Y-m-d H:i', strtotime($time . ' +1 hour'));
				if ($time == 0) {
					continue;
				}

				DB::table('pgtimes')->insert([
					['pg_id'        => $pgtime->pg_id,
						'time'          => date('g', strtotime($time)) . ' - ' . date('g', strtotime($to_time)),
						'from_datetime' => $time,
						'to_datetime'   => $to_time,
						'booked'        => $pgtime->booked,
						'parent_id'     => $pgtime->id],
				]);

			}
		}
		DB::table('pgtimes')->where('parent_id', '=', 0)->delete();

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

	public function rangeString($row) {
		$pgtime = DB::table('pgtimes')->where('id', $row->id)->first();
		if (empty($pgtime->from_datetime)) {
			return 0;
		}

		$date1 = date('m/d/Y h:i a', strtotime($pgtime->from_datetime));

		$date2 = date('m/d/Y h:i a', strtotime($pgtime->to_datetime));
		$col   = $date1 . ' - ' . $date2;
		return $col;
		// $diff = $date2->diff($date1);
		// $days = $diff->days;
		// return  $days;

	}

	public function getDetail($id) {
		//Create an Auth
		if (!CRUDBooster::isRead() && $this->global_privilege == FALSE || $this->button_edit == FALSE) {
			CRUDBooster::redirect(CRUDBooster::adminPath(), trans("crudbooster.denied_access"));
		}
		$pgtime = DB::table('pgtimes')->where('id', $id)->first();

		$data               = [];
		$data['page_title'] = 'تفاصيل التوقيت';
		$data['row']        = $pgtime;
		$data['pgname'] = DB::table('playgrounds')->select('pg_name')->where('id', $pgtime->pg_id)->first()->pg_name;
		$data['time'] = ' من '.date("g:i a j/m/Y", strtotime($pgtime->from_datetime)) . ' الى '.date("g:i a j/m/Y", strtotime($pgtime->to_datetime));
		$data['command'] = 'detail';
		$data['booked'] = ( $pgtime->booked ) ? 'نعم' : 'لا';
		

		$this->cbView('crudbooster::times.form', $data);
	}

	//By the way, you can still create your own method in here... :)
}