@extends('layouts.app')

@section('content')
    <h2>Nova Rota</h2>
    <form method="POST" action="{{ route('rotas.store') }}">
        @csrf
        <input type="text" name="origem" placeholder="Origem" required>
        <input type="text" name="destino" placeholder="Destino" required>
        <input type="number" step="0.01" name="distancia" placeholder="Distância (km)" required>
        <input type="text" name="tempo_estimado" placeholder="Tempo Estimado" required>
        <input type="hidden" name="waypoints" id="waypoints">
        <button type="submit">Salvar</button>
    </form>
    <div id="map" style="height: 400px;"></div>
@endsection

@push('js')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-8.8383, 13.2344], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var waypoints = [];
        var polyline = null;

        map.on('click', function(e) {
            waypoints.push({lat: e.latlng.lat, lng: e.latlng.lng});
            if (polyline) map.removeLayer(polyline);
            polyline = L.polyline(waypoints.map(function(p) { return [p.lat, p.lng]; }), {color: 'red'}).addTo(map);
            document.getElementById('waypoints').value = JSON.stringify(waypoints);
        });
    </script>
@endpush
