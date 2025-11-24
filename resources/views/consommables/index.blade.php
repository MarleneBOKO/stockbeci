@extends('layouts.template')

@section('content')
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="box-content bordered info js__card">
                <h4 class="box-title with-control">
                    Liste des Consommables :
                    <span class="controls">
                        <button type="button" class="control fa fa-minus js__card_minus"></button>
                    </span>
                </h4>
                <div class="js__card_content">
                    <div class="col-xs-12">
                        <center style="border-radius: 10px;top: 75%;">@include('flash::message')</center>
                    </div>

                    <div class="row small-spacing">
                        @if(in_array("add_cons", session("auto_action")))
                            <button type="button" style="margin-left: 30px;"
                                class="btn btn-icon btn-icon-left btn-primary btn-sm waves-effect waves-light" data-toggle="modal"
                                data-target="#add">
                                <i class="ico fa fa-plus"></i> Ajouter
                            </button>
                        @endif

                        {{-- Barre de recherche --}}
                        <form class="form-horizontal" action="" id="recherche">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-group">
                                <div class="col-sm-3" style="margin-right: 30px; margin-top: -45px; float: right;">
                                    <input class="form-control" type="text" id="search"
                                        placeholder="Rechercher un consommable..">
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Table --}}
                    <div class="col-xs-12">
                        <div class="box-content" id="data">
                            <div class="table-responsive" data-pattern="priority-columns">
                               <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Modèle</th>
                <th>Article</th>
                <th>Quantité</th>
                <th>Quantité Min</th>
                <th>Seuil</th>
                <th>Emplacement</th>
                <th>N° Commande</th>
                <th>Date Achat</th>
                <th>Coût Achat</th>
                <th>Fournisseur</th>
                <th>Fabricant</th>
                <th>Notes</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($list as $item)
                <tr>
                    <td>{{ $item->nom }}</td>
                    <td>{{ $item->categorie->nom ?? '-' }}</td>
                    <td>{{ $item->numero_model ?? '-' }}</td>
                    <td>{{ $item->numero_article ?? '-' }}</td>
                    <td>{{ $item->quantite }}</td>
                    <td>{{ $item->qte_min ?? '-' }}</td>
                    <td>
                        @if($item->qte_min && $item->quantite <= $item->qte_min)
                            <span class="badge bg-danger">⚠ Stock bas</span>
                        @else
                            <span class="badge bg-success">OK</span>
                        @endif
                    </td>
                    <td>{{ $item->emplacement->nom ?? '-' }}</td>
                    <td>{{ $item->num_commande ?? '-' }}</td>
                    <td>{{ $item->date_achat ? $item->date_achat->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->cout_achat ? number_format($item->cout_achat, 2, ',', ' ') . ' F CFA' : '-' }}</td>
                    <td>{{ $item->fournisseur->nom ?? '-' }}</td>
                    <td>{{ $item->fabricant->nom ?? '-' }}</td>
                    <td>{{ Str::limit($item->notes, 30) }}</td>
                    <td>
                        @if($item->images)
                            <img src="{{ asset('storage/' . $item->images) }}" alt="image" width="50">
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        {{-- 🔵 Bouton Modifier --}}
                        <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $item->id }}">
                            <i class="fa fa-edit"></i>
                        </button>

                        {{-- 🔴 Bouton Supprimer --}}
                        <form action="{{ route('consommables.destroy', $item->id) }}"
                              method="POST"
                              style="display:inline-block"
                              onsubmit="return confirm('Voulez-vous vraiment supprimer ce consommable ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>

                        @if(in_array("assign_consommable_project", session("auto_action")))
                            <button class="btn btn-success btn-circle btn-xs" title="Attribuer à un projet" data-toggle="modal"
                                data-target="#assignProjetModal" data-consommable-id="{{ $item->id }}">
                                <i class="fa fa-project-diagram"></i>
                            </button>
                        @endif
                        <td>
                            <button class="btn btn-success btn-circle btn-xs" data-toggle="modal" data-target="#assignProjetModal"
                                data-id="{{ $item->id }}">
                                <i class="fas fa-link"></i>
                            </button>

                        </td>


                    </td>
                </tr>

                {{-- 🔵 Modal d’édition --}}
                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('consommables.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier consommable</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Nom</label>
                                        <input type="text" name="nom" class="form-control" value="{{ $item->nom }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Quantité</label>
                                        <input type="number" name="quantite" class="form-control" value="{{ $item->quantite }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Quantité Min</label>
                                        <input type="number" name="qte_min" class="form-control" value="{{ $item->qte_min }}">
                                    </div>

                                    <div class="mb-3">
                                        <label>Notes</label>
                                        <textarea name="notes" class="form-control">{{ $item->notes }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label>Image</label>
                                        <input type="file" name="images" class="form-control">
                                        @if($item->images)
                                            <img src="{{ asset('storage/' . $item->images) }}" width="80" class="mt-2">
                                        @endif
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-success">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="16" class="text-center">Aucun consommable trouvé</td>
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

@section("js")
        <script>
            $('#flash-overlay-modal').modal();
            $('div.alert').not('.alert-important').delay(6000).fadeOut(350);

            // Recherche AJAX
            document.getElementById("search").addEventListener("keyup", function () {
                let search = this.value;
                fetch(`{{ route('consommables.search') }}?q=${search}`)
                    .then(res => res.text())
                    .then(html => document.getElementById("data").innerHTML = html);
            });

         $('#assignProjetModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var consommableId = button.data('id');  // Vérifie bien que data-id existe

    if (!consommableId || consommableId <= 0 || isNaN(consommableId)) {
        alert('ID de consommable invalide.');
        $(this).modal('hide');
        return false;
    }

    var form = $(this).find('#assignProjetForm');

    var action = consommablesBaseUrl + '/' + consommableId + '/affecter-projet';
    console.log("➡️ Action mise à :", action); // ✅ Ajoute ceci

    form.attr('action', action);
    $('#modalConsommableId').val(consommableId);
});

    </script>

@endsection

{{-- Modal Ajout Consommable --}}
@section("model")
    <div class="modal fade" id="add" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('consommables.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Ajouter un Consommable</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Catégorie</label>
                            <select name="categorie_id" class="form-control" required>
                                <option value="">-- Sélectionner une catégorie --</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Modèle (N°)</label>
                            <input type="text" name="numero_model" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Article (N°)</label>
                            <input type="text" name="numero_article" class="form-control">
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
                            <label>Emplacement</label>
                            <select name="emplacement_id" class="form-control" required>
                                <option value="">-- Sélectionner un emplacement --</option>
                                @foreach($emplacements as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->nom }}</option>
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
                            <label>Fournisseur</label>
                            <select name="fournisseur_id" class="form-control">
                                <option value="">-- Sélectionner un fournisseur --</option>
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Fabricant</label>
                            <select name="fabricant_id" class="form-control">
                                <option value="">-- Sélectionner un fabricant --</option>
                                @foreach($fabricants as $fabricant)
                                    <option value="{{ $fabricant->id }}">{{ $fabricant->nom }}</option>
                                @endforeach
                            </select>
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
                <form id="assignProjetForm" method="POST" action=""> <!-- Remove hardcoded route; JS will set it -->
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Attribuer le consommable à un projet</h5>
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="consommable_id" id="modalConsommableId" value="">
                            <div class="form-group">
                                <label for="projetSelect">Projet</label>
                                <select name="projet_id" id="projetSelect" class="form-control" required>
                                    <option value="">-- Choisir un projet --</option>
                                    @foreach($projets as $projet)
                                        <option value="{{ $projet->id }}">{{ $projet->refprojet }}</option>
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
