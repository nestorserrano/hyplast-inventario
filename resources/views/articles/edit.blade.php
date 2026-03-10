@extends('adminlte::page')

@section('template_title')
    {!! trans('hyplast.editing-machine', ['name' => $product->name]) !!}
@endsection

@section('template_linked_css')
@endsection

@section('content')

<div class="col-lg-8 offset-lg-2">
    <div class="card card-info">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                {!! trans('hyplast.editing-machine', ['name' => $product->name]) !!}
                <div class="pull-right">
                    <a href="{{ route('products') }}" class="btn btn-success btn-sm float-right" data-toggle="tooltip" data-placement="top" title="{{ trans('hyplast.tooltips.back-machines') }}">
                        <i class="fa fa-fw fa-reply-all" aria-hidden="true"></i>
                        {!! trans('hyplast.buttons.back-to-machines') !!}
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(array('route' => ['products.update', $product->id], 'method' => 'PUT', 'role' => 'form', 'class' => 'needs-validation', 'enctype' => 'multipart/form-data')) !!}
            {!! csrf_field() !!}
            <div class="row align-items-center">
                <div class="col-sm-6 align-self-center">
                    <div class="form-group has-feedback row {{ $errors->has('code') ? ' has-error ' : '' }}">
                        {!! Form::label('code', trans('forms.create_product_label_code'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::text('code', $product->code, array('id' => 'code', 'class' => 'form-control', 'readonly' => 'true', 'placeholder' => trans('forms.create_product_ph_code'))) !!}
                                <div class="input-group-append">
                                    <label for="code" class="input-group-text">
                                        <i class="fa fa-fw {{ trans('forms.create_product_icon_code') }}" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('code'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 align-self-center">
                    <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                        {!! Form::label('name', trans('forms.create_product_label_name'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::text('name', $product->name, array('id' => 'name', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_name'))) !!}
                                <div class="input-group-append">
                                    <label class="input-group-text" for="name">
                                        <i class="fa fa-fw {{ trans('forms.create_product_icon_name') }}" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-sm-6 align-self-center">
                    <div class="form-group has-feedback row {{ $errors->has('color_id') ? ' has-error ' : '' }}">
                        {!! Form::label('color_id', trans('forms.create_product_label_color'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="col align-self-center">
                            <div class="input-group">
                                <select class="custom-select form-control" name="color_id" id="color_id" required="true">
                                    <option value="">Seleccione Color</option>
                                    @foreach ($colors as $color)
                                        @if ($product->color_id == $color->id)
                                            <option value="{{ $color->id }}" selected>{{ $color->name }}</option>
                                        @else
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endif
                                    @endforeach

                                </select>

                                <div class="input-group-append">
                                    <label class="input-group-text" for="role">
                                        <i class="{{ trans('forms.create_product_icon_color') }}" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('color_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('color_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 align-self-center">
                    <div class="form-group has-feedback row {{ $errors->has('material_id') ? ' has-error ' : '' }}">
                        {!! Form::label('material_id', trans('forms.create_product_label_material'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="col align-self-center">
                            <div class="input-group">
                                <select class="custom-select form-control" name="material_id" id="material_id" required="true">
                                    <option value="">{{ trans('forms.create_product_ph_material') }}</option>
                                    @foreach ($materials as $material)
                                        @if ($product->material_id == $material->id)
                                            <option value="{{$material->id}}" selected>{{$material->name}}</option>
                                        @else
                                            <option value="{{$material->id}}">{{$material->name}}</option>
                                        @endif
                                    @endforeach
                                </select>

                                <div class="input-group-append">
                                    <label class="input-group-text" for="material_id">
                                        <i class="{{ trans('forms.create_product_icon_material') }}" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('material_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('material_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-sm-6 align-self-center">
                    <div class="form-group has-feedback row {{ $errors->has('category_id') ? ' has-error ' : '' }}">
                        {!! Form::label('category_id', trans('forms.create_product_label_categories'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <select class="custom-select form-control" name="category_id" id="category_id" required="true">
                                    <option value="">{{ trans('forms.create_product_ph_categories') }}</option>
                                    @foreach ($categories as $categorie)
                                        @if ($product->category_id == $categorie->id)
                                            <option value="{{$categorie->id}}" selected>{{$categorie->name}}</option>
                                        @else
                                            <option value="{{$categorie->id}}">{{$categorie->name}}</option>
                                        @endif
                                    @endforeach
                                </select>

                                <div class="input-group-append">
                                    <label class="input-group-text" for="category_id">
                                        <i class="{{ trans('forms.create_product_icon_categories') }}" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('category_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('category_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 align-self-center">
                    <div class="form-group has-feedback row {{ $errors->has('aplication_id') ? ' has-error ' : '' }}">
                        {!! Form::label('aplication_id', trans('forms.create_product_label_aplications'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <select class="custom-select form-control" name="aplication_id" id="aplication_id" required="true">
                                    <option value="">{{ trans('forms.create_product_ph_aplications') }}</option>
                                    @foreach ($aplications as $aplication)
                                        @if ($product->aplication_id == $aplication->id)
                                            <option value="{{$aplication->id}}" selected>{{$aplication->name}}</option>
                                        @else
                                            <option value="{{$aplication->id}}">{{$aplication->name}}</option>
                                        @endif
                                    @endforeach
                                </select>

                                <div class="input-group-append">
                                    <label class="input-group-text" for="aplication_id">
                                        <i class="{{ trans('forms.create_product_icon_aplications') }}" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('aplication_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('aplication_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-sm-6 align-self-center">
                    <div class="form-group has-feedback row {{ $errors->has('familyproduct_id') ? ' has-error ' : '' }}">
                        {!! Form::label('familyproduct_id', trans('forms.create_product_label_familyproduct'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <select class="custom-select form-control" name="familyproduct_id" id="familyproduct_id" required="true">
                                    <option value="">{{ trans('forms.create_product_ph_familyproduct') }}</option>
                                    @foreach ($familyproducts as $familyproduct)
                                        @if ($product->familyproduct_id == $familyproduct->id)
                                            <option value="{{$familyproduct->id}}" selected>{{$familyproduct->name}}</option>
                                        @else
                                            <option value="{{$familyproduct->id}}">{{$familyproduct->name}}</option>
                                        @endif
                                    @endforeach
                                </select>

                                <div class="input-group-append">
                                    <label class="input-group-text" for="familyproduct_id">
                                        <i class="{{ trans('forms.create_product_icon_familyproduct') }}" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('familyproduct_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('familyproduct_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 align-self-center">
                    <div class="form-group has-feedback row {{ $errors->has('subproduct_id') ? ' has-error ' : '' }}">
                        {!! Form::label('subproduct_id', trans('forms.create_product_label_subproduct'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <select class="custom-select form-control" name="subproduct_id" id="subproduct_id" required="true">
                                    <option value="">{{ trans('forms.create_product_ph_subproduct') }}</option>
                                    @foreach ($subproducts as $subproduct)
                                        @if ($product->subproduct_id == $subproduct->id)
                                            <option value="{{$subproduct->id}}" selected>{{$subproduct->name}}</option>
                                        @else
                                            <option value="{{$subproduct->id}}">{{$subproduct->name}}</option>
                                        @endif
                                    @endforeach
                                </select>

                                <div class="input-group-append">
                                    <label class="input-group-text" for="subproduct_id">
                                        <i class="{{ trans('forms.create_product_icon_subproduct') }}" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('subproduct_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('subproduct_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-sm-5 align-self-center">
                    <div class="form-group has-feedback row {{ $errors->has('barcode') ? ' has-error ' : '' }}">
                        {!! Form::label('barcode', trans('forms.create_product_label_barcode'), array('class' => 'col-md-5 control-label')); !!}
                        <div class="col-md-7">
                            <div class="input-group">
                                {!! Form::text('barcode', $product->barcode, array('id' => 'barcode', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_barcode'))) !!}
                                <div class="input-group-append">
                                    <label for="barcode" class="input-group-text">
                                        <i class="fa fa-fw {{ trans('forms.create_product_icon_barcode') }}" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('barcode'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('barcode') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 align-self-center">
                    <div class="form-group has-feedback row {{ $errors->has('caliber') ? ' has-error ' : '' }}">
                        {!! Form::label('laminate', trans('forms.create_product_label_laminate'), array('class' => 'col-md-3 control-label text-danger')); !!}
                        {!! Form::label('caliber_lamination', trans('forms.create_extruder_label_caliber'), array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-6">
                            <div class="input-group">
                                {!! Form::text('caliber_lamination', $product->caliber_lamination, array('id' => 'caliber_lamination', 'class' => 'form-control', 'placeholder' => trans('forms.create_extruder_ph_caliber'))) !!}
                                <div class="input-group-append">
                                    <label for="caliber" class="input-group-text">
                                        <i class="fa fa-fw {{ trans('forms.create_extruder_icon_caliber') }}" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('caliber_lamination'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('caliber_lamination') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 align-self-center">
                    <div class="form-group has-feedback row {{ $errors->has('width_lamination') ? ' has-error ' : '' }}">
                        {!! Form::label('width_lamination', trans('forms.create_product_label_width'), array('class' => 'col-md-4 control-label')); !!}
                        <div class="col-md-8">
                            <div class="input-group">
                                {!! Form::text('width_lamination', $product->width_lamination, array('id' => 'width_lamination', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_width'))) !!}
                                <div class="input-group-append">
                                    <label for="width_lamination" class="input-group-text">
                                        <i class="fa fa-fw {{ trans('forms.create_product_icon_width') }}" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('width_lamination'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('width_lamination') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                            <div class="card card-info">
                                <div class="card-header">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        {!! trans('forms.create_product_label_title_atributes') !!}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-sm-4 align-self-center">
                                            <div class="form-group has-feedback row {{ $errors->has('capacity_id') ? ' has-error ' : '' }}">
                                                {!! Form::label('capacity', trans('forms.create_product_label_capacity'), array('class' => 'col-md-6 control-label')); !!}
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <select class="custom-select form-control" name="capacity_id" id="capacity_id" required="true">
                                                            <option value="">{{ trans('forms.create_product_ph_capacity') }}</option>
                                                            @foreach ($capacities as $capacity)
                                                                @if ($product->capacity_id == $capacity->id)
                                                                    <option value="{{$capacity->id}}" selected>{{$capacity->capacity}}</option>
                                                                @else
                                                                    <option value="{{$capacity->id}}">{{$capacity->capacity}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-append">
                                                            <label class="input-group-text" for="capacity">
                                                                <i class="{{ trans('forms.create_product_icon_capacity') }}" aria-hidden="true"></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('capacity_id'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('capacity_id') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 align-self-center">
                                            <div class="form-group has-feedback row {{ $errors->has('diameter_id') ? ' has-error ' : '' }}">
                                                {!! Form::label('diameter_id', trans('forms.create_product_label_diameter'), array('class' => 'col-md-6 control-label')); !!}
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <select class="custom-select form-control" name="diameter_id" id="diameter_id" required="true">
                                                            <option value="">{{ trans('forms.create_product_ph_diameter') }}</option>
                                                            @foreach ($diameters as $diameter)
                                                                @if ($product->diameter_id == $diameter->id)
                                                                    <option value="{{$diameter->id}}" selected>{{$diameter->diameter}}</option>
                                                                @else
                                                                    <option value="{{$diameter->id}}">{{$diameter->diameter}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-append">
                                                            <label class="input-group-text" for="diameter_id">
                                                                <i class="{{ trans('forms.create_product_icon_diameter') }}" aria-hidden="true"></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('diameter_id'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('diameter_id') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 align-self-center">
                                            <div class="form-group has-feedback row {{ $errors->has('inche_id') ? ' has-error ' : '' }}">
                                                {!! Form::label('inche_id', trans('forms.create_product_label_inches'), array('class' => 'col-md-6 control-label')); !!}
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <select class="custom-select form-control" name="inche_id" id="inche_id" required="true">
                                                            <option value="">{{ trans('forms.create_product_ph_inches') }}</option>
                                                            @foreach ($inches as $inche)
                                                                @if ($product->inche_id == $inche->id)
                                                                    <option value="{{$inche->id}}" selected>{{$inche->name}}</option>
                                                                @else
                                                                    <option value="{{$inche->id}}">{{$inche->name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-append">
                                                            <label class="input-group-text" for="inches_id">
                                                                <i class="{{ trans('forms.create_product_icon_inches') }}" aria-hidden="true"></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('inche_id'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('inche_id') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col-sm-4 align-self-center">
                                            <div class="form-group has-feedback row {{ $errors->has('design') ? ' has-error ' : '' }}">
                                                {!! Form::label('design', trans('forms.create_product_label_design'), array('class' => 'col-md-6 control-label')); !!}
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <select class="custom-select form-control" name="design" id="design" required="true">
                                                            <option value="">{{ trans('forms.create_product_ph_design') }}</option>
                                                            @if ($product->design == "N/A")
                                                                <option value="N/A" selected>N/A</option>
                                                            @else
                                                                <option value="N/A">N/A</option>
                                                            @endif
                                                            @if ($product->design == "Con Diseño")
                                                                <option value="Con Diseño" selected>Con Diseño</option>
                                                            @else
                                                                <option value="Con Diseño">Con Diseño</option>
                                                            @endif
                                                            @if ($product->design == "Sin Diseño")
                                                                <option value="Sin Diseño" selected>Sin Diseño</option>
                                                            @else
                                                                <option value="Sin Diseño">Sin Diseño</option>
                                                            @endif
                                                        </select>
                                                        <div class="input-group-append">
                                                            <label class="input-group-text" for="hole">
                                                                <i class="{{ trans('forms.create_product_icon_hole') }}" aria-hidden="true"></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('design'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('design') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 align-self-center">
                                            <div class="form-group has-feedback row {{ $errors->has('hole') ? ' has-error ' : '' }}">
                                                {!! Form::label('hole', trans('forms.create_product_label_hole'), array('class' => 'col-md-6 control-label')); !!}
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <select class="custom-select form-control" name="hole" id="hole" required="true">
                                                            <option value="">{{ trans('forms.create_product_ph_hole') }}</option>
                                                            @if ($product->hole == "N/A")
                                                                <option value="N/A" selected>N/A</option>
                                                            @else
                                                                <option value="N/A">N/A</option>
                                                            @endif
                                                            @if ($product->hole == "Con Agujero")
                                                                <option value="Con Agujero" selected>Con Agujero</option>
                                                            @else
                                                                <option value="Con Agujero">Con Agujero</option>
                                                            @endif
                                                            @if ($product->hole == "Sin Agujero")
                                                                <option value="Sin Agujero" selected>Sin Agujero</option>
                                                            @else
                                                                <option value="Sin Agujero">Sin Agujero</option>
                                                            @endif
                                                        </select>
                                                        <div class="input-group-append">
                                                            <label class="input-group-text" for="hole">
                                                                <i class="{{ trans('forms.create_product_icon_hole') }}" aria-hidden="true"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    @if ($errors->has('hole'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('hole') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 align-self-center">
                                            <div class="form-group has-feedback row {{ $errors->has('division') ? ' has-error ' : '' }}">
                                                {!! Form::label('division', trans('forms.create_product_label_division'), array('class' => 'col-md-6 control-label')); !!}
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <select class="custom-select form-control" name="division" id="division" required="true">
                                                            <option value="">{{ trans('forms.create_product_ph_division') }}</option>
                                                                @if ($product->division == "N/A")
                                                                    <option value="N/A" selected>N/A</option>
                                                                @else
                                                                    <option value="N/A">N/A</option>
                                                                @endif
                                                                @if ($product->division == "1")
                                                                    <option value="1" selected>1</option>
                                                                @else
                                                                    <option value="1">1</option>
                                                                @endif
                                                                @if ($product->division == "2")
                                                                    <option value="2" selected>2</option>
                                                                @else
                                                                    <option value="2">2</option>
                                                                @endif
                                                                @if ($product->division == "3")
                                                                <option value="3" selected>3</option>
                                                                @else
                                                                    <option value="3">3</option>
                                                                @endif
                                                                @if ($product->division == "4")
                                                                    <option value="4" selected>4</option>
                                                                @else
                                                                    <option value="4">4</option>
                                                                @endif
                                                                @if ($product->division == "5")
                                                                    <option value="5" selected>5</option>
                                                                @else
                                                                    <option value="5">5</option>
                                                                @endif
                                                            </select>
                                                        <div class="input-group-append">
                                                            <label class="input-group-text" for="division">
                                                                <i class="{{ trans('forms.create_product_icon_division') }}" aria-hidden="true"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    @if ($errors->has('division'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('division') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>


                                <div class="card card-info">
                                    <div class="card-header">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            {!! trans('forms.create_product_label_title_atributes') !!}
                                        </div>

                                    </div>

                                    <div class="card-body">
                                        <div class="form-group has-feedback row {{ $errors->has('composition') ? ' has-error ' : '' }}">
                                            {!! Form::label('composition', trans('forms.create_product_label_composition'), array('class' => 'col col-sm-2 control-label')); !!}
                                            <div class="col-sm-10 align-self-center">
                                                <div class="input-group">
                                                    {!! Form::textarea('composition', $product->composition, array( 'rows' => 3, 'id' => 'composition', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_composition'))) !!}
                                                    <div class="input-group-append">
                                                        <label class="input-group-text" for="composition">
                                                            <i class="fa fa-fw {{ trans('forms.create_product_icon_composition') }}" aria-hidden="true"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                                @if ($errors->has('composition'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('composition') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback row {{ $errors->has('raw_material') ? ' has-error ' : '' }}">
                                            {!! Form::label('raw_material', trans('forms.create_product_label_raw_material'), array('class' => 'col col-sm-2 control-label')); !!}
                                            <div class="col-sm-10 align-self-center">
                                                <div class="input-group">
                                                    {!! Form::textarea('raw_material',  $product->raw_material,array( 'rows' => 3, 'id' => 'raw_material', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_raw_material'))) !!}
                                                    <div class="input-group-append">
                                                        <label class="input-group-text" for="raw_material">
                                                            <i class="fa fa-fw {{ trans('forms.create_product_icon_raw_material') }}" aria-hidden="true"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                                @if ($errors->has('raw_material'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('raw_material') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback row {{ $errors->has('useful_life') ? ' has-error ' : '' }}">
                                            {!! Form::label('useful_life', trans('forms.create_product_label_useful_life'), array('class' => 'col col-sm-2 control-label')); !!}
                                            <div class="col-sm-10 align-self-center">
                                                <div class="input-group">
                                                    {!! Form::textarea('useful_life', $product->useful_life,array('rows' => 3, 'id' => 'useful_life', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_useful_life'))) !!}
                                                    <div class="input-group-append">
                                                        <label class="input-group-text" for="useful_life">
                                                            <i class="fa fa-fw {{ trans('forms.create_product_icon_useful_life') }}" aria-hidden="true"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                                @if ($errors->has('useful_life'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('useful_life') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>


                                    </div>
                                </div>








                                <div class="card card-info">
                                    <div class="card-header">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            {!! trans('forms.create_product_label_title_images') !!}
                                        </div>

                                    </div>

                                    <div class="card-body">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    @if($product->picture1)
                                                        <td align="center"><img id="imagenPrevisualizacion1" src="{!! asset('images/' . $product->picture1) !!}" style="width: 200px; align:center;" alt="{{ $product->name }}"></td>
                                                    @else
                                                        <td align="center"><img id="imagenPrevisualizacion1" src="{!! asset('images/no_image_available.png') !!}" style="width: 200px; align:center;" alt="{{ $product->name }}"></td>
                                                    @endif
                                                    @if($product->picture2)
                                                        <td align="center"><img id="imagenPrevisualizacion2" src="{!! asset('images/' . $product->picture2) !!}" style="width: 200px; align:center;" alt="{{ $product->name }}"></td>
                                                    @else
                                                        <td align="center"><img id="imagenPrevisualizacion2" src="{!! asset('images/no_image_available.png') !!}" style="width: 200px; align:center;" alt="{{ $product->name }}"></td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-group has-feedback row {{ $errors->has('picture1') ? ' has-error ' : '' }}">
                                                            {!! Form::label('picture1', trans('forms.create_product_label_picture1'), array('class' => 'col col-sm-3 control-label')); !!}
                                                            <div class="col-sm-8 align-self-center">
                                                                <div class="input-group">
                                                                    {!! Form::file('picture1', NULL, array('id' => 'picture1', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_picture1'), "accept" => "image/*")) !!}
                                                                </div>
                                                                @if ($errors->has('picture1'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('picture1') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group has-feedback row {{ $errors->has('picture2') ? ' has-error ' : '' }}">
                                                            {!! Form::label('picture2', trans('forms.create_product_label_picture2'), array('class' => 'col col-sm-3 control-label')); !!}
                                                            <div class="col-sm-8 align-self-center">
                                                                <div class="input-group">
                                                                    {!! Form::file('picture2', NULL, array('id' => 'picture2', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_picture1'))) !!}
                                                                </div>
                                                                @if ($errors->has('picture2'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('picture2') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>



                                    </div>
                                </div>








                                <div class="row align-items-center">
                                    <div class="col-sm-6 align-self-center">
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                                    {!! trans('forms.create_product_label_title_weight') !!}
                                                </div>

                                            </div>

                                            <div class="card-body">
                                                <div class="form-group has-feedback row {{ $errors->has('unit_weight') ? ' has-error ' : '' }}">
                                                    {!! Form::label('unit_weight', trans('forms.create_product_label_unit_weight'), array('class' => 'col-md-4 control-label')); !!}
                                                    <div class="col align-self-center">
                                                        <div class="input-group">
                                                            {!! Form::text('unit_weight', $product->unit_weight, array('id' => 'unit_weight', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_unit_weight'))) !!}
                                                            <div class="input-group-append">
                                                                <label class="input-group-text" for="name">
                                                                    <i class="fa fa-fw {{ trans('forms.create_product_icon_unit_weight') }}" aria-hidden="true"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('unit_weight'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('unit_weight') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback row {{ $errors->has('net_weight') ? ' has-error ' : '' }}">
                                                    {!! Form::label('net_weight', trans('forms.create_product_label_net_weight'), array('class' => 'col-md-4 control-label')); !!}
                                                    <div class="col align-self-center">
                                                        <div class="input-group">
                                                            {!! Form::text('net_weight', $product->net_weight, array('id' => 'weight', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_net_weight'), 'required')) !!}
                                                            <div class="input-group-append">
                                                                <label class="input-group-text" for="name">
                                                                    <i class="fa fa-fw {{ trans('forms.create_product_icon_net_weight') }}" aria-hidden="true"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('net_weight'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('net_weight') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback row {{ $errors->has('gross_weight') ? ' has-error ' : '' }}">
                                                    {!! Form::label('gross_weight', trans('forms.create_product_label_gross_weight'), array('class' => 'col-md-4 control-label')); !!}
                                                    <div class="col align-self-center">
                                                        <div class="input-group">
                                                            {!! Form::text('gross_weight', $product->gross_weight, array('id' => 'gross_weight', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_gross_weight'))) !!}
                                                            <div class="input-group-append">
                                                                <label class="input-group-text" for="name">
                                                                    <i class="fa fa-fw {{ trans('forms.create_product_icon_gross_weight') }}" aria-hidden="true"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('gross_weight'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('gross_weight') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card card-info">
                                            <div class="card-header">
                                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                                    {!! trans('forms.create-new-product_box_dimensions') !!}
                                                </div>
                                            </div>

                                            <div class="card-body">


                                                <div class="row align-items-center">
                                                    <div class="col-sm-4 align-self-center">
                                                        <div class="form-group has-feedback row {{ $errors->has('length') ? ' has-error ' : '' }}">
                                                            {!! Form::label('length', 'L', array('class' => 'col-md-1 control-label')); !!}
                                                             <div class="col-md-10">
                                                                <div class="input-group">
                                                                    {!! Form::text('length', $product->length, array('id' => 'length', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_length'), 'type' => 'number')) !!}
                                                                    <div class="input-group-append">
                                                                        <label for="length" class="input-group-text">
                                                                            <i class="fa fa-fw {{ trans('forms.create_product_icon_length') }}" aria-hidden="true"></i>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                @if ($errors->has('length'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('length') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4 align-self-center">
                                                        <div class="form-group has-feedback row {{ $errors->has('width') ? ' has-error ' : '' }}">
                                                            {!! Form::label('width', 'A', array('class' => 'col-md-1 control-label')); !!}
                                                            <div class="col-md-10">
                                                                <div class="input-group">
                                                                    {!! Form::text('width', $product->width, array('id' => 'width', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_width'), 'type' => 'number')) !!}
                                                                    <div class="input-group-append">
                                                                        <label class="input-group-text" for="width">
                                                                            <i class="fa fa-fw {{ trans('forms.create_product_icon_width') }}" aria-hidden="true"></i>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                @if ($errors->has('width'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('width') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4 align-self-center">
                                                        <div class="form-group has-feedback row {{ $errors->has('height') ? ' has-error ' : '' }}">
                                                            {!! Form::label('height', 'H', array('class' => 'col-md-1 control-label')); !!}
                                                            <div class="col-md-10">
                                                                <div class="input-group">
                                                                    {!! Form::text('height', $product->height, array('id' => 'height', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_height'), 'type' => 'number')) !!}
                                                                    <div class="input-group-append">
                                                                        <label class="input-group-text" for="heighte">
                                                                            <i class="fa fa-fw {{ trans('forms.create_product_icon_height') }}" aria-hidden="true"></i>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                @if ($errors->has('height'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('height') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 align-self-center">
                                                        <div class="form-group has-feedback row {{ $errors->has('cartonsize') ? ' has-error ' : '' }}">
                                                            {!! Form::label('cartonsize', trans('forms.create_product_label_cartonsize'), array('class' => 'col-md-4 control-label')); !!}
                                                            <div class="col align-self-center">
                                                                <div class="input-group">
                                                                    {!! Form::text('cartonsize', $product->cartonsize, array('id' => 'cartonsize', 'readonly' => 'true', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_cartonsize'))) !!}
                                                                    <div class="input-group-append">
                                                                        <label class="input-group-text" for="cartonsize">
                                                                            <i class="fa fa-fw {{ trans('forms.create_product_icon_cartonsize') }}" aria-hidden="true"></i>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                @if ($errors->has('cartonsize'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('cartonsize') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-sm-6 align-self-center">
                                        <div class="card card-info">

                                            <div class="card-header">
                                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                                    {!! trans('forms.create_product_label_title_package') !!}
                                                </div>

                                            </div>

                                            <div class="card-body">
                                                <div class="form-group has-feedback row {{ $errors->has('package_units') ? ' has-error ' : '' }}">
                                                    {!! Form::label('package_units', trans('forms.create_product_label_package_units'), array('class' => 'col-md-4 control-label')); !!}
                                                    <div class="col align-self-center">
                                                        <div class="input-group">
                                                            {!! Form::number('package_units', $product->package_units, array('id' => 'package_units', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_package_units'), 'required')) !!}
                                                            <div class="input-group-append">
                                                                <label class="input-group-text" for="package_units">
                                                                    <i class="fa fa-fw {{ trans('forms.create_product_icon_package_units') }}" aria-hidden="true"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('package_units'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('package_units') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback row {{ $errors->has('package_box') ? ' has-error ' : '' }}">
                                                    {!! Form::label('package_box', trans('forms.create_product_label_package_box'), array('class' => 'col-md-4 control-label')); !!}
                                                    <div class="col align-self-center">
                                                        <div class="input-group">
                                                            {!! Form::number('package_box', $product->package_box, array('id' => 'package_box', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_package_box'), 'required')) !!}
                                                            <div class="input-group-append">
                                                                <label class="input-group-text" for="package_box">
                                                                    <i class="fa fa-fw {{ trans('forms.create_product_icon_package_box') }}" aria-hidden="true"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('package_box'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('package_box') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback row {{ $errors->has('box_units') ? ' has-error ' : '' }}">
                                                    {!! Form::label('box_units', trans('forms.create_product_label_box_units'), array('class' => 'col-md-4 control-label')); !!}
                                                    <div class="col align-self-center">
                                                        <div class="input-group">
                                                            {!! Form::number('box_units', $product->box_units, array('id' => 'box_units', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_box_units'), 'required')) !!}
                                                            <div class="input-group-append">
                                                                <label class="input-group-text" for="box_units">
                                                                    <i class="fa fa-fw {{ trans('forms.create_product_icon_box_units') }}" aria-hidden="true"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('box_units'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('box_units') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback row {{ $errors->has('box_litter') ? ' has-error ' : '' }}">
                                                    {!! Form::label('box_litter', trans('forms.create_product_label_box_litter'), array('class' => 'col-md-4 control-label')); !!}
                                                    <div class="col align-self-center">
                                                        <div class="input-group">
                                                            {!! Form::number('box_litter', $product->box_litter, array('id' => 'box_litter', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_box_litter'), 'required')) !!}
                                                            <div class="input-group-append">
                                                                <label class="input-group-text" for="box_litter">
                                                                    <i class="fa fa-fw {{ trans('forms.create_product_icon_box_litter') }}" aria-hidden="true"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('box_litter'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('box_litter') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback row {{ $errors->has('platform_litter') ? ' has-error ' : '' }}">
                                                    {!! Form::label('platform_litter', trans('forms.create_product_label_platform_litter'), array('class' => 'col-md-4 control-label')); !!}
                                                    <div class="col align-self-center">
                                                        <div class="input-group">
                                                            {!! Form::number('platform_litter', $product->platform_litter, array('id' => 'platform_litter', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_platform_litter'), 'required')) !!}
                                                            <div class="input-group-append">
                                                                <label class="input-group-text" for="platform_litter">
                                                                    <i class="fa fa-fw {{ trans('forms.create_product_icon_platform_litter') }}" aria-hidden="true"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('platform_litter'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('platform_litter') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group has-feedback row {{ $errors->has('dunnage_size') ? ' has-error ' : '' }}">
                                                    {!! Form::label('dunnage_size', trans('forms.create_product_label_dunnage_size'), array('class' => 'col-md-4 control-label')); !!}
                                                    <div class="col align-self-center">
                                                        <div class="input-group">
                                                            {!! Form::number('dunnage_size', $product->dunnage_size, array('id' => 'dunnage_size', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_dunnage_size'), 'required')) !!}
                                                            <div class="input-group-append">
                                                                <label class="input-group-text" for="dunnage_size">
                                                                    <i class="fa fa-fw {{ trans('forms.create_product_icon_dunnage_size') }}" aria-hidden="true"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('dunnage_size'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('dunnage_size') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                    </div>
                                </div>



                        <div class="card card-info">
                            <div class="card-header">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    {!! trans('forms.create_product_label_title_composition') !!}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-sm-6 align-self-center">
                                        <div class="form-group has-feedback row {{ $errors->has('time_production') ? ' has-error ' : '' }}">
                                            {!! Form::label('time_production', trans('forms.create_product_label_time_production'), array('class' => 'col-md-4 control-label')); !!}
                                            <div class="col align-self-center">
                                                <div class="input-group">
                                                    {!! Form::number('time_production', $product->time_production, array('id' => 'time_production', 'class' => 'form-control', 'placeholder' => trans('forms.create_product_ph_time_production'), 'required')) !!}
                                                    <div class="input-group-append">
                                                        <label class="input-group-text" for="time_production">
                                                            <i class="fa fa-fw {{ trans('forms.create_product_icon_time_production') }}" aria-hidden="true"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                                @if ($errors->has('time_production'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('time_production') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 align-self-center">

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                        <div class="row">
                                <div class="col-5">

                                </div>
                                <div class="col-2">
                                    {!! Form::button(trans('forms.save-changes'), array('class' => 'btn btn-success btn-block margin-bottom-1 mt-3 mb-2 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_machine__modal_text_confirm_title'), 'data-message' => trans('modals.edit_machine__modal_text_confirm_message'))) !!}
                                    @include('modals.modal-save')
                                    <br><br><br>
                                </div>

                                <div class="col-5">

                                </div>

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    <script>
        const $seleccionArchivos1 = document.querySelector("#picture1"),
        $imagenPrevisualizacion1 = document.querySelector("#imagenPrevisualizacion1");
        const $seleccionArchivos2 = document.querySelector("#picture2"),
        $imagenPrevisualizacion2 = document.querySelector("#imagenPrevisualizacion2");


        $seleccionArchivos1.addEventListener("change", () => {
        const archivos1 = $seleccionArchivos1.files;
        if (!archivos1 || !archivos1.length) {
            $imagenPrevisualizacion1.src = "{!! asset('images/' . $product->picture1) !!}";
            return;
        }
        $seleccionArchivos2.addEventListener("change", () => {
        const archivos2 = $seleccionArchivos2.files;
        if (!archivos2 || !archivos2.length) {
            $imagenPrevisualizacion2.src = "{!! asset('images/' . $product->picture2) !!}";
            return;
        }


        const primerArchivo1 = archivos1[0];
        const primerArchivo2 = archivos2[0];

        const objectURL1 = URL.createObjectURL(primerArchivo1);
        const objectURL2 = URL.createObjectURL(primerArchivo2);

        $imagenPrevisualizacion1.src = objectURL1;
        });
        $imagenPrevisualizacion2.src = objectURL2;
        });

        $seleccionArchivos1.addEventListener("change", () => {
            const archivos1 = $seleccionArchivos1.files;
            if (!archivos1 || !archivos1.length) {
                $imagenPrevisualizacion1.src = "{!! asset('images/' . $product->picture1) !!}";
                return;
            }
            const primerArchivo1 = archivos1[0];
            const objectURL1 = URL.createObjectURL(primerArchivo1);
            $imagenPrevisualizacion1.src = objectURL1;
        });

        $seleccionArchivos2.addEventListener("change", () => {
            const archivos2 = $seleccionArchivos2.files;
            if (!archivos2 || !archivos2.length) {
                $imagenPrevisualizacion2.src = "{!! asset('images/' . $product->picture2) !!}";
                return;
            }
            const primerArchivo2 = archivos2[0];
            const objectURL2 = URL.createObjectURL(primerArchivo2);
            $imagenPrevisualizacion2.src = objectURL2;


        });



        $(document).ready(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });


            document.getElementById("length").onchange = function(){alerta()};
            document.getElementById("width").onchange = function(){alerta()};
            document.getElementById("height").onchange = function(){alerta()};

            function inputFieldsAreFilled() {

                if (isNaN($('#length').val()) || $('#length').val() == '0' || $('#length').val() == '') {
                    return false;
                }
                if  (isNaN($('#width').val()) || $('#width').val() == '0' || $('#width').val() == '') {
                    return false;
                }
                if (isNaN($('#height').val()) || $('#height').val() == '0' || $('#height').val() == '') {
                    return false;
                }

                return true;
            }

            function alerta()  {
                var Texto = '';

                if (isNaN($('#length').val()) || $('#length').val() == '0' || $('#length').val() == '') {
                    Texto = Texto + "- El largo debe ser numérico \n";
                }
                if (isNaN($('#width').val())|| $('#width').val() == '0' || $('#width').val() == '') {
                    Texto = Texto + "- El Ancho debe ser numérico \n";
                }
                if (isNaN($('#height').val()) || $('#height').val() == '0' || $('#height').val() == '') {
                    Texto = Texto +  "- La altura debe ser numérico \n";
                }

                if (inputFieldsAreFilled()) {
                    Var1 = $("#length").val().toString();
                    Var2 = $("#width").val().toString();
                    Var3 = $("#height").val().toString();
                    $("#cartonsize").val(Var1 + ' cm x ' + Var2 + ' cm x ' + Var3 + ' cm');
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Debe Completar los Campos Requeridos: \n' + Texto
                    });
                }
            };
        });


    </script>
@endsection
