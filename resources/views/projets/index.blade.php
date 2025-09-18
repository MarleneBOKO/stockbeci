@extends('layouts.template')

@section('content')
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="box-content bordered info js__card">
            <h4 class="box-title with-control">
                Liste des Projets
                <span class="controls">
                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                </span>
            </h4>

            <div class="js__card_content">
                <div class="col-xs-12">
                    <center>@include('flash::message')</center>
                </div>

                <div class="row small-spacing">
                    @if(in_array("add_projet", session("auto_action")))
                        <button type="button" class="btn btn-icon btn-icon-left btn-primary btn-sm waves-effect waves-light"
                            data-toggle="modal" data-target="#addProjet">
                            <i class="ico fa fa-plus"></i> Ajouter
                        </button>
                    @endif

                    {{-- Barre de recherche --}}
                    <form class="form-horizontal" id="recherche">
                        <div class="form-group">
                            <div class="col-sm-3" style="float:right;">
                                <input class="form-control" type="text" id="search" placeholder="Rechercher un projet..">
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="col-xs-12">
                    <div class="box-content" id="data">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Réf Projet</th>
                                        <th>Date Création</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($projets as $projet)
                                        <tr>
                                            <td>{{ $projet->refprojet }}</td>
                                            <td>{{ $projet->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if($projet->statut == 'en_cours')
                                                    <span class="badge badge-success">En cours</span>
                                                @elseif($projet->statut == 'termine')
                                                    <span class="badge badge-primary">Terminé</span>
                                                @else
                                                    <span class="badge badge-danger">Annulé</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(in_array("update_projet", session("auto_action")))
                                                    <button class="btn btn-primary btn-xs" data-toggle="modal"
                                                        data-target="#editProjet{{ $projet->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif

                                                @if(in_array("delete_projet", session("auto_action")))
                                                    <form method="POST" action="{{ route('projets.destroy', $projet->id) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-xs"
                                                            onclick="return confirm('Supprimer ce projet ?')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(in_array("toggle_projet", session("auto_action")))
                                                    <form method="POST" action="{{ route('projets.toggle', $projet->id) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button class="btn btn-warning btn-xs">
                                                            <i class="fa fa-power-off"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(in_array("assign_projet", session("auto_action")))
                                                    <a href="{{ route('projets.assign.form', $projet->id) }}" class="btn btn-success btn-xs" title="Attribuer éléments">
                                                        <i class="fa fa-tasks"></i>
                                                    </a>
                                                @endif
                                                <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#assignItem{{ $projet->id }}">
                                                    <i class="fa fa-plus"></i> Attribuer
                                                </button>


                                            </td>
                                        </tr>

                                        {{-- Modal édition --}}
                                        <div class="modal fade" id="editProjet{{ $projet->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('projets.update', $projet->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Modifier Projet</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Réf Projet</label>
                                                                <input type="text" name="refprojet" class="form-control"
                                                                    value="{{ $projet->refprojet }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Statut</label>
                                                                <select name="statut" class="form-control" required>
                                                                    <option value="en_cours" {{ $projet->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                                                    <option value="termine" {{ $projet->statut == 'termine' ? 'selected' : '' }}>Terminé</option>
                                                                    <option value="annule" {{ $projet->statut == 'annule' ? 'selected' : '' }}>Annulé</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Modal d’attribution générique --}}
                                        <div class="modal fade" id="assignItem{{ $projet->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('projets.assignItem', $projet->id) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Attribuer un Élément au Projet</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="type">Type d'élément</label>
                                                                <select name="type" class="form-control type-select" data-projet="{{ $projet->id }}" required>
                                                                    <option value="">-- Sélectionner --</option>
                                                                    <option value="actif">Actif</option>
                                                                    <option value="consommable">Consommable</option>
                                                                    <option value="composant">Composant</option>
                                                                    <option value="kit">Kit</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="item_id">Élément</label>
                                                                <select name="item_id" id="itemSelect{{ $projet->id }}" class="form-control" required>
                                                                    <option value="">-- Choisissez un type d'abord --</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Quantité à attribuer</label>
                                                                <input type="number" name="quantite" class="form-control" min="1" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-success">Attribuer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Aucun projet trouvé</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $projets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("model")



    <!-- Modal d’attribution actif -->
    <div class="modal fade" id="assignActif{{ $projet->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('projets.assignActif', $projet->id) }}">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Attribuer un Actif au Projet</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="actif_id">Sélectionner l’actif</label>
                            <select name="actif_id" class="form-control" required>
                                @foreach($actifs as $actif)
                                    <option value="{{ $actif->id }}">
                                        {{ $actif->nom }} (Stock: {{ $actif->quantite_stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Quantité à attribuer</label>
                            <input type="number" name="quantite" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-success">Attribuer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        {{-- Modal ajout --}}
        <div class="modal fade" id="addProjet" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('projets.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Ajouter un Projet</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Réf Projet</label>
                                <input type="text" name="refprojet" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="statut" class="form-control" required>
                                    <option value="en_cours">En cours</option>
                                    <option value="termine">Terminé</option>
                                    <option value="annule">Annulé</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div

                </div>
            </div>
@endsection

@section("js")
    <script>
        document.getElementById("search").addEventListener("keyup", function () {
            let search = this.value;
            fetch(`{{ route('projets.index') }}?q=${search}`)
                .then(res => res.text())
                .then(html => document.getElementById("data").innerHTML = html);
        });


                const data = {
                    actifs: @json($actifs),
                consommables: @json($consommables),
                composants: @json($composants),
                kits: @json($kits)
    };

    document.querySelectorAll('.type-select').forEach(select => {
                    select.addEventListener('change', function () {
                        const type = this.value;
                        const projetId = this.getAttribute('data-projet');
                        const selectEl = document.getElementById('itemSelect' + projetId);

                        // Vider la liste
                        selectEl.innerHTML = '';

                        if (data[type + 's']) {
                            data[type + 's'].forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.id;
                                option.text = `${item.nom} (${item.quantite_stock ?? ''})`;
                                selectEl.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.text = 'Aucun élément trouvé';
                            selectEl.appendChild(option);
                        }
                    });
    });

    </script>
@endsection
