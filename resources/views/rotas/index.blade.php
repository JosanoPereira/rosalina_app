@extends('layouts.app')

@section('content')
    <h2>Rotas</h2>
    <div id="map" style="height: 400px;"></div>
    <ul>
        @foreach($rotas as $rota)
            <li>
                <a href="{{ route('rotas.show', $rota) }}">{{ $rota->origem }} → {{ $rota->destino }}</a>
            </li>
        @endforeach
    </ul>
@endsection

@push('js')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-8.8383, 13.2344], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            // attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var rotas = @json($rotas);
        rotas.forEach(function(rota) {
            if (rota.waypoints) {
                var pontos = JSON.parse(rota.waypoints);
                var latlngs = pontos.map(function(p) { return [p.lat, p.lng]; });
                L.polyline(latlngs, {color: 'blue'}).addTo(map)
                    .bindPopup(rota.origem + " → " + rota.destino);
            }
        });
    </script>
@endpush
