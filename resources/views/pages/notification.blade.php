@extends("crudbooster::admin_template")
@section("content")
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><i class="fa fa-bell"></i> Send global notification</strong>
    </div>
    <form class="form-horizontal" method="post" id="form" enctype="multipart/form-data" action="{{CRUDBooster::mainpath('add-save')}}">
        <div class="panel-body" style="padding:20px 0px 0px 0px">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body" id="parent-form-area">
                    <link rel="stylesheet" type="text/css" href="{{asset('vendor/crudbooster/assets/summernote/summernote.css')}}">
                    <script type="text/javascript" src="{{asset('vendor/crudbooster/assets/summernote/summernote.js')}}"></script>
                    <div class="form-group header-group-0 " id="form-group-title" style="">
                        <label class="control-label col-sm-2">Title <span class="text-danger" title="This field is required">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" title="Title" required="" placeholder="You can only enter the letter only" maxlength="70" class="form-control" name="title" id="title" value="">
                            <div class="text-danger"></div>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="form-group" id="form-group-content" style="">
                        <label class="control-label col-sm-2">Body</label>
                        <div class="col-sm-10">
                            <textarea id="textarea_content" name="body" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                </div>
        </div>
        <div class="box-footer" style="background: #F5F5F5">
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-10">
                    <input type="submit" name="submit" value="Send" class="btn btn-success">
                </div>
            </div>
        </div>
                <!-- /.box-footer-->
    </form>
</div>

@endsection