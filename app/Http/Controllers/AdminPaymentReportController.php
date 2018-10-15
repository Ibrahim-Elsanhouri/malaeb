<?php namespace App\Http\Controllers;

use Session;
	use Request;
	use DB;
	use CRUDBooster;;

class AdminPaymentReportController extends \crocodicstudio\crudbooster\controllers\CBController {

	public function cbInit() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field         = "title";
		$this->limit               = "20";
		$this->orderby             = "id,desc";
		$this->global_privilege    = false;
		$this->button_table_action = false;
		$this->button_bulk_action  = false;
		$this->button_action_style = "button_icon";
		$this->button_add          = false;
		$this->button_edit         = false;
		$this->button_delete       = false;
		$this->button_detail       = false;
		$this->button_show         = false;
		$this->button_filter       = false;
		$this->button_import       = false;
		$this->button_export       = false;
		$this->table               = "notifications";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col   = [];
		$this->col[] = ["label" => "Title", "name" => "title"];
		$this->col[] = ["label" => "Body", "name" => "body"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form   = [];
		$this->form[] = ['label' => 'Title', 'name' => 'title', 'type' => 'text', 'validation' => 'required|string|min:3|max:70', 'width' => 'col-sm-10', 'placeholder' => 'You can only enter the letter only'];
		$this->form[] = ['label' => 'Body', 'name' => 'body', 'type' => 'textarea', 'validation' => 'required|string|min:5|max:5000', 'width' => 'col-sm-10'];
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ["label"=>"Title","name"=>"title","type"=>"text","required"=>TRUE,"validation"=>"required|string|min:3|max:70","placeholder"=>"You can only enter the letter only"];
		//$this->form[] = ["label"=>"Body","name"=>"body","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
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
		$this->script_js = NULL;

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
		//Your code here

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

	public function getIndex() {

		$current_user = CRUDBooster::first('users',CRUDBooster::myId());				
		$this->user = $current_user->id_cms_privileges;
		$this->user_id = $current_user->id;
		$data['page_title'] = 'التقارير المالية';
		/**
		 * Prepare quires for payment report
		 * query for reserved ( attended reservation )
		 */
		$data['reserved'] = DB::table('playgrounds as p')
			->select('u.name','um.name as marketer', 'p.user_id', 'p.price', 'p.pg_name', 'p.id', DB::raw('COUNT(r.id) AS reserved'), DB::raw('p.price * COUNT(r.id)  AS total'))
			->leftJoin('reservations as r', 'p.id', '=', 'r.pg_id')
			->join('users as u', 'p.user_id', '=', 'u.id')
			->leftJoin('users as um', 'u.marketer_id', '=', 'um.id')
			->where('r.attendance', 1)
			->groupBy('p.id');
		if ( $this->user == 3 )
			$data['reserved'] = $data['reserved']->where('p.user_id', $this->user_id);
		
		
		if ( $this->user == 4 ){
			$data['reserved'] = $data['reserved']->where('u.marketer_id',$current_user->id );
		}
			
		$data['reserved'] = $data['reserved']->get()->toArray();
		$data['percent'] = DB::table('cms_settings')->where('name','payment_percent_value')->first()->content;
		$data['owner'] =  ( $this->user == 3 ) ? 1 : 0;
		// debug($data);die;
		$this->cbView('admin.payment-report', $data);
	}

	public function getAddSave() {
		if ( ! empty( Request::get('payment_percent') ) ){
			DB::table('cms_settings')->where('name','payment_percent_value')->update(['content' => Request::get('payment_percent')]);
			CRUDBooster::redirect(Request::server('HTTP_REFERER'),'تم الحفظ بنجاح.','success');
		}
	}


}