@extends('adminlte::page')


@section('template_title')
  {!! trans('hyplast.showing-user', ['name' => $machine->name]) !!}
@endsection

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-lg-10 offset-lg-1">

        <div class="card">

          <div class="card-header text-white @if ($machine->status == 1) bg-success @else bg-danger @endif">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              {!! trans('hyplast.showing-machine-title', ['name' => $machine->name]) !!}
              <div class="pull-right">
                <a href="{{ route('machines') }}" class="btn btn-light btn-sm float-right" data-toggle="tooltip" data-placement="left" title="{{ trans('hyplast.tooltips.back-machines') }}">
                    <i class="fa fa-fw fa-reply-all" aria-hidden="true"></i>
                    {!! trans('hyplast.buttons.back-to-machines') !!}
                </a>
            </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">

                  <div class="col">
                <h4 class="text-muted margin-top-sm-1 text-center text-left-tablet">
                  {{ $machine->code }}
                </h4>
                <p class="text-center text-left-tablet">
                  <strong>
                    {{ $machine->name }}
                  </strong>
                </p>
                <br />

              </div>
            </div>
            <br />
            <div class="clearfix"></div>
            <div class="border-bottom"></div>

            @if ($machine->code)

              <div class="col-sm-5 col-6 text-larger">
                <strong>
                  {{ trans('hyplast.labelmachineCode') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $machine->code }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif

            @if ($machine->name)

            <div class="col-sm-5 col-6 text-larger">
              <strong>
                {{ trans('hyplast.labelname') }}
              </strong>
            </div>

            <div class="col-sm-7">
                {{ $machine->name }}
              </div>

            <div class="clearfix"></div>
            <div class="border-bottom"></div>

            @endif

            @if ($machine->location->name)

              <div class="col-sm-5 col-6 text-larger">
                <strong>
                  {{ trans('hyplast.labelLocation') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $machine->location->name }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif


            <div class="col-sm-5 col-6 text-larger">
              <strong>
                {{ trans('hyplast.labelCategory_Machine') }}
              </strong>
            </div>

            <div class="col-sm-7">
                @php $badgeClass = 'primary' @endphp
                <span class="badge badge-{{$badgeClass}}">{{ $machine->category_machine->name }}</span>
            </div>

            <div class="clearfix"></div>
            <div class="border-bottom"></div>

            <div class="col-sm-5 col-6 text-larger">
              <strong>
                {{ trans('hyplast.labelStatus') }}
              </strong>
            </div>

            <div class="col-sm-7">
              @if ($machine->status == 1)
                <span class="badge badge-success">
                  Activada
                </span>
              @else
                <span class="badge badge-danger">
                  Apagada
                </span>
              @endif
            </div>

            <div class="clearfix"></div>
            <div class="border-bottom"></div>

            @if ($machine->created_at)

              <div class="col-sm-5 col-6 text-larger">
                <strong>
                  {{ trans('hyplast.labelCreatedAt') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $machine->created_at }}
              </div>

              <div class="clearfix"></div>
              <div class="border-bottom"></div>

            @endif

            @if ($machine->updated_at)

              <div class="col-sm-5 col-6 text-larger">
                <strong>
                  {{ trans('hyplast.labelUpdatedAt') }}
                </strong>
              </div>

              <div class="col-sm-7">
                {{ $machine->updated_at }}
              </div>
            @endif
            <br />

            <div class="container">

                <div class="row">

                        <div class="card">
                            <div class="card-header">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <strong>
                                    {!! trans('hyplast.products-machines') !!}
                                    </strong>
                                </div>
                            </div>
                            <div class="card-body">

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
                        </div>

                </div>
            </div>

            @if ($machine->id)

                <div class="row align-items-end">
                    <div class="col">
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <a class="btn btn-sm btn-info btn-block" href="{{ URL::to('machines/' . $machine->id . '/edit') }}" data-toggle="tooltip" title="Edit">
                                {!! trans('hyplast.buttons.edit') !!}
                            </a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <button class="btn  btn-sm btn-danger btn-block" type="submit" onclick="deleteConfirmation({{$machine->id}})"> {!! trans('hyplast.buttons.delete') !!}</button>
                        </div>
                    </div>
                    <div class="col">

                    </div>
                </div>



            @endif
    </div>
</div>

      </div>
    </div>

  </div>
  </div>

@endsection

@section('footer_scripts')

  @if(config('machine.tooltipsEnabled'))
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
                    url: "{{url('/machines/delete')}}/" + id,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        if (results.success === true) {
                            swal("Done!", results.message, "success");
                            window.location = "/machines";
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
