@extends('adminlte::page')


@section('template_title')
    {!! trans('hyplast.editing-machine', ['name' => $machine->name]) !!}
@endsection

@section('template_linked_css')
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            {!! trans('hyplast.editing-machine', ['name' => $machine->name]) !!}
                            <div class="pull-right">
                                <a href="{{ route('machines') }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="top" title="{{ trans('hyplast.tooltips.back-machines') }}">
                                    <i class="fa fa-fw fa-reply-all" aria-hidden="true"></i>
                                    {!! trans('hyplast.buttons.back-to-machines') !!}
                                </a>
                                <a href="{{ url('/machines/' . $machine->id) }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="left" title="{{ trans('hyplast.tooltips.back-machines') }}">
                                    <i class="fa fa-fw fa-reply" aria-hidden="true"></i>
                                    {!! trans('hyplast.buttons.back-to-machine') !!}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        {!! Form::open(array('route' => ['machines.update', $machine->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation')) !!}
                        {!! csrf_field() !!}

                        <div class="form-group has-feedback row {{ $errors->has('code') ? ' has-error ' : '' }}">
                            {!! Form::label('code', trans('forms.create_machine_label_code'), array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('code', $machine->code, array('id' => 'code', 'class' => 'form-control', 'placeholder' => trans('forms.create_machine_ph_code'))) !!}
                                    <div class="input-group-append">
                                        <label class="input-group-text" for="name">
                                            <i class="fa fa-fw {{ trans('forms.create_machine_icon_code') }}" aria-hidden="true"></i>
                                        </label>
                                    </div>
                                </div>
                                @if($errors->has('code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                            {!! Form::label('name', trans('forms.create_machine_label_name'), array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('name', $machine->name, array('id' => 'name', 'class' => 'form-control', 'placeholder' => trans('forms.create_machine_ph_name'))) !!}
                                    <div class="input-group-append">
                                        <label class="input-group-text" for="name">
                                            <i class="fa fa-fw {{ trans('forms.create_machine_icon_name') }}" aria-hidden="true"></i>
                                        </label>
                                    </div>
                                </div>
                                @if($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group has-feedback row {{ $errors->has('location') ? ' has-error ' : '' }}">
                            {!! Form::label('location', trans('forms.create_machine_label_location'), array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    <select class="custom-select form-control" name="location" id="location">
                                        <option value="">{{ trans('forms.create_machine_ph_location') }}</option>
                                        @foreach ($locations as $location)
                                            <option value="{{$location->id}}" {{ $machine->location_id == $location->id ? 'selected="selected"' : '' }}>{{ $location->name }}</option>
                                        @endforeach
                                    </select>

                                    <div class="input-group-append">
                                        <label class="input-group-text" for="role">
                                            <i class="{{ trans('forms.create_machine_icon_location') }}" aria-hidden="true"></i>
                                        </label>
                                    </div>
                                </div>
                                @if ($errors->has('location'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('location') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group has-feedback row {{ $errors->has('category_machine') ? ' has-error ' : '' }}">

                            {!! Form::label('role', trans('forms.create_machine_label_category_machine'), array('class' => 'col-md-3 control-label')); !!}

                            <div class="col-md-9">
                                <div class="input-group">
                                    <select class="custom-select form-control" name="category_machine" id="category_machine">
                                        <option value="">{{ trans('forms.create_machine_ph_category_machine') }}</option>
                                        @if ($category_machines)
                                            @foreach($category_machines as $category)
                                                <option value="{{ $category->id }}" {{ $machine->category_machine_id == $category->id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="input-group-append">
                                        <label class="input-group-text" for="category_machine">
                                            <i class="{{ trans('forms.create_machine_icon_category_machine') }}" aria-hidden="true"></i>
                                        </label>
                                    </div>
                                </div>
                                @if ($errors->has('category_machine'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('category_machine') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container">

                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <div class="card">
                            <div class="card-header">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    {!! trans('hyplast.products', ['name' => $machine->name]) !!}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group has-feedback row {{ $errors->has('product') ? ' has-error ' : '' }}">
                                        {!! Form::label('product', trans('forms.create_extruder_label_selectproduct'), array('class' => 'col-md-3 control-label')); !!}
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <select class="custom-select form-control" name="product" id="product">
                                                    <option value="">{{ trans('forms.create_extruder_label_product') }}</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                                    @endforeach
                                                </select>

                                                <div class="input-group-append">
                                                    <label class="input-group-text" for="role">
                                                        <i class="{{ trans('forms.create_extruder_icon_product') }}" aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($errors->has('product'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('product') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn  btn-sm btn-primary btn-block" data-toggle="tooltip" onclick="attachConfirmation({{$machine->id}})" type="submit"> {!! trans('hyplast.buttons.create-new6') !!}</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive machine-table">
                                    <table class="table table-striped table-sm data-table">
                                        <thead class="thead">
                                            <tr>
                                                <th>{!! trans('hyplast.machines-table.id') !!}</th>
                                                <th>{!! trans('hyplast.machines-table.code') !!}</th>
                                                <th class="hidden-xs">{!! trans('hyplast.machines-table.name') !!}</th>
                                            </tr>
                                        </thead>
                                        @php
                                            $i = 1
                                        @endphp
                                        <tbody id="machine-table">
                                            @foreach($machine->products as $p)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{$p->code}}</td>
                                                    <td>{{$p->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                    <a class="btn btn-sm btn-warning btn-block" href="{{ URL::to('binnacles/' . $machine->id) }}" data-toggle="tooltip" title="Bitácora">
                                        {!! trans('hyplast.buttons.binnacle') !!}
                                    </a>
                                    <div class="col-6  offset-lg-8 col-sm-3 mb-3">
                                        {!! Form::button(trans('forms.save-changes'), array('class' => 'btn btn-success btn-block margin-bottom-1 mt-2 mb-2 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_machine__modal_text_confirm_title'), 'data-message' => trans('modals.edit_machine__modal_text_confirm_message'))) !!}
                                        @include('modals.modal-save')
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
<script type="text/javascript">
    function attachConfirmation(id) {
        swal({
            title: "Eliminar?",
            text: "Por Favor, asegurese y luego Confirme!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Si, Eliminar Registro!",
            cancelButtonText: "No, cancelar!",
            reverseButtons: !0
        }).then(function (e) {
            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var product =  $("#product :selected").value();
                $.ajax({
                    type: 'POST',
                    url: "{{url('/machines/products')}}/" + id + "/" + product,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        if (results.success === true) {
                            swal("Done!", results.message, "success");
                            window.location = "/machines/"+id+"/edit";
                        } else {
                            swal("Error!", results.message, "error");
                        }
                    }
                });
            } else {
                e.dismiss;
            }
        }, function (dismiss) {
            return false;
        })
    }
</script>
@endsection
