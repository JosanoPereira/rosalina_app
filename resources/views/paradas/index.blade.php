@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="mb-3">
                <button type="button" id="btnModal" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal_novo_parada">
                    Novo
                </button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Paradas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-striped" id="tabela" style="width:100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Parada</th>
                                <th>Ordem</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($paradas as $parada)
                                <tr>
                                    <td>{{ $parada->id }}</td>
                                    <td>{{ $parada->nome }}</td>
                                    <td>{{ $parada->ordem }}</td>
                                    <td>
                                        <a title="View" onclick="visualizar_parada({{$parada->id}})"
                                           class="btn btn-sm btn-info" href="#"> <i class="fas fa-eye"></i> </a>
                                        {{--<a title="Delete"
                                           onclick="eliminar('cursos/delete?id={{$curso->id}}')"
                                           class="btn btn-sm btn-danger" href="#"> <i class="fas fa-trash-alt"></i></a>--}}
                                        <a title="Edite" onclick="alterar_parada({{$parada->id}})"
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
        <div class="modal fade" id="modal_novo_parada" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Dados</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('paradas.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control form-control-sm  text-dark" name="id"
                                   id="id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input required type="text" class="form-control form-control-sm"
                                               id="form_nome"
                                               placeholder="Parada"
                                               name="nome" value="{{ old('nome') }}"/>
                                        <label for="form_nome">Parada</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input required type="number" class="form-control form-control-sm"
                                               id="form_ordem"
                                               placeholder="Marca"
                                               name="ordem" value="{{ old('ordem') }}"/>
                                        <label for="form_ordem">Ordem</label>
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
        function visualizar_parada(id) {
            $.get({
                url: "{{route('paradas.show', ':id')}}".replace(':id', id),
                dataType: 'json',
                success: function (row) {
                    $('#id').val(id)
                    $('#form_nome').val(row.nome)
                    $('#form_ordem').val(row.ordem)

                    $('#modal_novo_parada form input').attr('readOnly', true)
                    $('#modal_novo_parada form select').attr('disabled', true)
                    $('#modal_novo_parada form textarea').attr('disabled', true)
                    $('#modal_novo_parada .modal-header').css('backgroundColor', '#ADB5BD')
                    $('#modal_novo_parada .modal-title').html('Parada (View)')
                    $('#modal_novo_parada .modal-footer input').hide()
                    //$('#modal_form_cursos').trigger('reset')
                    $('#modal_novo_parada').modal('show')
                }

            })
        }

        function alterar_parada(id) {
            $.get({
                url: "{{route('paradas.show', ':id')}}".replace(':id', id),
                dataType: 'json',
                success: function (row) {
                    $('#id').val(id)
                    $('#form_nome').val(row.nome)
                    $('#form_ordem').val(row.ordem)


                    $('#modal_novo_parada form input').attr('readOnly', false)
                    $('#modal_novo_parada form select').attr('disabled', false)
                    $('#modal_novo_parada form textarea').attr('disabled', false)
                    $('#modal_novo_parada .modal-header').css('backgroundColor', '#FFD600')
                    $('#modal_novo_parada .modal-title').html('Parada (Change)')
                    $('#modal_novo_parada .modal-footer input').val('Update').show()
                    //$('#modal_form_cursos').trigger('reset')
                    $('#modal_novo_parada').modal('show')
                }

            })
        }

        $(document).ready(function () {
            var dataTablecursos = $('#tabela').DataTable({
                'order': [0, 'desc'],
            })

            $('#btnModal').click(function () {
                $('#pk_cursos').attr('disabled', true)
                $('#modal_novo_parada form input').attr('readOnly', false)
                $('#modal_novo_parada form select').attr('disabled', false)
                $('#modal_novo_parada .modal-header').css('backgroundColor', '#11CDEF')
                $('#modal_novo_parada .modal-title').html('Parada (New)')
                $('#modal_novo_parada .modal-footer input').val('Salvar')
                $('#modal_form_cursos').trigger('reset')
                $('#modal_novo_parada').modal('show')
            })
        });
    </script>
@endpush
