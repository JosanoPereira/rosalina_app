@extends('layouts.app')

@section('content')
    <h2>Editar Rota</h2>
    <form method="POST" action="{{ route('rotas.update', $rota) }}">
        @csrf
        @method('PUT')
        <input type="text" name="origem" value="{{ old('origem', $rota->origem) }}" required>
        <input type="text" name="destino" value="{{ old('destino', $rota->destino) }}" required>
        <input type="number" step="0.01" name="distancia" value="{{ old('distancia', $rota->distancia) }}" required>
        <input type="text" name="tempo_estimado" value="{{ old('tempo_estimado', $rota->tempo_estimado) }}" required>
        <input type="hidden" name="waypoints" id="waypoints" value="{{ old('waypoints', json_encode($rota->waypoints)) }}">
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('rotas.index') }}" class="btn btn-secondary">Cancelar</a>
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

        var waypoints = {!! old('waypoints', json_encode($rota->waypoints)) !!} || [];
        var polyline = null;

        function drawRoute() {
            if (polyline) map.removeLayer(polyline);
            if (waypoints.length > 0) {
                var latlngs = waypoints.map(function(p) { return [p.lat, p.lng]; });
                polyline = L.polyline(latlngs, {color: 'red'}).addTo(map);
                map.fitBounds(polyline.getBounds());
            }
        }

        drawRoute();

        map.on('click', function(e) {
            waypoints.push({lat: e.latlng.lat, lng: e.latlng.lng});
            document.getElementById('waypoints').value = JSON.stringify(waypoints);
            drawRoute();
        });

        // Opcional: botão para limpar rota
        // Adicione um botão e o JS para limpar o array waypoints e o polyline
    </script>
@endpush
