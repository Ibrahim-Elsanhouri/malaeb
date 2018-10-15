<?php namespace App\Http\Controllers;

use CRUDbooster;
use Session;
use Request;
use DB;

class AdminCmsUsersController extends \crocodicstudio\crudbooster\controllers\CBController {

	public function cbInit() {

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "name";
		// $this->limit = "";
		$this->orderby             = "";
		$this->global_privilege    = false;
		$this->button_table_action = true;
		$this->button_bulk_action  = true;
		$this->button_action_style = "button_icon";
		$this->button_add          = true;
		$this->button_edit         = true;
		$this->button_delete       = true;
		$this->button_detail       = true;
		$this->button_show         = false;
		$this->button_filter       = false;
		$this->button_import       = false;
		$this->button_export       = false;
		$this->table               = "users";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col   = [];
		$this->col[] = ["label" => "الإسم", "name" => "name"];
		$this->col[] = ["label" => "رقم الموبايل", "name" => "mobile"];
		$this->col[] = ["label" => "المدينة", "name" => "city", "callback_php" => '$this->cities($row->city)'];
		$this->col[] = ["label" => "مؤكد", "name" => "confirmed", "callback_php" => '($row->confirmed == 1 )?"<span class=\"label label-success\">نعم</span>":"<span class=\"label label-default\">لا</span>"'];
		$this->col[] = ["label" => "نوع المستخدم", "name" => "type", "callback_php" => '$this->user_type_trans($row->type)'];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form   = [];
		$this->form[] = ['label' => 'الإسم', 'name' => 'name', 'type' => 'text', 'validation' => 'required|string|min:3|max:150', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'رقم الموبايل', 'name' => 'mobile', 'type' => 'number', 'validation' => 'required|min:1|numeric|unique:users', 'width' => 'col-sm-10'];
		$current_user = CRUDBooster::first('users', CRUDBooster::myId());
		if ( $current_user->id_cms_privileges == 4 ) {
			$this->form[] = ['label' => 'نوع المستخدم', 'name' => 'type', 'type' => 'select', 'validation' => 'required', 'width' => 'col-sm-9', 'dataenum' => 'pg_owner|مالك ملعب'];
		}else{
			$this->form[] = ['label' => 'نوع المستخدم', 'name' => 'type', 'type' => 'select', 'validation' => 'required', 'width' => 'col-sm-9', 'dataenum' => 'player|لاعب;pg_owner|مالك ملعب;marketer|مسوق;admin|مدير'];

		}
		if ( $current_user->id_cms_privileges != 4 ) {
			$this->form[] = ['label' => 'المسوق', 'name' => 'marketer_id', 'type' => 'select2', 'validation' => 'integer', 'width' => 'col-sm-9', 'datatable' => 'users,name', 'datatable_where' => 'users.id_cms_privileges = 4'];
		}

		$this->form[] = ['label' => 'المدينة', 'name' => 'city', 'type' => 'select2', 'validation' => 'required|min:1', 'width' => 'col-sm-10', 'datatable' => 'cities,name_ar'];
		// $this->form[] = ['label' => 'إحداثيات العرض (long)', 'name' => 'map_lon', 'type' => 'number', 'validation' => 'min:1', 'width' => 'col-sm-10'];
		// $this->form[] = ['label' => 'احداثيات الطول (lat)', 'name' => 'map_lat', 'type' => 'number', 'validation' => 'min:1', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'البريد الإلكتروني', 'name' => 'email', 'type' => 'email', 'validation' => 'email', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'صورة العضو', 'name' => 'image', 'type' => 'upload', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'كلمة المرور', 'name' => 'password', 'type' => 'password', 'validation' => 'min:8|max:32', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'مؤكد', 'name' => 'confirmed', 'type' => 'select', 'width' => 'col-sm-9', 'dataenum' => '0|لا ;1|نعم', 'validation' => 'required'];
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:150','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
		//$this->form[] = ['label'=>'Mobile','name'=>'mobile','type'=>'number','validation'=>'required|min:1|numeric','width'=>'col-sm-10'];
		//$this->form[] = ['label'=>'City','name'=>'city','type'=>'text','validation'=>'required|min:1','width'=>'col-sm-10'];
		//$this->form[] = ['label'=>'Area','name'=>'area','type'=>'text','validation'=>'min:1','width'=>'col-sm-10'];
		//$this->form[] = ['label'=>'Team','name'=>'team','type'=>'select2','validation'=>'required|integer','width'=>'col-sm-10','datatable'=>'teams,name'];
		//$this->form[] = ['label'=>'Birth Date','name'=>'birth_date','type'=>'date','validation'=>'date|required','width'=>'col-sm-10'];
		//$this->form[] = ['label'=>'Map Lon','name'=>'map_lon','type'=>'text','validation'=>'min:1','width'=>'col-sm-10'];
		//$this->form[] = ['label'=>'Map Lat','name'=>'map_lat','type'=>'text','validation'=>'min:1','width'=>'col-sm-10'];
		//$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|min:1|email|unique:users','width'=>'col-sm-10'];
		//$this->form[] = ['label'=>'Password','name'=>'password','type'=>'password','validation'=>'min:3|max:32|required','width'=>'col-sm-10'];
		# OLD END FORM

		$this->sub_module = array();
		$this->script_js  = "
			$(function() {
				// $('#birth_date').datepicker('setEndDate', '0d');
				// $('#birth_date').datepicker({
				//     language: 'ar',
				//     format: 'yyyy-mm-dd',
				//     startDate: '01-01-1950',
				//     endDate: '0d',
				//     rtl: true
				// });
				if ( $('td:contains(نوع المستخدم)').next('td').text() != 'pg_owner' ){
					$('td:contains(المسوق)').parent('tr').remove();
				}
				if ( $('#type').val() == 'pg_owner' ){
					$('#form-group-marketer_id').css('display', 'block');
				}else{
					$('#form-group-marketer_id').css('display', 'none');
				}

				$(document).on('change', 'select#type', function(event) {
					if ( $('#type').val() == 'pg_owner' ){
						$('#form-group-marketer_id').css('display', 'block');
					}else{
						$('#form-group-marketer_id').css('display', 'none');
					}

				});

				$('.input_date').removeAttr('readonly');
				$(document).on('click', 'a[data-name=delete]', function(event) {
			    	if ($('input:checkbox:checked').length <= 6)
					{
					sweetAlert('خطأ', 'لا يوجد محدد.', 'error');
					}
			    });
			});

			";
// 		$this->style_css = '.datepicker {
//    direction: rtl;
// }
// .datepicker.dropdown-menu {
//    right: initial;
// }';

		// $this->sub_module[] = ['label'=>'Playgrounds','path'=>'playgrounds','parent_columns'=>'pg_name,address','foreign_key'=>'user_id','button_color'=>'primary','button_icon'=>'fa fa-bars'];
		// $this->index_button[] = ["label"=>"Owners","icon"=>"fa fa-user","url"=>CRUDBooster::mainpath('owners')];

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
		$this->addaction   = array();
		$this->addaction[] = ['label' => 'الجوائز', 'icon' => 'fa fa-gift', 'color' => 'success', 'url' => CRUDBooster::mainpath('prizes') . '/[id]','showIf'=>'[type] == "player"'];
		$this->addaction[] = ['label' => 'الجوائز', 'icon' => 'fa fa-gift', 'color' => 'success', 'url' => CRUDBooster::mainpath('sendnow') . '/[id]',"showIf"=>"[status] == 'belum lunas'"];

	}

	public function hook_before_add(&$postdata) {
		//Your code here
		$current_user = CRUDBooster::first('users', CRUDBooster::myId());

		if ( $current_user->id_cms_privileges == 4 ) {
			$postdata['marketer_id'] = $current_user->id;
		}
		if ($postdata['type'] == 'pg_owner') {
			$postdata['id_cms_privileges'] = 3;
		}
		if ($postdata['type'] == 'admin') {
			$postdata['id_cms_privileges'] = 2;
		}
		if ($postdata['type'] == 'marketer') {
			$postdata['id_cms_privileges'] = 4;
		}

		if ( empty ($postdata['password'])) {
			unset($postdata['password']);
		}

		return $postdata;
	}

	public function hook_query_index(&$query) {
		$current_user = CRUDBooster::first('users', CRUDBooster::myId());
		if ($current_user->id_cms_privileges == 4) {
			$query->where('users.marketer_id', $current_user->id);
		}
		$query->where('users.id_cms_privileges', '!=', 1);
	}

	public function getowners() {
		debug($this->query);die;
		$this->query = $this->query->where('users.type', '!=', 'development');
	}
	public function getProfile() {

		$this->button_addmore = FALSE;
		$this->button_cancel  = FALSE;
		$this->button_show    = FALSE;
		$this->button_add     = FALSE;
		$this->button_delete  = FALSE;
		$this->hide_form      = ['id_cms_privileges'];

		$data['page_title'] = trans("crudbooster.label_button_profile");
		$data['row']        = CRUDBooster::first('users', CRUDBooster::myId());

		$this->cbView('crudbooster::default.form', $data);
	}

	public function print() {

	}

	public function hook_row_index($column_index, &$column_value) {
		//Your code here
	}

	public function hook_after_delete($id) {
		DB::table('users')->where('id', $id)->delete();

	}

	public function user_type_trans($type) {
		switch ($type) {
		case 'admin':
			return 'مدير';
			break;
		case 'pg_owner':
			return 'مالك ملعب';
			break;
		case 'marketer':
			return 'مسوق';
			break;

		default:
			return 'لاعب';
			break;
		}
	}

	public function cities($id) {
		
		if ( ! is_numeric($id) )
			return '';

		$city = DB::table('cities')->where('id', $id)->first();

		if ( $city )
			return $city->name_ar;

		return '';

	}

	/**
	 * List user prizes  and check if sent or not with arability to send action
	 * @param  [type] $id User ID
	 */
	public function getprizes($id) {
		$current_user = CRUDBooster::first('users',CRUDBooster::myId());
		$data['page_title'] = 'تقرير جوائز الاعب';
		/**
		 * Prepare quires for payment report
		 * query for reserved ( attended reservation )
		 */
		$data['history'] = DB::table('user_prizes_history as u')
			->select('u.redeemed_points', 'u.sent', 'u.sent', 'u.sent_at', 'u.created_at', 'u.id', 'p.name')
			->where('user_id', $id)
			->join('prizes as p', 'p.id', '=', 'prize_id')
			->get()->toArray();
		$data['to_date'] = function( $date ){
			if ( empty($date) ) return '----';
			return date("Y/m/d h:ia", strtotime($date));
		};
		$data['yes_no'] = function( $value,$p_id ){
			if ( $value == 1 )
				return 'نعم';
			return "لا  <a href='".CRUDBooster::mainpath('sendnow')."/".$p_id."' class='btn btn-xs btn-success'> أرسل الآن </a>";
		};
		$user = DB::table('users')->where('id',$id)->first();
		$data['user_name'] = $user->name;
		$data['user_points'] = $user->points;
		$data['user_url'] = CRUDBooster::adminPath('users28/detail/'.$id);
		$this->cbView('admin.user-prizes', $data);
	}

	/**
	 * List user prizes  and check if sent or not with arability to send action
	 * @param  [type] $id User ID
	 */
	public function getsendnow($id) {
		// debug(date("Y-m-d h:i:s", strtotime(now()) ) );die;
		$prize_history = DB::table('user_prizes_history')->where('id',$id);
		if  ( ! $prize_history->first() ){
			CRUDBooster::redirect(CRUDBooster::adminPath('users28/'),'حدث خطأ ما حيث انه لا توجد عملية الاستبدال المطلوبة.','error');
		}
		$prize_history->update(['sent' => 1]);
		$prize_history->update(['sent_at' => DB::raw('CURRENT_TIMESTAMP(0)') ]);
		CRUDBooster::redirect(CRUDBooster::adminPath('users28/'),'تم تحديث معلومات الجائزة على انها ارسلت الان.','success');

	}
}