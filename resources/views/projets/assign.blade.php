@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Ressources du Projet : {{ $projet->refprojet }}</h1>
        <a href="{{ route('projets.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
        </a>
    </div>

    <div class="row">
        <!-- Affectation Actifs -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Affecter un Actif (Matériel/Engin)</h6>
                </div>
                <div class="card-body">
                    <p class="mb-4">Sélectionnez un actif disponible pour l'affecter exclusivement à ce projet.</p>
                    
                    <form method="POST" action="{{ route('projets.assignActif', $projet->id) }}">
                        @csrf
                        <div class="form-group">
                            <label for="actif_id">Actif Disponible</label>
                            <select name="actif_id" id="actif_id" class="form-control select2" required>
                                <option value="">-- Choisir un actif --</option>
                                @foreach($actifs as $actif)
                                    <option value="{{ $actif->id }}">
                                        {{ $actif->nom_actif }} ({{ $actif->inventaire }}) - {{ $actif->statut }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-link"></i> Affecter au projet
                        </button>
                    </form>
                </div>
            </div>

            <!-- Liste des Actifs du Projet -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Actifs sur ce projet</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Inventaire</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projet->actifs as $pActif)
                                <tr>
                                    <td>{{ $pActif->nom_actif }}</td>
                                    <td>{{ $pActif->inventaire }}</td>
                                    <td>
                                        <!-- Bouton pour libérer l'actif (à implémenter si besoin) -->
                                        <button class="btn btn-danger btn-sm" disabled title="Fonctionnalité à venir">Libérer</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Aucun actif affecté.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sortie de Stock (Consommables) -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Sortie de Stock (Consommables/Composants)</h6>
                </div>
                <div class="card-body">
                    <p class="mb-4">Attribuer des consommables. <strong>Attention :</strong> Cela décrémente le stock immédiatement.</p>

                    <form method="POST" action="{{ route('projets.assignItem', $projet->id) }}">
                        @csrf
                        
                        <div class="form-group">
                            <label for="type_item">Type d'élément</label>
                            <select name="type" id="type_item" class="form-control" onchange="updateItemsList()" required>
                                <option value="consommable">Consommable</option>
                                <option value="composant">Composant</option>
                                <option value="kit">Kit</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="item_id">Article</label>
                            <select name="item_id" id="item_id" class="form-control select2" required>
                                <!-- Rempli par JS -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quantite">Quantité à sortir</label>
                            <input type="number" name="quantite" id="quantite" class="form-control" min="1" value="1" required>
                        </div>

                        <button type="submit" class="btn btn-warning btn-block">
                            <i class="fas fa-box-open"></i> Valider la sortie
                        </button>
                    </form>
                </div>
            </div>

             <!-- Liste des Consommables du Projet -->
             <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Consommables utilisés</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($projet->consommables as $pConso)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $pConso->libelle ?? $pConso->nom }}
                            <span class="badge badge-primary badge-pill">{{ $pConso->pivot->quantite }}</span>
                        </li>
                        @endforeach
                        @foreach($projet->composants as $pComp)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $pComp->libelle ?? $pComp->nom }} (Comp.)
                            <span class="badge badge-secondary badge-pill">{{ $pComp->pivot->quantite }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Données préchargées pour le JS
    const consommables = @json($consommables);
    const composants = @json($composants);
    const kits = @json($kits);

    function updateItemsList() {
        const type = document.getElementById('type_item').value;
        const select = document.getElementById('item_id');
        select.innerHTML = '';

        let data = [];
        if (type === 'consommable') data = consommables;
        else if (type === 'composant') data = composants;
        else if (type === 'kit') data = kits;

        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            // Gestion des différents noms de champs selon les modèles
            const nom = item.nom_actif || item.libelle || item.nom || 'Item #' + item.id;
            const stock = item.quantite_stock !== undefined ? `(Stock: ${item.quantite_stock})` : '';
            option.text = `${nom} ${stock}`;
            select.appendChild(option);
        });
    }

    // Init
    document.addEventListener('DOMContentLoaded', function() {
        updateItemsList();
    });
</script>
@endsection
