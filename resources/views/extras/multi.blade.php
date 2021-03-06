@extends('layouts.app')
@push('css_lib')
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
<!-- select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
{{--dropzone--}}
<link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.extra_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.extra_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('extras.index') !!}">{{trans('lang.extra_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.extra_create')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  @include('adminlte-templates::common.errors')
  <div class="clearfix"></div>
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        @can('extras.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('extras.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.extra_table')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link" href="{!! route('extras.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.extra_create')}}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.extra_create')}} (Múltiple) <span class="badge badge-danger animated shake">New</span></a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::open(['url' => ['extra/multiple_extras'], 'method' => 'post']) !!}
      <div class="row">
        <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
            <!-- Name Field -->                           
            <div class="form-group row ">
              {!! Form::label('name', trans("lang.extra_name"), ['class' => 'col-3 control-label text-right']) !!}
              <div class="col-9">
                  {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.extra_name_placeholder")]) !!}
                  <div class="form-text text-muted">
                      {{ trans("lang.extra_name_help") }}
                  </div>
              </div>
            </div>

            <!-- Extra Group Id Field -->
            <div class="form-group row ">
              {!! Form::label('extra_group_id', trans("lang.extra_extra_group_id"),['class' => 'col-3 control-label text-right']) !!}
              <div class="col-9">
                    <select name="extra_group_id" class="form-control select2">
                      <?php
                      $result = DB::table('extra_groups')
                      ->select(
                          'extra_groups.id',
                          'extra_groups.name',
                      )
                      ->get();
                      $myArray = json_decode($result, true);

                      foreach ($myArray as $extraGroups){
                          echo '<option value="'.$extraGroups['id'].'">'.htmlspecialchars($extraGroups['name']).'</option>';
                      }
                      ?>
                    </select>
                  <div class="form-text text-muted">
                    {{ trans("lang.extra_extra_group_id_help") }}
                  </div>
              </div>
            </div>
        </div>

        <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
          <!-- Price Field -->
          <div class="form-group row ">
              {!! Form::label('price', trans("lang.extra_price"), ['class' => 'col-3 control-label text-right']) !!}
              <div class="col-9">
                  {!! Form::number('price', null,  ['class' => 'form-control','step'=>"any",'placeholder'=>  trans("lang.extra_price_placeholder")]) !!}
                  <div class="form-text text-muted">
                      {{ trans("lang.extra_price_help") }}
                  </div>
              </div>
          </div>

          <!-- Product Field -->
          <div class="form-group row ">
              {!! Form::label('food_id', trans("lang.extra_food_id"),['class' => 'col-3 control-label text-right']) !!}
              <div class="col-9">
                <select class="form-control select2" multiple name="producto[]" style="height: 250px;">    
                      <?php
                          $resultFood = DB::table('foods')
                          ->select(
                              'foods.id',
                              'foods.name',
                          )
                          ->get();
                          $myArrayFood = json_decode($resultFood, true);

                          foreach ($myArrayFood as $foods){
                              echo '<option value="'.$foods['id'].'">'.htmlspecialchars($foods['name']).')</option>';
                          }
                      ?>
                  </select>
                  <div class="form-text text-muted">{{ trans("lang.extra_food_id_help") }}</div>
              </div>
          </div>

          <div class="form-group row ">
            {!! Form::label('active', 'Active?',['class' => 'col-3 control-label text-right']) !!}
            <div class="checkbox icheck">
                <label class="w-100 ml-2 form-check-inline">
                {!! Form::hidden('active', 0) !!}
                {!! Form::checkbox('active', 1, null) !!}
                <span class="ml-2">Activate extra in the app</span>
                </label>
            </div>
          </div>

        </div>
      </div>

      <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
        <!-- Description Field -->
        <div class="form-group row ">
          {!! Form::label('description', trans("lang.extra_description"), ['class' => 'col-3 control-label text-right']) !!}
          <div class="col-9">
            {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=> trans("lang.extra_description_placeholder")  ]) !!}
            <div class="form-text text-muted">{{ trans("lang.extra_description_help") }}</div>
          </div>
        </div>
      </div>

      <!-- Submit Field -->
      <div class="form-group col-12 text-right">
          <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.extra')}}</button>
          <a href="{!! route('extras.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
      </div>
    </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@include('layouts.media_modal')
@endsection
@push('scripts_lib')
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- select2 -->
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
{{--dropzone--}}
<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
</script>
@endpush