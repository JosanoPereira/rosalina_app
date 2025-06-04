@extends('layouts.app')

@section('content')
    <h2>Rota: {{ $rota->origem }} → {{ $rota->destino }}</h2>
    <p><strong>Distância:</strong> {{ $rota->distancia }} km</p>
    <p><strong>Tempo estimado:</strong> {{ $rota->tempo_estimado }}</p>
    <div id="map" style="height: 400px;"></div>
    <a href="{{ route('rotas.edit', $rota) }}" class="btn btn-warning">Editar</a>
    <form action="{{ route('rotas.destroy', $rota) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" onclick="return confirm('Tem certeza que deseja deletar?')">Deletar</button>
    </form>
    <a href="{{ route('rotas.index') }}" class="btn btn-secondary">Voltar</a>
@endsection

@push('js')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-8.8383, 13.2344], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        @if($rota->waypoints)
        var waypoints = @json($rota->waypoints);
        var latlngs = waypoints.map(function(p) { return [p.lat, p.lng]; });
        var polyline = L.polyline(latlngs, {color: 'blue'}).addTo(map);
        map.fitBounds(polyline.getBounds());
        @endif
    </script>
@endpush
