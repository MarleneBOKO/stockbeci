@extends('layouts.app')

@section('content')
        <div class="container">
            <h2 class="mb-4">Gestion des Composants</h2>

            <!-- Bouton Ajouter -->
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle"></i> Ajouter un composant
            </button>

            <!-- Recherche -->
            <div class="mb-3">
                <input type="text" id="search" class="form-control" placeholder="Rechercher un composant...">
            </div>

            <!-- Tableau -->
            <div id="tableData">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Quantité</th>
                        <th>Seuil min</th>
                        <th>Emplacement</th>
                        <th>Fabricant</th>
                        <th>Fournisseur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list as $composant)
                        <tr @if($composant->qte_min && $composant->quantite <= $composant->qte_min) class="table-danger" @endif>
                            <td>{{ $composant->nom }}</td>
                            <td>{{ $composant->categorie->nom ?? '-' }}</td>
                            <td>{{ $composant->quantite }}</td>
                            <td>{{ $composant->qte_min ?? '-' }}</td>
                            <td>{{ $composant->emplacement->nom ?? '-' }}</td>
                            <td>{{ $composant->fabricant->nom ?? '-' }}</td>
                            <td>{{ $composant->fournisseur->nom ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $composant->id }}">
                                    Modifier
                                </button>
                                <form method="POST" action="{{ route('composants.destroy', $composant) }}"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Supprimer ce composant ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Aucun composant trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $list->links() }}

            </div>
        </div>

        <!-- Modal Ajout -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('composants.store') }}" enctype="multipart/form-data"
                    class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un composant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('composants._form', [
        'categories' => $categories,
        'emplacements' => $emplacements,
        'fournisseurs' => $fournisseurs,
        'fabricants' => $fabricants
    ])
                </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success">Enregistrer</button>
              </div>
            </form>
          </div>
        </  div>


                          <!-- Modals édition -->
        @foreach($list as $composant)
             <div c     lass="modal fade" id="editModal{{ $composant->id }}" tabindex="-1">
                <div c      lass="modal-dialog modal-lg">
                 <for       m method="POST" action="{{ route('composants.update', $composant) }}" enctype="multipart/form-data" class="modal-content">
                        @csrf
                        @method('PUT')
                     <d     iv class="modal-header">
                          <h5 class="modal-title">Modifier : {{ $composant->nom }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                       </   div>
                        <div class="modal-body">
                        @include('composants._form', [
            'composant' => $composant,
            'categories' => $categories,
            'emplacements' => $emplacements,
            'fournisseurs' => $fournisseurs,
            'fabricants' => $fabricants
        ])
                      </div>
                       <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                  </    div>
                   </fo rm>
                  </div>
            </div>
        @endforeach

        <!-- JS Recherche AJAX -->
        <script>
        document.getElementById('search').addEventListener('keyup', function() {
            let q = this.value;
            fetch(`{{ route('composants.search') }}?q=${q}`)
                .then(res => res.text())
                .then(html => document.getElementById('tableData').innerHTML = html);
        });
        </script>

@endsection
