@extends("crudbooster::admin_template")
@section("content")
<link rel='stylesheet' href='<?php echo asset("vendor/crudbooster/assets/select2/dist/css/select2.min.css")?>'/>
<script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
<style>
    .select2-container--default .select2-selection--single {border-radius: 0px !important}
    .select2-container .select2-selection--single {height: 35px}
    select#table {
        height: 40px;
    }
</style>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><i class="fa fa-bell"></i> إرسال اشعار عام</strong>
    </div>
    <form class="form-horizontal" method="post" id="form" enctype="multipart/form-data" action="{{CRUDBooster::mainpath('add-save')}}">
        <div class="panel-body" style="padding:20px 0px 0px 0px">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body" id="parent-form-area">
                    <link rel="stylesheet" type="text/css" href="{{asset('vendor/crudbooster/assets/summernote/summernote.css')}}">
                    <script type="text/javascript" src="{{asset('vendor/crudbooster/assets/summernote/summernote.js')}}"></script>
                    

                    <div class="form-group header-group-0 " id="form-group-title" style="">
                        <label class="control-label col-sm-2">ارسل الاشعار الى<span class="text-danger" title="This field is required">*</span></label>
                        <div class="col-sm-10">
                            <select name="send_to" id="table" required class="select2 form-control">
                                <option value="all">الكل</option>
                                <option value="pg_owner">مالكين الملاعب</option>
                                <option value="player">جميع الاعبين</option>
                            </select>
                            <div class="text-danger"></div>
                            <p class="help-block"></p>
                        </div>
                    </div>

                    <div class="form-group header-group-0 " id="form-group-title" style="">
                        <label class="control-label col-sm-2">فرز بواسطة المدينة<span class="text-danger" title="This field is required">*</span></label>
                        <div class="col-sm-10">
                            <select name="city" id="table" required class="select2 form-control">
                                <option value="all">الكل</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name_ar }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger"></div>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="form-group header-group-0 " id="form-group-title" style="">
                        <label class="control-label col-sm-2">عنوان الإشعار <span class="text-danger" title="This field is required">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" title="Title" required="" maxlength="70" class="form-control" name="title" id="title" value="">
                            <div class="text-danger"></div>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="form-group" id="form-group-content" style="">
                        <label class="control-label col-sm-2">محتوى الإشعار<span class="text-danger" title="This field is required">*</span></label>
                        <div class="col-sm-10">
                            <textarea id="textarea_content" name="body" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>
                </div>
        </div>
        <div class="box-footer" style="background: #F5F5F5">
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-10">
                    <input type="submit" name="submit" value="إرسال" class="btn btn-success">
                </div>
            </div>
        </div>
                <!-- /.box-footer-->
    </form>
</div>

@endsection