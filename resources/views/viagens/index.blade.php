@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="mb-3">
                <button type="button" id="btnModal" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal_novo_viagen">
                    Novo
                </button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Viagens</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-striped" id="tabela" style="width:100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Rota</th>
                                <th>Motorista</th>
                                <th>Autocarro</th>
                                <th>Hora Partida</th>
                                <th>Hora Chegada</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($viagens as $viagen)
                                <tr>
                                    <td>{{ $viagen->id }}</td>
                                    <td>{{ $viagen->rota }}</td>
                                    <td>{{ $viagen->nome }}</td>
                                    <td>{{ $viagen->autocarro }}</td>
                                    <td>{{ $viagen->hora_partida }}</td>
                                    <td>{{ $viagen->hora_chegada }}</td>
                                    <td>
                                        <a title="View" onclick="visualizar_viagen({{$viagen->id}})"
                                           class="btn btn-sm btn-info" href="#"> <i class="fas fa-eye"></i> </a>
                                        {{--<a title="Delete"
                                           onclick="eliminar('cursos/delete?id={{$curso->id}}')"
                                           class="btn btn-sm btn-danger" href="#"> <i class="fas fa-trash-alt"></i></a>--}}
                                        <a title="Edite" onclick="alterar_viagen({{$viagen->id}})"
                                           class="btn btn-sm btn-success" href="#"> <i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal_novo_viagen" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Dados</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('viagens.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <input type="hidden" class="form-control form-control-sm  text-dark" name="id"
                                   id="id">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="form_rotas_id"
                                                aria-label="Floating label select example"
                                                name="rotas_id">
                                            <option value="" selected>Selecione</option>
                                            @foreach($rotas as $rota)
                                                <option value="{{ $rota->id }}"
                                                    {{ old('rotas_id') == $rota->id ? 'selected' : '' }}>{{ $rota->origem. ' '.$rota->destino  }}</option>
                                            @endforeach
                                        </select>
                                        <label for="form_rotas_id">Rota</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="form_motoristas_id"
                                                aria-label="Floating label select example"
                                                name="motoristas_id">
                                            <option value="" selected>Selecione</option>
                                            @foreach($motoristas as $motorista)
                                                <option value="{{ $motorista->id }}"
                                                    {{ old('motoristas_id') == $motorista->id ? 'selected' : '' }}>{{ $motorista->nome }}</option>
                                            @endforeach
                                        </select>
                                        <label for="form_motoristas_id">Motorista</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="form_autocarros_id"
                                                aria-label="Floating label select example"
                                                name="autocarros_id">
                                            <option value="" selected>Selecione</option>
                                            @foreach($autocarros as $autocarro)
                                                <option value="{{ $autocarro->id }}"
                                                    {{ old('autocarros_id') == $autocarro->id ? 'selected' : '' }}>{{ $autocarro->marca.'/'.$autocarro->modelo.' - '.$autocarro->matricula }}</option>
                                            @endforeach
                                        </select>
                                        <label for="form_autocarros_id">Autocarro</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="time" class="form-control form-control-sm"
                                               id="form_hora_partida"
                                               placeholder=""
                                               name="hora_partida" value="{{ old('hora_partida') }}"/>
                                        <label for="form_hora_partida">Hora Partida</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="time" class="form-control form-control-sm" id="form_hora_chegada"
                                               placeholder=""
                                               name="hora_chegada" value="{{ old('hora_chegada') }}"/>
                                        <label for="form_hora_chegada">Hora Chegada</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <input type="submit" class="btn btn-success" value="Salvar">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function visualizar_viagen(id) {
            $.get({
                url: "{{route('viagens.show', ':id')}}".replace(':id', id),
                dataType: 'json',
                success: function (row) {
                    $('#id').val(id)
                    $('#form_rotas_id').val(row.rotas_id)
                    $('#form_motoristas_id').val(row.motoristas_id)
                    $('#form_autocarros_id').val(row.autocarros_id)
                    $('#form_hora_partida').val(row.hora_partida)
                    $('#form_hora_chegada').val(row.hora_chegada)

                    $('#modal_novo_viagen form input').attr('readOnly', true)
                    $('#modal_novo_viagen form select').attr('disabled', true)
                    $('#modal_novo_viagen form textarea').attr('disabled', true)
                    $('#modal_novo_viagen .modal-header').css('backgroundColor', '#ADB5BD')
                    $('#modal_novo_viagen .modal-title').html('Viagem (View)')
                    $('#modal_novo_viagen .modal-footer input').hide()
                    //$('#modal_form_cursos').trigger('reset')
                    $('#modal_novo_viagen').modal('show')
                }

            })
        }

        function alterar_viagen(id) {
            $.get({
                url: "{{route('viagens.show', ':id')}}".replace(':id', id),
                dataType: 'json',
                success: function (row) {
                    $('#id').val(id)
                    $('#form_rotas_id').val(row.rotas_id)
                    $('#form_motoristas_id').val(row.motoristas_id)
                    $('#form_autocarros_id').val(row.autocarros_id)
                    $('#form_hora_partida').val(row.hora_partida)
                    $('#form_hora_chegada').val(row.hora_chegada)

                    $('#modal_novo_viagen form input').attr('readOnly', false)
                    $('#modal_novo_viagen form select').attr('disabled', false)
                    $('#modal_novo_viagen form textarea').attr('disabled', false)
                    $('#modal_novo_viagen .modal-header').css('backgroundColor', '#FFD600')
                    $('#modal_novo_viagen .modal-title').html('Viagem (Change)')
                    $('#modal_novo_viagen .modal-footer input').val('Update').show()
                    //$('#modal_form_cursos').trigger('reset')
                    $('#modal_novo_viagen').modal('show')
                }

            })
        }

        $(document).ready(function () {
            var dataTablecursos = $('#tabela').DataTable({
                'order': [0, 'desc'],
            })

            $('#btnModal').click(function () {
                $('#pk_cursos').attr('disabled', true)
                $('#modal_novo_viagen form input').attr('readOnly', false)
                $('#modal_novo_viagen form select').attr('disabled', false)
                $('#modal_novo_viagen .modal-header').css('backgroundColor', '#11CDEF')
                $('#modal_novo_viagen .modal-title').html('Viagem (New)')
                $('#modal_novo_viagen .modal-footer input').val('Salvar')
                $('#modal_form_cursos').trigger('reset')
                $('#modal_novo_viagen').modal('show')
            })
        });
    </script>
@endpush
