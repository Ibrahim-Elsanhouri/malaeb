<?php 
//Loading Assets
$asset_already = [];
foreach($forms as $form) {
	$type = @$form['type']?:'text';

	if(in_array($type, $asset_already)) continue;

?>
	@if(file_exists(base_path('/vendor/crocodicstudio/crudbooster/src/views/default/type_components/'.$type.'/asset.blade.php')))
		@include('crudbooster::default.type_components.'.$type.'.asset')  
	@elseif(file_exists(resource_path('views/vendor/crudbooster/type_components/'.$type.'/asset.blade.php')))
		@include('vendor.crudbooster.type_components.'.$type.'.asset')  
	@endif
<?php
	$asset_already[] = $type;
} //end forms
?>

<style type="text/css">
#table-detail tr td:first-child {
	font-weight: bold;
	width: 25%;
}
</style>
<div class='table-responsive'>
	<table id="table-detail" class="table table-striped">
	    <tbody>
	        <tr>
	            <td>أسم الملعب</td>
	            <td>{{$pgname}}</td>
	        </tr>
	        <tr>
	            <td>التوقيت</td>
	            <td>{{$time}}</td>
	        </tr>
	        <tr>
	            <td>مؤكد</td>
	            <td><span class="badge">{{$confirmed}}</span> </td>
	        </tr>
	        <tr>
	            <td>تم الحضور</td>
	            <td><span class="badge">{{$confirmed}}</span> </td>
	        </tr>
	    </tbody>
	</table>
</div>