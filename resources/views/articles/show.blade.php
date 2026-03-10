@extends('adminlte::page')


@section('template_title')
    {!! trans('hyplast.showing-product', ['name' => $article->name]) !!}
@endsection

@section('template_linked_css')

    <style type="text/css" media="screen">
        .machine-table {
            border: 0;
        }
        .machine-table tr td:first-child {
            padding-left: 15px;
        }
        .machine-table tr td:last-child {
            padding-right: 15px;
        }
        .machine-table.table-responsive,
        .machine-table.table-responsive table {
            margin-bottom: 0;
        }

        .content23 {
            width:450px;
            height:250px;
            line-height:250px;
            margin:0px auto;
            text-align:center;

        }

        .content24 {

            margin:0px auto;
            text-align:center;

        }

        .content img {
            vertical-align:middle;
        }
    </style>
@endsection


@section('content')

  <div class="container">
    <div class="row">
      <div class="col-lg-10 offset-lg-1">
        <div class="card">

            <div class="card-header text-white bg-success">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                {!! trans('hyplast.showing-product-title', ['name' => $article->name]) !!}
                <div class="pull-right">
                    <a href="{{ route('articles') }}" class="btn btn-danger btn-sm float-right" data-toggle="tooltip" data-placement="left" title="{{ trans('hyplast.tooltips.back-machine') }}">
                        <i class="fa fa-fw fa-reply" aria-hidden="true"></i>
                        {!! trans('hyplast.buttons.back-to-machines') !!}
                    </a>
                </div>
            </div>
          </div>

        <div class="card-body">
            <div class="row align-items-start">

                <div class="col-md-12 align-self-start">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>{!! Form::label('code', trans('forms.create_product_label_code')); !!} </td>
                                    <td>{{$article->code }}</td>
                                    <td>{!! Form::label('name', trans('forms.create_product_label_name')); !!}</td>
                                    <td>{{ $article->name }}</td>
                                    <td rowspan="4" align="center">
                                        @if($product->picture1)
                                            <img style="width: 200px; align:center;" src="{!! asset('images/' . $product->picture1) !!}" alt="{{ $article->barcode }}">
                                            <?php echo DNS1D::getBarcodeHTML("$article->barcode", "EAN13");?>
                                        @else
                                            <img style="width: 200px; align:center;" src="../images/no_image_available.png" alt="{{ $article->name }}">
                                            <?php echo DNS1D::getBarcodeHTML("$article->barcode", "EAN13");?>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>{!! Form::label('color_id', trans('forms.create_product_label_color')); !!}</td>
                                    <td>{{ $article->color }}</td>
                                    <td>{!! Form::label('material_id', trans('forms.create_product_label_material')); !!}</td>
                                    <td>{{ $article->material }}</td>
                                </tr>
                                <tr>

                                    <td>{!! Form::label('category_id', trans('forms.create_product_label_categories')); !!}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{!! Form::label('aplication_id', trans('forms.create_product_label_aplications')); !!}</td>
                                    <td>{{ $product->aplication->name }}</td>

                                </tr>
                                <tr>

                                    <td>{!! Form::label('category_id', trans('forms.create_product_package_information')); !!}</td>
                                    <td>{{ number_format($product->package_box) }} x {{ number_format($product->package_units) }}</td>
                                    <td> {!! Form::label('barcode', trans('forms.create_product_label_barcode')); !!}</td>
                                    <td>
                                        {{ $article->barcode }}
                                    </td>

                                </tr>

                            </tbody>
                        </table>
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
                                {{ $product->capacity->capacity }} Oz
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 align-self-center">
                        <div class="form-group has-feedback row {{ $errors->has('diameter_id') ? ' has-error ' : '' }}">
                            {!! Form::label('diameter_id', trans('forms.create_product_label_diameter'), array('class' => 'col-md-6 control-label')); !!}
                            <div class="col-md-6">
                                {{ $product->diameter->diameter}} mm
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 align-self-center">
                        <div class="form-group has-feedback row {{ $errors->has('inche_id') ? ' has-error ' : '' }}">
                            {!! Form::label('inche_id', trans('forms.create_product_label_inches'), array('class' => 'col-md-6 control-label')); !!}
                            <div class="col-md-6">
                                {{ $product->inche->name}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col-sm-4 align-self-center">
                        <div class="form-group has-feedback row {{ $errors->has('design') ? ' has-error ' : '' }}">
                            {!! Form::label('design', trans('forms.create_product_label_design'), array('class' => 'col-md-6 control-label')); !!}
                            <div class="col-md-6">
                                {{ $product->design }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 align-self-center">
                        <div class="form-group has-feedback row {{ $errors->has('hole') ? ' has-error ' : '' }}">
                            {!! Form::label('hole', trans('forms.create_product_label_hole'), array('class' => 'col-md-6 control-label')); !!}
                            <div class="col-md-6">
                                {{ $product->hole }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 align-self-center">
                        <div class="form-group has-feedback row {{ $errors->has('division') ? ' has-error ' : '' }}">
                            {!! Form::label('division', trans('forms.create_product_label_division'), array('class' => 'col-md-6 control-label')); !!}
                            <div class="col-md-6">
                                {{ $product->division }}
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
                        {!! $product->composition !!}
                    </div>
                </div>
                <div class="form-group has-feedback row {{ $errors->has('raw_material') ? ' has-error ' : '' }}">
                    {!! Form::label('raw_material', trans('forms.create_product_label_raw_material'), array('class' => 'col col-sm-2 control-label')); !!}
                    <div class="col-sm-10 align-self-center">
                        {!! $product->raw_material !!}
                    </div>
                </div>
                <div class="form-group has-feedback row {{ $errors->has('useful_life') ? ' has-error ' : '' }}">
                    {!! Form::label('useful_life', trans('forms.create_product_label_useful_life'), array('class' => 'col col-sm-2 control-label')); !!}
                    <div class="col-sm-10 align-self-center">
                        {!! $product->useful_life !!}
                    </div>
                </div>
            </div>
        </div>


        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="card card-info">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            {!! trans('forms.create_product_label_title_weight') !!}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">
                                            {!! Form::label('net_weight', trans('forms.create_product_label_volumen')); !!}
                                        </th>
                                        <th style="text-align: center;">
                                            {!! Form::label('net_weight', trans('forms.create_product_label_net_weight')); !!}
                                        </th>
                                        <th style="text-align: center;">
                                            {!! Form::label('gross_weight', trans('forms.create_product_label_gross_weight')); !!}
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td align="center">{{ number_format($article->volumen,2) }} g</td>
                                        <td align="center"> {{ number_format($article->peso_neto,2) }} kg</td>
                                        <td align="center">{{ number_format($article->peso_bruto,2) }} kg</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 align-self-center">

                <div class="card card-info">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; ">
                            {!! trans('forms.create-new-product_box_dimensions2') !!} {{ $product->cartonsize }}
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">
                                                {!! Form::label('length', 'Largo'); !!}
                                            </th>
                                            <th style="text-align: center;">
                                                {!! Form::label('width', 'Ancho'); !!}
                                            </th>
                                            <th style="text-align: center;">
                                                {!! Form::label('height', 'Alto'); !!}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td align="center">{{ $product->length }} cm</td>
                                            <td align="center">{{ $product->width }} cm</td>
                                            <td align="center">{{ $product->height }} cm</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="card card-info">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            {!! trans('forms.create_product_label_title_package') !!}: {{ number_format($product->package_box) }} x {{ number_format($product->package_units) }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                <th style="text-align: center;">
                                        {!! Form::label('package_units', trans('forms.create_product_label_package_units')); !!}
                                </th>
                                <th style="text-align: center;">
                                        {!! Form::label('box_units', trans('forms.create_product_label_package_box')); !!}
                                </th>
                                <th style="text-align: center;">
                                        {!! Form::label('platform_litter', trans('forms.create_product_label_box_units')); !!}
                                </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <td align="center">{{ number_format($product->package_units) }}</td>
                                <td align="center">{{ number_format($product->package_box) }}</td>
                                <td align="center">{{ number_format($product->box_units) }}</td>
                                </tr>
                            </tbody>
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">
                                            {!! Form::label('box_litter', trans('forms.create_product_label_box_litter')); !!}
                                        </th>
                                        <th style="text-align: center;">
                                            {!! Form::label('platform_litter', trans('forms.create_product_label_platform_litter')); !!}
                                        </th>
                                        <th style="text-align: center;">
                                            {!! Form::label('dunnage_size', trans('forms.create_product_label_dunnage_size')); !!}
                                        </th>
                                    </tr>
                                </thead>
                            <tbody>

                                <tr>
                                <td align="center">{{ number_format($product->box_litter) }}</td>
                                <td align="center">{{ number_format($product->platform_litter) }}</td>
                                <td align="center">{{ number_format($product->dunnage_size) }}</td>
                                </tr>
                            </tbody>

                            </table>
                        </div>



                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card card-info">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            {!! trans('forms.create_product_label_title_picture2') !!}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-6">
                            <div class='content23'>
                                @if($product->picture2)
                                    <img style="width: 250px; align:center; vertical-align:middle;" src="{!! asset('images/' . $product->picture2) !!}" alt="Imagen del paletizado de: {{ $product->name}}">
                                @else
                                    <img style="width: 200px; align:center;" src="../images/no_image_available.png" alt="{{ $product->name }}">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>






        <br>
        <div class="row">
            <div class="col-sm-12">
                @if ($product->id)
                    <div class="row">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <a class="btn btn-sm btn-info btn-block" href="{{ URL::to('products/' . $product->id . '/edit') }}" data-toggle="tooltip" title="Edit">
                                    {!! trans('hyplast.buttons.edit') !!}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <button class="btn  btn-sm btn-danger btn-block" type="submit" onclick="deleteConfirmation({{$product->id}})"> {!! trans('hyplast.buttons.delete') !!}</button>
                            </div>
                        </div>
                        <div class="col-md-4">

                        </div>
                    </div>
                    <br>
                    <br>
                @endif
            </div>
        </div>
    </div>
</div>



@endsection

@section('footer_scripts')
  @if(config('hyplast.tooltipsEnabled'))
    @include('scripts.tooltips')
  @endif
  <script type="text/javascript">
    function deleteConfirmation(id) {
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
                $.ajax({
                    type: 'POST',
                    url: "{{url('/products/delete/')}}" + id,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        if (results.success === true) {
                            swal("Done!", results.message, "success");
                            window.location = "/products";
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



