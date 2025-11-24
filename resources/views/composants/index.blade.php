@extends('layouts.template')

@section('content')
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="box-content bordered info js__card">
            <h4 class="box-title with-control">
                Liste des Composants
                <span class="controls">
                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                </span>
            </h4>

            <div class="js__card_content">
                <div class="col-xs-12">
                    <center>@include('flash::message')</center>
                </div>

                <div class="row small-spacing">
                    @if(in_array("add_comp", session("auto_action")))
                        <button type="button" class="btn btn-icon btn-icon-left btn-primary btn-sm waves-effect waves-light"
                            data-toggle="modal" data-target="#addComposant">
                            <i class="ico fa fa-plus"></i> Ajouter
                        </button>
                    @endif

                    {{-- Barre de recherche --}}
                    <form class="form-horizontal" id="recherche">
                        <div class="form-group">
                            <div class="col-sm-3" style="float:right;">
                                <input class="form-control" type="text" id="search" placeholder="Rechercher un composant..">
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
                                        <th>Nom</th>
                                        <th>Catégorie</th>
                                        <th>Emplacement</th>
                                        <th>Fournisseur</th>
                                        <th>Fabricant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $composant)

                                    <tr>
                                        <td>{{ $composant->nom }}</td>
                                        <td>{{ $composant->categorie->nom ?? '-' }}</td>
                                        <td>{{ $composant->emplacement->nom ?? '-' }}</td>
                                        <td>{{ $composant->fournisseur->nom ?? '-' }}</td>
                                        <td>{{ $composant->fabricant->nom ?? '-' }}</td>
                                        <td>
                                            @if(in_array("update_composant", session("auto_action")))
                                                <button class="btn btn-primary btn-xs" data-toggle="modal"
                                                    data-target="#editComposant{{ $composant->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif

                                            @if(in_array("delete_composant", session("auto_action")))
                                                <form method="POST" action="{{ route('composants.destroy', $composant->id) }}" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-xs"
                                                        onclick="return confirm('Supprimer ce composant ?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(in_array("assign_composant_project", session("auto_action")))
                                                <button class="btn btn-success btn-xs" title="Attribuer à un projet" data-toggle="modal"
                                                    data-target="#assignProjetModal" data-composant-id="{{ $composant->id }}">
                                                    <i class="fa fa-project-diagram"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-success btn-xs" title="Attribuer à un projet" data-toggle="modal"
                                                data-target="#assignProjetModal" data-composant-id="{{ $composant->id }}">
                                                <i class="fa fa-project-diagram"></i>
                                            </button>

                                        </td>
                                    </tr>

                                    {{-- Modal édition --}}
                                    <div class="modal fade" id="editComposant{{ $composant->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('composants.update', $composant->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Modifier Composant</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Nom</label>
                                                            <input type="text" name="nom" class="form-control" value="{{ $composant->nom }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Catégorie</label>
                                                            <select name="categorie_id" class="form-control" required>
                                                                @foreach($categories as $categorie)
                                                                    <option value="{{ $categorie->id }}" {{ $composant->categorie_id == $categorie->id ? 'selected' : '' }}>
                                                                        {{ $categorie->nom }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Emplacement</label>
                                                            <select name="emplacement_id" class="form-control">
                                                                @foreach($emplacements as $emplacement)
                                                                    <option value="{{ $emplacement->id }}" {{ $composant->emplacement_id == $emplacement->id ? 'selected' : '' }}>
                                                                        {{ $emplacement->nom }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Fournisseur</label>
                                                            <select name="fournisseur_id" class="form-control">
                                                                @foreach($fournisseurs as $fournisseur)
                                                                    <option value="{{ $fournisseur->id }}" {{ $composant->fournisseur_id == $fournisseur->id ? 'selected' : '' }}>
                                                                        {{ $fournisseur->nom }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Fabricant</label>
                                                            <select name="fabricant_id" class="form-control">
                                                                @foreach($fabricants as $fabricant)
                                                                    <option value="{{ $fabricant->id }}" {{ $composant->fabricant_id == $fabricant->id ? 'selected' : '' }}>
                                                                        {{ $fabricant->nom }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucun composant trouvé</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        {{ $list->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("model")

    <div class="modal fade" id="addComposant" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('composants.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Ajouter un Composant</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Catégorie</label>
                            <select name="categorie_id" class="form-control" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Quantité</label>
                            <input type="number" name="quantite" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Quantité minimale</label>
                            <input type="number" name="qte_min" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Numéro de série</label>
                            <input type="text" name="serial" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Numéro du modèle</label>
                            <input type="text" name="numero_model" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Emplacement</label>
                            <select name="emplacement_id" class="form-control" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($emplacements as $emplacement)
                                    <option value="{{ $emplacement->id }}">{{ $emplacement->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Fournisseur</label>
                            <select name="fournisseur_id" class="form-control">
                                <option value="">-- Sélectionner --</option>
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Fabricant</label>
                            <select name="fabricant_id" class="form-control">
                                <option value="">-- Sélectionner --</option>
                                @foreach($fabricants as $fabricant)
                                    <option value="{{ $fabricant->id }}">{{ $fabricant->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Numéro de commande</label>
                            <input type="text" name="num_commande" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Date d'achat</label>
                            <input type="date" name="date_achat" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Coût d'achat (€)</label>
                            <input type="number" name="cout_achat" step="0.01" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Images (URL ou chemin)</label>
                            <input type="text" name="images" class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<div class="modal fade" id="assignProjetModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form id="assignProjetForm" method="POST" action="">  {{-- Action mise à jour par JS --}}
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attribuer le composant à un projet</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="composant_id" id="modalComposantId" value="">  {{-- Adapté pour composant --}}
                        <div class="form-group">
                            <label for="projetSelect">Projet</label>
                            <select name="projet_id" id="projetSelect" class="form-control" required>
                                <option value="">-- Choisir un projet --</option>
                                @foreach($projets as $projet)  {{-- Assurez-vous que $projets est passé du contrôleur --}}
                                    <option value="{{ $projet->id }}">{{ $projet->refprojet ?? $projet->nom }}</option>  {{-- Ajustez le champ affiché --}}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Attribuer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section("js")
    <script>
        document.getElementById("search").addEventListener("keyup", function () {
            let search = this.value;
            fetch(`{{ route('composants.index') }}?q=${search}`)
                .then(res => res.text())
                .then(html => document.getElementById("data").innerHTML = html);
        });

                   $('#assignProjetModal').on('show.bs.modal', function (event) {
                        var button = $(event.relatedTarget);
                        var composantId = button.data('composant-id');  // Cohérent avec data-composant-id

                        // Validation ID
                        if (!composantId || composantId <= 0 || isNaN(composantId)) {
                            alert('ID de composant invalide. Veuillez sélectionner un composant valide.');
                            event.preventDefault();  // Empêche l'ouverture du modal
                            return false;
                        }

                        var modal = $(this);
                        modal.find('#modalComposantId').val(composantId);
                        var url = "{{ url('composants') }}/" + composantId + "/affecter-projet";
                        modal.find('#assignProjetForm').attr('action', url);

                        console.log('Modal ouvert pour ID:', composantId, 'Action:', url);  // Debug temporaire
                    });

                    // Debug soumission (optionnel, retirez après)
                    $('#assignProjetForm').on('submit', function (e) {
                        console.log('Formulaire soumis ! Données:', $(this).serialize());
                    });
    </script>
@endsection
