@extends("crudbooster::admin_template")
@section("content")
<link rel='stylesheet' href='<?php echo asset("vendor/crudbooster/assets/adminlte/plugins/datatables/dataTables.bootstrap.css")?>'/>
<script src='<?php echo asset("vendor/crudbooster/assets/adminlte/plugins/datatables/jquery.dataTables.min.js")?>'></script>
<script src='<?php echo asset("http://malaeb.com/vendor/crudbooster/assets/adminlte/plugins/datatables/dataTables.bootstrap.min.js")?>'></script>
<style>
    
    select#table {
        height: 40px;
    }
    table th {
        background-color: #E0E0E0;
        font-weight: bold;
        text-align: right !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('#table_id').DataTable({
            "paging":   false,
            "searc":   false,
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "searching": false,
            "bInfo": false
            });
    } );
</script>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><i class="fa fa-bell"></i> جوائز اللاعب <a href="{{ $user_url }}" title=""> {{$user_name}} </a></strong>
    </div>
    <div class="panel-body">
        نقاط اللاعب الحالية هي : {{$user_points}}
    </div>

    <div class="box">
    <table id="table_id" class="table table-hover table-striped table-bordered">
        <thead>
            <tr class="active">
                <th width="auto"><a>اسم الجائزة</a></th>
                <th width="auto"><a>عدد نقاط الجائزة</a></th>
                <th width="auto"><a>أرسلت؟</a></th>
                <th width="auto"><a>تاريخ الإرسال</a></th>
                <th width="auto"><a>تاريخ الإستبدال</a></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th width="auto"><a>اسم الجائزة</a></th>
                <th width="auto"><a>عدد نقاط الجائزة</a></th>
                <th width="auto"><a>أرسلت؟</a></th>
                <th width="auto"><a>تاريخ الإرسال</a></th>
                <th width="auto"><a>تاريخ الإستبدال</a></th>
            </tr>
        </tfoot>
        <tbody>
            @foreach( $history as $prize )
            <tr>
                <td>{{ $prize->name }}</td>
                <td>{{ $prize->redeemed_points }}</td>
                <td>{!! $yes_no($prize->sent,$prize->id) !!}</td>
                <td>{{ $to_date($prize->sent_at) }}</td>
                <td>{{ $to_date($prize->created_at) }}</td>
            </tr>
            @endforeach


        </tbody>
    </table>
</div>
</div>

@endsection