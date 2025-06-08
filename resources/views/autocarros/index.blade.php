@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="mb-3">
                <button type="button" id="btnModal" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal_novo_autocarro">
                    Novo
                </button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Autocarros</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-striped" id="tabela" style="width:100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Matricula</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Capacidade</th>
                                <th>Cor</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($autocarros as $autocarro)
                                <tr>
                                    <td>{{ $autocarro->id }}</td>
                                    <td>{{ $autocarro->matricula }}</td>
                                    <td>{{ $autocarro->marca }}</td>
                                    <td>{{ $autocarro->modelo }}</td>
                                    <td>{{ $autocarro->capacidade }}</td>
                                    <td>{{ $autocarro->cor }}</td>
                                    <td>
                                        <a title="View" onclick="visualizar_autocarro({{$autocarro->id}})"
                                           class="btn btn-sm btn-info" href="#"> <i class="fas fa-eye"></i> </a>
                                        {{--<a title="Delete"
                                           onclick="eliminar('cursos/delete?id={{$curso->id}}')"
                                           class="btn btn-sm btn-danger" href="#"> <i class="fas fa-trash-alt"></i></a>--}}
                                        <a title="Edite" onclick="alterar_autocarro({{$autocarro->id}})"
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
        <div class="modal fade" id="modal_novo_autocarro" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Dados</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('autocarros.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <input type="hidden" class="form-control form-control-sm  text-dark" name="id"
                                   id="id">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input required type="text" class="form-control form-control-sm"
                                               id="form_matricula"
                                               placeholder="Matricula"
                                               name="matricula" value="{{ old('matricula') }}"/>
                                        <label for="floatingInput">Matricula</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input required type="text" class="form-control form-control-sm"
                                               id="form_marca"
                                               placeholder="Marca"
                                               name="marca" value="{{ old('marca') }}"/>
                                        <label for="floatingInput1">Marca</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input required type="text" class="form-control form-control-sm"
                                               id="form_modelo"
                                               placeholder="Modelo"
                                               name="modelo" value="{{ old('modelo') }}"/>
                                        <label for="floatingInput2">Modelo</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input required type="number" class="form-control form-control-sm"
                                               id="form_capacidade"
                                               placeholder="Capacidade"
                                               name="capacidade" value="{{ old('capacidade') }}"/>
                                        <label for="floatingInput">Capacidade</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control form-control-sm" id="form_cor"
                                               placeholder="Cor"
                                               name="cor" value="{{ old('cor') }}"/>
                                        <label for="floatingInput1">Cor</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input required type="text" class="form-control form-control-sm"
                                               id="form_numero_chassi"
                                               placeholder="Chassi"
                                               name="numero_chassi" value="{{ old('numero_chassi') }}"/>
                                        <label for="floatingInput2">Nº Chassi</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input required type="text" class="form-control form-control-sm"
                                               id="form_numero_motor"
                                               placeholder="Motor"
                                               name="numero_motor" value="{{ old('numero_motor') }}"/>
                                        <label for="floatingInput">Nº Motor</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select form-select-sm" id="form_estado"
                                                name="estado" aria-label="Floating label select example">
                                            <option value="ativo" {{ old('estado') == 'ativo' ? 'selected' : '' }}>
                                                Activo
                                            </option>
                                            <option value="inativo" {{ old('estado') == 'inativo' ? 'selected' : '' }}>
                                                Inactivo
                                            </option>
                                        </select>
                                        <label for="floatingInput">Estado</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control form-control-sm"
                                               id="form_data_fabricacao"
                                               placeholder="Fabricação"
                                               name="data_fabricacao" value="{{ old('data_fabricacao') }}"/>
                                        <label for="floatingInput1">Data Fabricação</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control form-control-sm" id="form_data_registo"
                                               placeholder="Registo"
                                               name="data_registo" value="{{ old('data_registo') }}"/>
                                        <label for="floatingInput1">Data Registo</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea name="observacoes"
                                                  class="form-control form-control-sm form-control form-control-sm-sm"
                                                  placeholder="Observações" id="form_observacoes"></textarea>
                                        <label for="floatingInput">Observações</label>
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
        function visualizar_autocarro(id) {
            $.get({
                url: "{{route('autocarros.show', ':id')}}".replace(':id', id),
                dataType: 'json',
                success: function (row) {
                    $('#id').val(id)
                    $('#form_matricula').val(row.matricula)
                    $('#form_marca').val(row.marca)
                    $('#form_modelo').val(row.modelo)
                    $('#form_capacidade').val(row.capacidade)
                    $('#form_cor').val(row.cor)
                    $('#form_numero_chassi').val(row.numero_chassi)
                    $('#form_numero_motor').val(row.numero_motor)
                    $('#form_data_fabricacao').val(row.data_fabricacao)
                    $('#form_data_registo').val(row.data_registo)
                    $('#form_observacoes').val(row.observacoes)
                    $('#form_estado').val(row.estado)

                    $('#modal_novo_autocarro form input').attr('readOnly', true)
                    $('#modal_novo_autocarro form select').attr('disabled', true)
                    $('#modal_novo_autocarro form textarea').attr('disabled', true)
                    $('#modal_novo_autocarro .modal-header').css('backgroundColor', '#ADB5BD')
                    $('#modal_novo_autocarro .modal-title').html('Autocarro (View)')
                    $('#modal_novo_autocarro .modal-footer input').hide()
                    //$('#modal_form_cursos').trigger('reset')
                    $('#modal_novo_autocarro').modal('show')
                }

            })
        }

        function alterar_autocarro(id) {
            $.get({
                url: "{{route('autocarros.show', ':id')}}".replace(':id', id),
                dataType: 'json',
                success: function (row) {
                    $('#id').val(id)
                    $('#form_matricula').val(row.matricula)
                    $('#form_marca').val(row.marca)
                    $('#form_modelo').val(row.modelo)
                    $('#form_capacidade').val(row.capacidade)
                    $('#form_cor').val(row.cor)
                    $('#form_numero_chassi').val(row.numero_chassi)
                    $('#form_numero_motor').val(row.numero_motor)
                    $('#form_data_fabricacao').val(row.data_fabricacao)
                    $('#form_data_registo').val(row.data_registo)
                    $('#form_observacoes').val(row.observacoes)
                    $('#form_estado').val(row.estado)

                    $('#pk_cursos').val(row.id).attr('disabled', false)

                    $('#modal_novo_autocarro form input').attr('readOnly', false)
                    $('#modal_novo_autocarro form select').attr('disabled', false)
                    $('#modal_novo_autocarro form textarea').attr('disabled', false)
                    $('#modal_novo_autocarro .modal-header').css('backgroundColor', '#FFD600')
                    $('#modal_novo_autocarro .modal-title').html('Autocarro (Change)')
                    $('#modal_novo_autocarro .modal-footer input').val('Update').show()
                    //$('#modal_form_cursos').trigger('reset')
                    $('#modal_novo_autocarro').modal('show')
                }

            })
        }

        $(document).ready(function () {
            var dataTablecursos = $('#tabela').DataTable({
                'order': [0, 'desc'],
            })

            $('#btnModal').click(function () {
                $('#pk_cursos').attr('disabled', true)
                $('#modal_novo_autocarro form input').attr('readOnly', false)
                $('#modal_novo_autocarro form select').attr('disabled', false)
                $('#modal_novo_autocarro .modal-header').css('backgroundColor', '#11CDEF')
                $('#modal_novo_autocarro .modal-title').html('Autocarro (New)')
                $('#modal_novo_autocarro .modal-footer input').val('Salvar')
                $('#modal_form_cursos').trigger('reset')
                $('#modal_novo_autocarro').modal('show')
            })
        });
    </script>
@endpush
