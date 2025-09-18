@extends('layouts.template')

@section('content')
    <div class="container">
        <h2>Attribuer des éléments au projet : {{ $projet->refprojet }}</h2>

        <form method="POST" action="{{ route('projets.assign', $projet->id) }}">
            @csrf

            <h4>Actifs</h4>
            @foreach($actifs as $actif)
                <div class="form-group">
                    <label>{{ $actif->nom }}</label>
                    <input type="number" name="actifs[{{ $actif->id }}][quantite]" min="0"
                        value="{{ $projet->actifs->find($actif->id)->pivot->quantite ?? 0 }}" class="form-control" />
                </div>
            @endforeach

            <h4>Consommables</h4>
            @foreach($consommables as $consommable)
                <div class="form-group">
                    <label>{{ $consommable->nom }}</label>
                    <input type="number" name="consommables[{{ $consommable->id }}][quantite]" min="0"
                        value="{{ $projet->consommables->find($consommable->id)->pivot->quantite ?? 0 }}"
                        class="form-control" />
                </div>
            @endforeach

            <h4>Composants</h4>
            @foreach($composants as $composant)
                <div class="form-group">
                    <label>{{ $composant->nom }}</label>
                    <input type="number" name="composants[{{ $composant->id }}][quantite]" min="0"
                        value="{{ $projet->composants->find($composant->id)->pivot->quantite ?? 0 }}" class="form-control" />
                </div>
            @endforeach

            <h4>Kits</h4>
            @foreach($kits as $kit)
                <div class="form-group">
                    <label>{{ $kit->nom }}</label>
                    <input type="number" name="kits[{{ $kit->id }}][quantite]" min="0"
                        value="{{ $projet->kits->find($kit->id)->pivot->quantite ?? 0 }}" class="form-control" />
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('projets.index') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection
