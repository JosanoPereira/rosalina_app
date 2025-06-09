@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="mb-3">
                <a href="{{ route('rotas.create') }}" class="btn btn-secondary">
                    Nova Rota
                </a>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Rotas</h5>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 400px;"></div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="tabela" style="width:100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Origem</th>
                                <th>Destino</th>
                                <th>Distância (Km)</th>
                                <th>Tempo Estimado (min)</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rotas as $rota)
                                <tr>
                                    <td>{{ $rota->id }}</td>
                                    <td>{{ $rota->origem }}</td>
                                    <td>{{ $rota->destino }}</td>
                                    <td>{{ $rota->distancia }}</td>
                                    <td>{{ $rota->tempo_estimado }}</td>
                                    <td>
                                        <a title="View" href="{{ route('rotas.show', $rota) }}"
                                           class="btn btn-sm btn-info"> <i class="fas fa-eye"></i> </a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-8.8383, 13.2344], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            // attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var rotas = @json($rotas);
        rotas.forEach(function (rota) {
            if (rota.waypoints) {
                var pontos = JSON.parse(rota.waypoints);
                var latlngs = pontos.map(function (p) {
                    return [p.lat, p.lng];
                });
                L.polyline(latlngs, {color: 'blue'}).addTo(map)
                    .bindPopup(rota.origem + " → " + rota.destino);
            }
        });

        $(document).ready(function () {
            var dataTablecursos = $('#tabela').DataTable({
                'order': [0, 'desc'],
            })
        });
    </script>
@endpush
