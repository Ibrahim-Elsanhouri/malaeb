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
            "bInfo": false,
            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 5 ).footer() ).html(
                ''+pageTotal 
            );

             // Total over all pages
            totalP = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotalP = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 6 ).footer() ).html(
                ''+pageTotalP 
            );
        }
        });
    } );
</script>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><i class="fa fa-bell"></i> التقرير المالي</strong>
    </div>
    <div class="panel-body">
        <form action="{{CRUDBooster::mainpath('add-save')}}" method="get" accept-charset="utf-8">
            <div class="form-group header-group-0 " id="form-group-title" style="">
                <label class="control-label col-sm-2">النسبة المحسوبة</label>
                <div class="col-sm-3">
                    <input type="number" style="width: 60px;" name="payment_percent" value="{{$percent}}" min="0"> %
                </div>
                <div class="col-sm-6">
                    <input type="submit" name="submit" value="حفظ" class="btn btn-success">
                </div>

            </div>
            
        </form>
    </div>
    <div class="box">
    <table id="table_id" class="table table-hover table-striped table-bordered">
        <thead>
            <tr class="active">
                <th width="auto"><a>الملعب</a></th>
                <th width="auto"><a>اسم المالك</a></th>
                <th width="auto"><a>اسم المسوق</a></th>
                <th width="auto"><a>عدد مرات الحجز</a></th>
                <th width="auto"><a>سعر الساعة</a></th>
                <th width="auto"><a>مجموع ايراد الملعب</a></th>
                <th width="auto"><a>النسبة المحسوبة</a></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right">المجموع:</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
            @foreach( $reserved as $pgs )
            <tr>
                <td>{{ $pgs->pg_name }}</td>
                <td>{{ $pgs->name }}</td>
                <td>{{ $pgs->marketer }}</td>
                <td>{{ $pgs->reserved }}</td>
                <td>{{ $pgs->price }}</td>
                <td>{{ $pgs->total }}</td>
                <td>{{ ($pgs->total * $percent) / 100 }}</td>
            </tr>
            @endforeach


        </tbody>
    </table>
</div>
</div>

@endsection