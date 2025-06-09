@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="mb-3">
                <button type="button" id="btnModal" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal_novo_motorista">
                    Novo
                </button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Motoristas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-striped" id="tabela" style="width:100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Telefone</th>
                                <th>BI</th>
                                <th>Nº Carta</th>
                                <th>Categoria</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($motoristas as $motorista)
                                <tr>
                                    <td>{{ $motorista->id }}</td>
                                    <td>{{ $motorista->nome }}</td>
                                    <td>{{ $motorista->telefone }}</td>
                                    <td>{{ $motorista->bi }}</td>
                                    <td>{{ $motorista->numero_carta }}</td>
                                    <td>{{ $motorista->categoria }}</td>
                                    <td>
                                        <a title="View" onclick="visualizar_motorista({{$motorista->id}})"
                                           class="btn btn-sm btn-info" href="#"> <i class="fas fa-eye"></i> </a>
                                        {{--<a title="Delete"
                                           onclick="eliminar('cursos/delete?id={{$curso->id}}')"
                                           class="btn btn-sm btn-danger" href="#"> <i class="fas fa-trash-alt"></i></a>--}}
                                        <a title="Edite" onclick="alterar_motorista({{$motorista->id}})"
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
        <div class="modal fade" id="modal_novo_motorista" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Dados</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('motoristas.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control form-control-sm  text-dark" name="id" id="id">
                            <input type="hidden" class="form-control form-control-sm  text-dark" name="pessoas_id"
                                   id="pessoas_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control form-control-sm" id="form_nome"
                                               placeholder="Nome"
                                               name="nome" value="{{ old('nome') }}"/>
                                        <label for="floatingInput">Primeiro Nome</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control form-control-sm" id="form_apelido"
                                               placeholder="Apelido"
                                               name="apelido" value="{{ old('apelido') }}"/>
                                        <label for="floatingInput1">Apelido</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control form-control-sm" id="form_telefone"
                                               placeholder="Telefone"
                                               name="telefone" value="{{ old('telefone') }}"/>
                                        <label for="floatingInput1">Telefone</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control form-control-sm" id="form_bi"
                                               placeholder="NIF"
                                               name="bi" value="{{ old('bi') }}"/>
                                        <label for="floatingInput1">BI</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="form_generos_id"
                                                aria-label="Floating label select example"
                                                name="generos_id">
                                            <option value="" selected>Selecione o sexo</option>
                                            @foreach($generos as $genero)
                                                <option value="{{ $genero->id }}"
                                                    {{ old('generos_id') == $genero->id ? 'selected' : '' }}>{{ $genero->genero }}</option>
                                            @endforeach
                                        </select>
                                        <label for="form_generos_id">Genero</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control form-control-sm" id="form_nascimento"
                                               placeholder=""
                                               name="nascimento" value="{{ old('nascimento') }}"/>
                                        <label for="floatingInput1">Data Nascimento</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control form-control-sm" id="form_numero_carta"
                                               placeholder="Número da Carta"
                                               name="numero_carta" value="{{ old('numero_carta') }}"/>
                                        <label for="form_numero_carta">Nº da Carta</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control form-control-sm" id="form_data_emissao"
                                               placeholder=""
                                               name="data_emissao" value="{{ old('data_emissao') }}"/>
                                        <label for="form_data_emissao">Data Emissão</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control form-control-sm" id="form_data_validade"
                                               placeholder=""
                                               name="data_validade" value="{{ old('data_validade') }}"/>
                                        <label for="form_data_validade">Data Validade</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <select class="form-select form-select-sm" id="form_categoria"
                                                name="categoria" aria-label="Floating label select example">
                                            <option value="amador" {{ old('categoria') == 'amador' ? 'selected' : '' }}>
                                                Amador
                                            </option>
                                            <option value="profissional" {{ old('categoria') == 'profissional' ? 'selected' : '' }}>
                                                Profissional
                                            </option>
                                            <option value="pesado" {{ old('categoria') == 'pesado' ? 'selected' : '' }}>
                                                Pesado
                                            </option>
                                        </select>
                                        <label for="form_categoria">Categoria</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <select class="form-select form-select-sm" id="form_estado"
                                                name="estado" aria-label="Floating label select example">
                                            <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>
                                                Activo
                                            </option>
                                            <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>
                                                Inactivo
                                            </option>
                                        </select>
                                        <label for="form_estado">Estado</label>
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
        function visualizar_motorista(id) {
            $.get({
                url: "{{route('motoristas.show', ':id')}}".replace(':id', id),
                dataType: 'json',
                success: function (row) {
                    $('#id').val(row.id);
                    $('#pessoas_id').val(row.pessoas_id);
                    $('#form_nome').val(row.nome);
                    $('#form_apelido').val(row.apelido);
                    $('#form_telefone').val(row.telefone);
                    $('#form_bi').val(row.bi);
                    $('#form_generos_id').val(row.generos_id);
                    $('#form_nascimento').val(row.nascimento);
                    $('#form_numero_carta').val(row.numero_carta);
                    $('#form_data_emissao').val(row.data_emissao);
                    $('#form_data_validade').val(row.data_validade);
                    $('#form_categoria').val(row.categoria);
                    $('#form_estado').val(row.estado);

                    $('#modal_novo_motorista form input').attr('readOnly', true)
                    $('#modal_novo_motorista form select').attr('disabled', true)
                    $('#modal_novo_motorista form textarea').attr('disabled', true)
                    $('#modal_novo_motorista .modal-header').css('backgroundColor', '#ADB5BD')
                    $('#modal_novo_motorista .modal-title').html('Motorista (View)')
                    $('#modal_novo_motorista .modal-footer input').hide()
                    //$('#modal_form_cursos').trigger('reset')
                    $('#modal_novo_motorista').modal('show')
                }

            })
        }

        function alterar_motorista(id) {
            $.get({
                url: "{{route('motoristas.show', ':id')}}".replace(':id', id),
                dataType: 'json',
                success: function (row) {
                    $('#id').val(row.id);
                    $('#pessoas_id').val(row.pessoas_id);
                    $('#form_nome').val(row.nome);
                    $('#form_apelido').val(row.apelido);
                    $('#form_telefone').val(row.telefone);
                    $('#form_bi').val(row.bi);
                    $('#form_generos_id').val(row.generos_id);
                    $('#form_nascimento').val(row.nascimento);
                    $('#form_numero_carta').val(row.numero_carta);
                    $('#form_data_emissao').val(row.data_emissao);
                    $('#form_data_validade').val(row.data_validade);
                    $('#form_categoria').val(row.categoria);
                    $('#form_estado').val(row.estado);



                    $('#modal_novo_motorista form input').attr('readOnly', false)
                    $('#modal_novo_motorista form select').attr('disabled', false)
                    $('#modal_novo_motorista form textarea').attr('disabled', false)
                    $('#modal_novo_motorista .modal-header').css('backgroundColor', '#FFD600')
                    $('#modal_novo_motorista .modal-title').html('Motorista (Change)')
                    $('#modal_novo_motorista .modal-footer input').val('Update').show()
                    //$('#modal_form_cursos').trigger('reset')
                    $('#modal_novo_motorista').modal('show')
                }

            })
        }

        $(document).ready(function () {
            var dataTablecursos = $('#tabela').DataTable({
                'order': [0, 'desc'],
            })

            $('#btnModal').click(function () {
                $('#pk_cursos').attr('disabled', true)
                $('#modal_novo_motorista form input').attr('readOnly', false)
                $('#modal_novo_motorista form select').attr('disabled', false)
                $('#modal_novo_motorista .modal-header').css('backgroundColor', '#11CDEF')
                $('#modal_novo_motorista .modal-title').html('Motorista (New)')
                $('#modal_novo_motorista .modal-footer input').val('Salvar')
                $('#modal_form_cursos').trigger('reset')
                $('#modal_novo_motorista').modal('show')
            })
        });
    </script>
@endpush
