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
                            <table class="table table-small-font table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Quantité</th>
                                        <th>Quantité Min</th>
                                        <th>Seuil</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($list as $item)
                                        <tr>
                                            <td>{{ $item->nom }}</td>
                                            <td>{{ $item->quantite }}</td>
                                            <td>{{ $item->qte_min ?? '-' }}</td>
                                            <td>
                                                @if($item->qte_min && $item->quantite <= $item->qte_min)
                                                    <span class="badge badge-danger">⚠ Stock bas</span>
                                                @else
                                                    <span class="badge badge-success">OK</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->item_type ?? '-' }}</td>
                                            <td>
                                                @if(in_array("update_consommable", session("auto_action")))
                                                    <button class="btn btn-primary btn-circle btn-xs" data-toggle="modal"
                                                        data-target="#edit{{ $item->id }}">
                                                        <i class="ico fa fa-edit"></i>
                                                    </button>
                                                @endif
                                                @if(in_array("delete_consommable", session("auto_action")))
                                                    <form action="{{ route('consommables.destroy', $item->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-circle btn-xs"><i
                                                                class="ico fa fa-trash"></i></button>
                                                    </form>
                                                @endif
                                                @if(in_array("assign_consommable_project", session("auto_action")))
                                                    <button class="btn btn-info btn-circle btn-xs" data-toggle="modal" data-target="#assignProjetModal"
                                                        data-consommable-id="{{ $item->id }}">
                                                        <i class="fa fa-project-diagram" title="Attribuer à un projet"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-info btn-circle btn-xs" data-toggle="modal" data-target="#assignProjetModal"
                                                    data-consommable-id="{{ $item->id }}">
                                                    <i class="fa fa-project-diagram" title="Attribuer à un projet"></i>
                                                </button>

                                                @if(in_array("stock_consommable", session("auto_action")))
                                                    <button class="btn btn-success btn-circle btn-xs" data-toggle="modal"
                                                        data-target="#entree{{ $item->id }}"><i
                                                            class="ico fa fa-arrow-up"></i></button>
                                                    <button class="btn btn-warning btn-circle btn-xs" data-toggle="modal"
                                                        data-target="#sortie{{ $item->id }}"><i
                                                            class="ico fa fa-arrow-down"></i></button>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Modal Edition --}}
                                        <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('consommables.update', $item->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h4>Modifier Consommable</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="text" name="nom" class="form-control"
                                                                value="{{ $item->nom }}" required>
                                                            <input type="number" name="quantite" class="form-control mt-2"
                                                                value="{{ $item->quantite }}" required>
                                                            <input type="number" name="qte_min" class="form-control mt-2"
                                                                value="{{ $item->qte_min }}">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">FERMER</button>
                                                            <button type="submit" class="btn btn-primary">MODIFIER</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Entrée --}}
                                        <div class="modal fade" id="entree{{ $item->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('consommables.entree', $item->id) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h4>Entrée de Stock</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="number" name="quantite" class="form-control"
                                                                placeholder="Quantité à ajouter" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">FERMER</button>
                                                            <button type="submit" class="btn btn-success">AJOUTER</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Sortie --}}
                                        <div class="modal fade" id="sortie{{ $item->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('consommables.sortie', $item->id) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h4>Sortie de Stock</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="number" name="quantite" class="form-control"
                                                                placeholder="Quantité à retirer" required>
                                                            <input type="text" name="projet" class="form-control mt-2"
                                                                placeholder="Projet (optionnel)">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">FERMER</button>
                                                            <button type="submit" class="btn btn-warning">RETIRER</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <center>Pas de consommable enregistré !!!</center>
                                            </td>
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
                var consommableId = button.data('consommable-id');
                var modal = $(this);
                modal.find('#modalConsommableId').val(consommableId);
                var url = "{{ url('consommables') }}/" + consommableId + "/affecter-projet";
                modal.find('#assignProjetForm').attr('action', url);
            });
    </script>

@endsection

{{-- Modal Ajout Consommable --}}
@section("model")
    <div class="modal fade" id="add" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('consommables.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h4>Ajouter un Consommable</h4>
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
                            <label>Quantité Min</label>
                            <input type="number" name="qte_min" class="form-control">
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
                            <label>Emplacement</label>
                            <select name="emplacement_id" class="form-control" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($emplacements as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>N° Commande</label>
                            <input type="text" name="num_commande" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Date d'achat</label>
                            <input type="date" name="date_achat" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Coût d'achat</label>
                            <input type="number" step="0.01" name="cout_achat" class="form-control">
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
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Images (URL ou chemin)</label>
                            <input type="text" name="images" class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">FERMER</button>
                        <button type="submit" class="btn btn-primary">AJOUTER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




        <div class="modal fade" id="assignProjetModal" tabindex="-1" role="dialog" aria-labelledby="assignProjetLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form id="assignProjetForm" method="POST" action="">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Attribuer le consommable à un projet</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                <span aria-hidden="true">&times;</span>
                            </button>
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
