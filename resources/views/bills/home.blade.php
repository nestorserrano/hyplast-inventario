@extends('adminlte::page')


@section('template_fastload_css')
@endsection

@section('template_title')
    {!! trans('hyplast.showing-all-machines') !!}
@endsection

@section('template_linked_css')
    @if(config('hyplast.enabledDatatablesJs'))
        <link rel="stylesheet" type="text/css" href="{{ config('hyplast.datatablesCssCDN') }}">
    @endif
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
    </style>
@endsection


@section('content')
    <div class="container-fluid">
         <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {!! trans('hyplast.showing-all-bills') !!}
                            </span>

                        </div>
                    </div>

                    <div class="card-body">

                        @if(config('hyplast.enableSearch'))
                            @include('partials.search-bills-form')
                        @endif

                        <div class="table-responsive machine-table">
                            <table id="data-table" class="table table-striped table-bordered shadow-lg table-sm data-table" style="width:100%">
                                <thead class="thead">
                                    <tr>
                                        <th>Fecha</th>
                                        <th class="hidden-xs">Factura</th>
                                        <th class="hidden-xs">NCF</th>
                                        <th class="hidden-xs">Cliente</th>
                                        <th class="hidden-xs">Bultos</th>
                                        <th class="hidden-xs">#Contenedor</th>
                                        <th class="hidden-xs">Kilos</th>
                                        <th class="no-search no-sort">Sellos</th>
                                        <th class="no-search no-sort">Booking</th>
                                        <th class="no-search no-sort"></th>
                                    </tr>
                                </thead>
                                @php
                                    $i = 1
                                @endphp
                                <tbody id="bills-table">
                                    @foreach($bills as $bill)
                                        <tr>
                                            <td>{{ Carbon\Carbon::parse($bill->FECHA)->format('d-m-Y') }}</td>
                                            <td>{{$bill->FACTURA }}</td>
                                            <td>{{ $bill->account->DOCUMENTO_FISCAL }}</td>
                                            <td>{{$bill->NOMBRE_CLIENTE }}</td>
                                            <td align="center"><strong>{{$bill->RUBRO1 }}</strong></td>
                                            <td>{{$bill->RUBRO2 }}</td>
                                            <td>{{$bill->RUBRO3 }}</td>
                                            <td>{{$bill->RUBRO4 }}</td>
                                            <td>{{$bill->RUBRO5 }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-success btn-block" href="{{ URL::to('bills/' . $bill->factura) }}" data-toggle="tooltip" title="Mostrar">
                                                    {!! trans('hyplast.buttons.show') !!}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tbody id="search_results"></tbody>
                                    @if(config('hyplast.enableSearch'))
                                        <tbody id="search_results"></tbody>
                                    @endif
                            </table>
                        </div>

                    </div>
                    @if(config('hyplast.enablePagination'))
                    {{ $bills->links() }}
                @endif
                </div>
            </div>
        </div>
    </div>



@endsection

@section('footer_scripts')
    @if ((count($bills) > config('hyplast.datatablesJsStartCount')) && config('hyplast.enabledDatatablesJs'))
        @include('scripts.datatables.datatables')
    @endif
    @include('scripts.save-modal-script')
    @if(config('hyplast.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
    @if(config('hyplast.enableSearch'))
        @include('scripts.searchs.search-bills')
    @endif


    <script type="text/javascript">

        function modalProduct(id) {
            let resultsContainer = $('#modal_result2');
            let title = $('#modal-title');
            let btn01 = $('#btn1');
            let noResulsHtml ='<tr>' +
                                '<td>{!! trans("hyplast.machines-products-no") !!}</td>' +
                                '<tr>';
            resultsContainer.html("");
            title.html("");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type:'POST',
                url: "{{ url('machinesproducts') }}/" + id,
                data: {_token: CSRF_TOKEN},
                success: function (result) {
                    resultsContainer.html("");
                    title.html("");
                    btn01.html("");
                    btn01.html('<button class="btn  btn-sm btn-primary btn-block" data-bs-target="#" data-bs-toggle="modal" data-toggle="tooltip" onclick="attachConfirmation(' + result.id + ')" type="submit"> {!! trans("hyplast.buttons.create-new6") !!}</button>');
                    title.html('<h5><strong>Máquina: ' + result.name + '</strong></h5>');
                    if (result.products.length != 0) {
                        var i = 0;
                        $.each(result, function() {
                            resultsContainer.append(

                                    '<tr>' +
                                    '<td>' + result.products[i].code + '</td>' +
                                    '<td>' + result.products[i].name + '</td>' +
                                    '<td><button class="btn  btn-sm btn-danger btn-block" type="submit" ' +
                                    'onclick="deleteattach(' + result.id + ',' + result.products[i].id + ')"> {!! trans("hyplast.buttons.delete") !!}</button></td>' +
                                    '<tr>'
                            );
                                i += 1;
                        });

                    } else {
                        resultsContainer.append(noResulsHtml);
                    };

                },

                error: function (response, status, error) {
                    if (response.status === 422) {
                        resultsContainer.append(noResulsHtml);
                        title.html('<h5><strong>Máquina: ' + result.name + '</strong></h5>');
                        btn01.html('<button class="btn  btn-sm btn-primary btn-block" data-bs-target="#" data-bs-toggle="modal" data-toggle="tooltip" onclick="attachConfirmation(' + result.id + ')" type="submit"> {!! trans("hyplast.buttons.create-new6") !!}</button>');
                    };
                },
            });

        };

        cancelbutton3.click(function(e) {
            resultsContainer.html('');
        });

        cancelbutton4.click(function(e) {
            resultsContainer.html('');
        });

    </script>
@endsection
