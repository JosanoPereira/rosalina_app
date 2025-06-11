@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_novo_ticket"
                        id="btnModal">
                    Novo
                </button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Bilhetes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-striped" id="tabela" style="width:100%">
                            <thead class="thead-light">
                            <tr>
                                <th>Bilhete N</th>
                                <th>Rota</th>
                                <th>Passageiro</th>
                                <th>Valor</th>
                                <th>QTD</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bilhetes as $bilhete)
                                <tr>
                                    <td>{{ $bilhete->numero_bilhete }}</td>
                                    <td>{{ $bilhete->rota }}</td>
                                    <td>{{ $bilhete->nome }}</td>
                                    <td>{{ $bilhete->valor }}</td>
                                    <td>{{ $bilhete->qtd }}</td>
                                    <td>
                                        <a title="View" onclick="visualizar_bilhete({{$bilhete->id}})"
                                           class="btn btn-sm btn-info" href="#"> <i class="fas fa-eye"></i> </a>
                                        {{--<a title="Delete"
                                           onclick="eliminar('cursos/delete?id={{$curso->id}}')"
                                           class="btn btn-sm btn-danger" href="#"> <i class="fas fa-trash-alt"></i></a>--}}
                                        @if(!$bilhete->passageiros_id || auth()->user()->hasRole('Passageiro'))
                                            <a title="Edite" onclick="alterar_bilhete({{$bilhete->id}})"
                                               class="btn btn-sm btn-success" href="#"> <i class="fas fa-edit"></i></a>
                                        @endif
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
        <div class="modal fade" id="modal_novo_ticket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('bilhetes.store') }}" method="POST" id="form_modal_bilhetes">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" class="form-control form-control-sm  text-dark" name="id"
                                   id="id">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="form_viagens_id"
                                                aria-label="Floating label select example"
                                                name="viagens_id">
                                            <option value="" selected>Selecione</option>
                                            @foreach($viagens as $viagen)
                                                <option value="{{ $viagen->id }}"
                                                        {{ old('viagens_id') == $viagen->id ? 'selected' : '' }}>{{ $viagen->rota }}</option>
                                            @endforeach
                                        </select>
                                        <label for="form_viagens_id">Rota</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control form-control-sm"
                                               id="form_qtd"
                                               placeholder="Valor"
                                               name="qtd" value="{{ old('qtd') }}"/>
                                        <label for="form_qtd">Quantidade</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="form_classe"
                                                aria-label="Floating label select example"
                                                name="classe">
                                            <option
                                                    value="Economica"{{ old('classe') == 'Economica' ? 'selected' : '' }}>
                                                Economica
                                            </option>
                                            <option value="VIP"{{ old('classe') == 'VIP' ? 'selected' : '' }}>VIP
                                            </option>
                                            <option
                                                    value="Executiva"{{ old('Executiva') == 'Executiva' ? 'selected' : '' }}>
                                                Executiva
                                            </option>
                                        </select>
                                        <label for="form_classe">Classe</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="datetime-local" class="form-control form-control-sm"
                                               id="form_data_validade"
                                               placeholder=""
                                               name="data_validade" value="{{ old('data_validade') }}"/>
                                        <label for="form_data_validade">Data Validade</label>
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
        function visualizar_bilhete(id) {
            $.get({
                url: "{{route('bilhetes.show', ':id')}}".replace(':id', id),
                dataType: 'json',
                success: function (row) {
                    $('#id').val(id)
                    $('#form_viagens_id').val(row.viagens_id)
                    $('#form_classe').val(row.classe)
                    $('#form_qtd').val(row.qtd)
                    $('#form_data_validade').val(row.data_validade)

                    $('#modal_novo_ticket form input').attr('readOnly', true)
                    $('#modal_novo_ticket form select').attr('disabled', true)
                    $('#modal_novo_ticket form textarea').attr('disabled', true)
                    $('#modal_novo_ticket .modal-header').css('backgroundColor', '#ADB5BD')
                    $('#modal_novo_ticket .modal-title').html('Ticket (View)')
                    $('#modal_novo_ticket .modal-footer input').hide()
                    //$('#modal_form_cursos').trigger('reset')
                    $('#modal_novo_ticket').modal('show')
                }

            })
        }

        function alterar_bilhete(id) {
            $.get({
                url: "{{route('bilhetes.show', ':id')}}".replace(':id', id),
                dataType: 'json',
                success: function (row) {
                    $('#id').val(id)
                    $('#form_viagens_id').val(row.viagens_id)
                    $('#form_classe').val(row.classe)
                    $('#form_qtd').val(row.qtd)
                    $('#form_data_validade').val(row.data_validade)

                    $('#modal_novo_ticket form input').attr('readOnly', false)
                    $('#modal_novo_ticket form select').attr('disabled', false)
                    $('#modal_novo_ticket form textarea').attr('disabled', false)
                    $('#modal_novo_ticket .modal-header').css('backgroundColor', '#FFD600')
                    $('#modal_novo_ticket .modal-title').html('Ticket (Change)')
                    $('#modal_novo_ticket .modal-footer input').val('Update').show()
                    //$('#modal_form_cursos').trigger('reset')
                    $('#modal_novo_ticket').modal('show')
                }
            })
        }

        $(document).ready(function () {
            var dataTablecursos = $('#tabela').DataTable({
                'order': [0, 'desc'],
            })

            $('#btnModal').click(function () {
                $('#pk_cursos').attr('disabled', true)
                $('#modal_novo_ticket form input').attr('readOnly', false)
                $('#modal_novo_ticket form select').attr('disabled', false)
                $('#modal_novo_ticket .modal-header').css('backgroundColor', '#11CDEF')
                $('#modal_novo_ticket .modal-title').html('Ticket (New)')
                $('#modal_novo_ticket .modal-footer input').val('Salvar')
                $('#form_modal_bilhetes').trigger('reset')
                $('#modal_novo_ticket').modal('show')
            })
        });
    </script>
@endpush
