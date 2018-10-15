@extends('layouts.header')

@section('content')

    <div class="reserved" id="reserved">
        <div class="row">
            <br/>
        </div>
        <div class="container">


            <div class="panel panel-default">

                <div class="panel-heading" style="color: #42a104; background-color: #dfebeb;border-color: #ddd;"><h3> <span class="glyphicon glyphicon-edit" style="font-size: 19px;"></span>&nbsp; تعديل بيانات الملف الشخصـي </h3> </div>
                <div class="panel-body"  style="color: #42a104;background-color: #f7fbfb">



                 {{Form::open(array('url' => 'edit-profile/'.$users->id, 'class' => 'form-horizontal' , 'files' => true , 'method' => 'post','style'=>'margin-right: 18%;margin-left: 22%;margin-top: 25px'))}}

         <!--      <form style=" margin-right: 18%;margin-left: 22%;"   method="POST">-->

                               <div class="form-group">
                                <label for="name">الأسـم</label>
                                <input type="text" class="form-control" name="name" value="{{ $users->name }}" id="name" placeholder="الأسـم" required>
                               </div>

                            <div class="form-group">
                                <label for="mobile">رقـم الجوال</label>
                                <input type="text" class="form-control" name="mobile" value="{{ $users->mobile }}" id="mobile" placeholder="رقم الموبيل" required>
                            </div>

                        <div class="form-group">
                            <label for="city">المدينة</label>
                            {!! Form::select('city', $cities, $users->city, ['class' => 'form-control']) !!}
                        </div>

                       
                             <div class="form-group">
                                 @if($users->image !=null)

                                    <img src="{{asset('/'.$users->image.'')}}"  style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;     margin-bottom: 15px;">
                                 @else
                                     <img src="{{asset('uploads/users/iiii.png')}}"  style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;    margin-bottom: 15px;">

                                 @endif
                                 <label>الصورة الشخصية</label>
                                 <input type="file" name="image">


                              </div>

                             {{ Form::hidden('id', $users->id) }}
                             <div class="form-group">
                                 {{ Form::submit('حفظ التعديلات', array('class'=>'btn btn-large btn-primary btn-block'))}}
                              <!--   <input type="submit" class="form-control" name="edit" value="حفظ التعديلات " style="    color: #f5faf2;
                        background-color: #5dad29;
                        font-size: 20px;" > -->
                             </div>



                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection