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
        <strong><i class="fa fa-download"></i> معالج استيراد البيانات</strong>
    </div>
    <form class="form-horizontal" method="post" id="form" enctype="multipart/form-data" action="{{CRUDBooster::mainpath('add-save')}}">
        <div class="panel-body" style="padding:20px 0px 0px 0px">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body" id="parent-form-area">
                    <link rel="stylesheet" type="text/css" href="{{asset('vendor/crudbooster/assets/summernote/summernote.css')}}">
                    <script type="text/javascript" src="{{asset('vendor/crudbooster/assets/summernote/summernote.js')}}"></script>
                    <div>
                        <a href="{{CRUDBooster::mainpath('get-xlsx')}}" title="" class="btn btn-info">تحميل نموذج استيراد الملاعب</a>
                    </div>
                    <br>

                    <div class="form-group header-group-0 " id="form-group-title" style="">
                        <label class="control-label col-sm-2">رفع الملف<span class="text-danger" title="This field is required">*</span></label>
                        <div class="col-sm-10">
                            <input type="file" name="userfile" class="form-control" required="">
                            <div class="text-danger"></div>
                            <div class="help-block">الصيغ المدعومة : XLS, XLSX, CSV</div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="box-footer" style="background: #F5F5F5">
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-10">
                    <input type="submit" name="submit" value="رفع وإستيراد" class="btn btn-success">
                </div>
            </div>
        </div>
        <div class="panel-body" style="padding: 20px 24px 0px 0px;">
            <h2> ملاحظات هامة في الملف</h2>        
            <h4><strong>Usermobile:</strong> يجب ان يكون المستخدم موجود فعلاً.</h4>
            <h4><strong>City:</strong>أختيار من القائمة المنسدلة المخصصة.</h4>
            <h4><strong>Light available:</strong>أختيار من القائمة المنسدلة المخصصة.</h4>
            <h4><strong>Football available:</strong>أختيار من القائمة المنسدلة المخصصة.</h4>
        </div>
                <!-- /.box-footer-->
    </form>
    

        
    </div>
</div>

@endsection