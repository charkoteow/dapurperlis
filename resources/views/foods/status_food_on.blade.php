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
        <h1 class="m-0 text-dark">{{trans('lang.food_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.food_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('foods.index') !!}">{{trans('lang.food_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">Desactivar productos</li>
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
        @can('foods.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('foods.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.food_table')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link" href="{!! route('foods.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.food_create')}}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-times mr-2"></i>Desactivar Productos <span class="badge badge-danger animated shake">New</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{!! route('foods.statusoff') !!}"><i class="fa fa-check mr-2"></i>Activar Productos <span class="badge badge-danger animated shake">New</span></a>
        </li>
      </ul>
    </div>
    <div class="card-body">
        <div class="alert " role="alert" id="message_success" style="display: none;">
            <span id="message_display"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {!! Form::select('food[]', $foods, null, ['class' => 'duallistbox form-control', 'multiple'=>'multiple', 'style'=>'height: 290px;', 'id' => 'id_foods']) !!}
                    </div>
                </div>
                <p class="lead mb-0">¿Cómo funciona?</p>
                <div class="tab-content" id="custom-content-above-tabContent">
                  <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                    En esta pantalla podrás ver los productos activos o disponibles
                    <p></p>
                    <p><b>Recuadro izquierdo:</b> Si deseas desactivar/agotar uno o más productos, deberás seleccionarlo(s) y dar clic sobre el botón <b><i>Agregar</i></b> y posteriormente clic en guardar para aplicar los cambios.</p>
                    
                    <p></p>
                    <p><b>Recuadro derecho:</b> De este lado podrás ver los productos que seleccionaste y agregaste para posteriormente desactivarlos o ponerlos como agotados. Si te equivocaste al agregar un producto al recuadro derecho, puedes seleccionarlo y dar clic en el botón <b><i>Quitar</i></b> para eliminar la selección de ese producto.</p>
                </div>
              </div>
        <div class="form-group col-12 text-right">
            <button type="submit" class="btn btn-sm btn-{{setting('theme_color')}} action"><i class="fa fa-save"></i> Guardar</button>
            <a href="{!! route('foods.index') !!}" class="btn btn-sm btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
        </div>
        </div>
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

    $('.duallistbox').bootstrapDualListbox({
        infoTextFiltered:'<span class="badge badge-warning">Productos</span> {0} de {1}',
        infoText:'Mostrar todos {0}',
        infoTextEmpty:'Lista vacía',   
        filterTextClear:'mostrar todos',
        filterPlaceHolder:'Buscar productos...',
        moveSelectedLabel:'Mover seleccionados',
        moveAllLabel:'Mover todos',
        removeSelectedLabel:'Remover seleccionados',
        removeAllLabel:'Remover todos',
        nonSelectedListLabel: 'Productos activos',
        selectedListLabel: 'Seleccionados',
        preserveSelectionOnMove: 'moved',
        moveOnSelect: false,
        // sort by input order
        sortByInputOrder:false,
        // filter by selector's values, boolean
        filterOnValues:false,
        // boolean, allows user to unbind default event behaviour and run their own instead
        eventMoveOverride:false,
        eventRemoveAllOverride:false,  
        btnClass: 'btn btn-sm btn-outline-primary',
        // string, sets the text for the "Move" button                                           
        btnMoveText: 'Agregar',
        // string, sets the text for the "Remove" button                                                       
        btnRemoveText: 'Quitar',
        // string, sets the text for the "Move All" button
        btnMoveAllText: 'Agregar todos',
        // string, sets the text for the "Remove All" button
        btnRemoveAllText: 'Quitar todos'
    });

    $("body").on("click", ".action", function( event ) {
        $('.action').prop('disabled', true);
        $('.action').html("<i class='fa fa-refresh animated rotateIn'></i> Cargando...");

        var CSRF_TOKEN = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ route('foods.actionstatus') }}",
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                food: $("#id_foods").val(),
                action: 0,
            },
            dataType: 'JSON',
            success: function (response) {
                if (response.success == true) {
                    $('.action').html("<i class='fa fa-save'></i> Guardar");

                    $('#message_success').css('display', 'block');
                    $("#message_success").addClass("alert-success");
                    $("#message_display").html(response.message);
                    setTimeout(function() {
                        $("#message_success").removeClass("alert-success");
                        $("#message_success").fadeOut(1700);
                        location.reload();
                    },2100);
                } else {
                    $('.action').removeAttr("disabled");
                    $('.action').html("<i class='fa fa-save'></i> Guardar");

                    $('#message_success').css('display', 'block');
                    $("#message_display").html(response.message);
                    $("#message_success").addClass("alert-warning");
                    setTimeout(function() {
                        $("#message_display").html('');
                        $("#message_success").removeClass("alert-warning");
                        $("#message_success").fadeOut(2300);
                    },1100);
                }
                //console.log(response);
            }
        });
    });
</script>
@endpush