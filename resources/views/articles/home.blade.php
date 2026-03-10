@extends('adminlte::page')


@section('template_fastload_css')
@endsection

@section('template_title')
    {!! trans('hyplast.showing-all-products') !!}
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

@endsection
<!-- Meta -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


@section('content')
    <form method="POST">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <a class="btn btn-success" href="/products/create">
                                {!! trans('hyplast.buttons.create-new3') !!}
                            </a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span id="card_title">
                                    {!! trans('hyplast.showing-all-products') !!}
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                                <table id="data-table" class="table table-striped table-bordered shadow-lg table-sm data-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>{!! trans('hyplast.machines-table.code') !!}</th>
                                            <th>{!! trans('hyplast.machines-table.name') !!}</th>
                                            <th>{!! trans('hyplast.machines-table.family') !!}</th>
                                            <th>{!! trans('hyplast.machines-table.category') !!}</th>
                                            <th>{!! trans('forms.create_product_label_material') !!}</th>
                                            <th>{!! trans('forms.create_product_label_color') !!}</th>
                                            <th>{!! trans('forms.create_product_label_machine') !!}</th>
                                            <th class="no-search no-sort">{!! trans('hyplast.machines-table.actions') !!}</th>
                                            <th class="no-search no-sort"></th>
                                            <th class="no-search no-sort"></th>
                                            <th class="no-search no-sort"></th>
                                            <th class="no-search no-sort"></th>
                                        </tr>
                                    </thead>
                                </table>
                            @include('modals.modal-machine')
                            @include('modals.modal-supplies')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


@endsection

@section('footer_scripts')
    @include('scripts.datatables.datatables-article')
    @include('scripts.save-modal-script')
    @if(config('hyplast.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
    @if(config('hyplast.enableSearch'))
        @include('scripts.searchs.search-products')
    @endif
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/es.js"></script>

    <script type="text/javascript">
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $( "#product2" ).select2({
            language: "es",
            dropdownParent: $('#modalSupplies .modal-body'),
            ajax: {
            url: "{{ url('autocomplete/products') }}",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                _token: CSRF_TOKEN,
                search: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
            }

        });


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
                        url: "{{url('/products/delete')}}'/" + id ,
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




        function modalMachine(id) {
            let resultsContainer = $('#modal_result');
            let noResulsHtml ='<tr>' +
                                '<td>{!! trans("hyplast.machines-products-no") !!}</td>' +
                                '<tr>';
            resultsContainer.html("");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type:'GET',
                url: "{{ url('productsmachines') }}/" + encodeURIComponent(id),
                data: {_token: CSRF_TOKEN},
                success: function (result) {
                    resultsContainer.html('');
                    if (result.length != 0) {
                        var i = 0;
                        $.each(result, function() {
                            resultsContainer.append(
                                    '<tr><td>' + result[i].code + '</td>' +
                                    '<td>' + result[i].name + '</td><tr>'
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
                    };
                },
            });

        };

        function modalSupplies(id) {
            let resultsContainer = $('#modal_result3');
            let title = $('#modal-title');
            let btn02 = $('#btn2');
            let noResulsHtml ='<tr>' +
                                '<td>{!! trans("hyplast.products-supplies-no") !!}</td>' +
                                '<tr>';
            resultsContainer.html("");
            title.html("");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type:'POST',
                url: "{{ url('productssupplies') }}/" + id,
                data: {_token: CSRF_TOKEN},
                success: function (result) {
                    resultsContainer.html("");
                    title.html("");
                    btn02.html("");
                    btn02.html('<button class="btn  btn-sm btn-primary btn-block" data-bs-target="#" data-bs-toggle="modal" data-toggle="tooltip" onclick="attachConfirmation(' + result.id + ')" type="submit"> {!! trans("hyplast.buttons.create-new6") !!}</button>');
                    title.html('<h5><strong>Producto: ' + result.name + '</strong></h5>');

                    if (result.supplies.length != 0) {
                        var i = 0;
                        $.each(result.supplies, function() {
                            resultsContainer.append(
                                '<tr>' +
                                    '<td>' + result.supplies[i].supplie + '</td>' +
                                    '<td>' + result.supplies[i].description + '</td>' +
                                    '<td><input type="text" name="quantity'+i + '" class="quantity" value='+result.supplies[i].pivot.quantity+'></td>' +
                                    '<td>'+result.supplies[i].unit_store+'</td>' +
                                    '<td><button class="btn  btn-sm btn-success btn-block" type="submit" ' +
                                    'onclick="savequantity(' + result.supplies[i].id + ',' + result.id + ',' + i +')"> {!! trans("hyplast.buttons.save") !!}</button></td>' +
                                    '<td><button class="btn  btn-sm btn-danger btn-block" type="submit" ' +
                                    'onclick="deleteattach(' + result.id + ',' + result.supplies[i].id + ')"> {!! trans("hyplast.buttons.delete") !!}</button></td>' +
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
                        title.html('<h5><strong>Producto: ' + result.name + '</strong></h5>');
                        btn02.html('<button class="btn  btn-sm btn-primary btn-block" data-bs-target="#" data-bs-toggle="modal" data-toggle="tooltip" onclick="attachConfirmation(' + result.id + ')" type="submit"> {!! trans("hyplast.buttons.create-new6") !!}</button>');
                    };
                },
            });

        };

        function savequantity(supplie,product,i) {
            event.preventDefault();
            var x =  document.getElementsByClassName("quantity")[i].value;
            swal({
                title: "Actualizar",
                text: "Se actualizará la Cantidad para el Insumo seleccionado!",
                type: "info",
                showCancelButton: !0,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'GET',
                        url: "{{url('products/savequantity')}}/" + supplie + "/" + product + "/" + x,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.success === true) {
                                swal("Done!", results.message, "success");
                                modalSupplies(id);

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


        function attachConfirmation(id) {
            event.preventDefault();
            swal({
                title: "Agregar?",
                text: "Se agregará el insumo al Producto!",
                type: "info",
                showCancelButton: !0,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    var product =  $("#product2 :selected").val();
                    var quantity = 1;
                    $.ajax({
                        type: 'GET',
                        url: "{{url('products/attachsupplie')}}/" + id + "/" + product + "/" + quantity,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.success === true) {
                                swal("Done!", results.message, "success");
                                modalSupplies(id);

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

        function deleteattach(product, supplie) {
            event.preventDefault();
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
                        type: 'GET',
                        url: "{{url('/products/detachsupplie')}}/" + product + "/" + supplie,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.success === true) {
                                swal("Done!", results.message, "success");
                                modalSupplies(product);
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


        function cleanModal()
        {
            let resultsContainer2 = $('#select-result');
            resultsContainer2.html('');
            Swal.fire({
                title: 'Error! Operación Cancelada',
                text: 'Cancelada la Operación, No se agregó Consumible!',
                icon: 'error',
                showCancelButton: false,
                showLoaderOnConfirm: false,

            });
        };


        cancelbutton1.click(function(e) {
            resultsContainer.html('');
        });

        cancelbutton2.click(function(e) {
            resultsContainer.html('');
        });
    </script>

@endsection
