@extends('layouts.template')

@section('content')
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="box-content bordered info js__card">
            <h4 class="box-title with-control">
                Liste des Actifs :
                <span class="controls">
                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                </span>
            </h4>
            <div class="js__card_content">
                <div class="col-xs-12">
                    <center style="border-radius: 10px;top: 75%;">@include('flash::message')</center>
                </div>

                <div class="row small-spacing">
                    @if(in_array("add_actif", session("auto_action")))
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
                                <input class="form-control" type="text" id="search" placeholder="Rechercher un actif..">
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
                                        <th>Inventaire</th>
                                        <th>Nom</th>
                                        <th>Modèle</th>
                                        <th>Statut</th>
                                        <th>Emplacement</th>
                                        <th>Projet</th>
                                        <th>Utilisateur</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($list as $actif)
                                        <tr>
                                            <td>{{ $actif->inventaire }}</td>
                                            <td>{{ $actif->nom_actif }}</td>
                                            <td>{{ $actif->model->nom ?? '-' }}</td>
                                            <td>{{ $actif->statut ?? '-' }}</td> {{-- Changé : plus de ->libelle --}}
                                            <td>{{ $actif->emplacement->nom ?? '-' }}</td>
                                            <td>{{ $actif->projet->nom ?? '-' }}</td>
                                            <td>{{ $actif->utilisateur->nom ?? '-' }}</td>
                                            <td>
                                                @if(in_array("update_actif", session("auto_action")))
                                                    <button class="btn btn-primary btn-circle btn-xs">
                                                        <a href="/actifs/edit/{{ $actif->id }}" style="color:white;"><i
                                                                class="ico fa fa-edit"></i></a>
                                                    </button>
                                                @endif

                                                @if(in_array("delete_actif", session("auto_action")))
                                                    <button class="btn btn-danger btn-circle btn-xs">
                                                        <a href="/actifs/delete/{{ $actif->id }}" style="color:white;"><i
                                                                class="ico fa fa-trash"></i></a>
                                                    </button>
                                                @endif
                                                @if(in_array("assign_actif_project", session("auto_action")))
                                                    <button class="btn btn-info btn-circle btn-xs" data-toggle="modal"
                                                        data-target="#assignProjetActifModal" data-actif-id="{{ $actif->id }}">
                                                        <i class="fa fa-project-diagram" title="Attribuer à un projet"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                <center>Pas d'actif enregistré !!!</center>
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
            fetch(`{{ route('actifs.search') }}?q=${search}`)
                .then(res => res.text())
                .then(html => document.getElementById("data").innerHTML = html);
        });

        $('#assignProjetActifModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var actifId = button.data('actif-id');
            var modal = $(this);
            modal.find('#modalActifId').val(actifId);
            var url = "{{ url('actifs') }}/" + actifId + "/affecter-projet";
            modal.find('#assignProjetActifForm').attr('action', url);
        });
    </script>
@endsection

@section("model")
    {{-- Modal Ajout Actif --}}
    <div class="modal fade" id="add" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" method="post" action="{{ route('actifs.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Ajouter un Actif</h4>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nom Actif</label>
                            <div class="col-sm-8">
                                <input type="text" name="nom_actif" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Inventaire</label>
                            <div class="col-sm-8">
                                <input type="text" name="inventaire" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Modèle</label>
                            <div class="col-sm-8">
                                <select name="model_id" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($models as $model)
                                        <option value="{{ $model->id }}">{{ $model->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Champ Statut (optionnel, hardcodé avec strings) --}}
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Statut</label>
                            <div class="col-sm-8">
                                <select name="statut" class="form-control"> {{-- Changé : name="statut" (pas _id) --}}
                                    <option value="">-- Sélectionner --</option>
                                    <option value="Liste">Liste</option>
                                    <option value="Déployé">Déployé</option>
                                    <option value="Prêt à être déployé">Prêt à être déployé</option>
                                    <option value="En instance">En instance</option>
                                    <option value="Archivés">Archivés</option>
                                    <option value="Sur demande">Sur demande</option>
                                    <option value="Audit">Audit</option>
                                    <option value="Supprimé">Supprimé</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Emplacement</label>
                            <div class="col-sm-8">
                                <select name="emplacement_id" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($emplacements as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Date Achat</label>
                            <div class="col-sm-8">
                                <input type="date" name="date_achat" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Date Fin Vie</label>
                            <div class="col-sm-8">
                                <input type="date" name="date_fin_vie" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Garantie</label>
                            <div class="col-sm-8">
                                <input type="text" name="garantie" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Notes</label>
                            <div class="col-sm-8">
                                <textarea name="notes" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Cout Achat</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" name="cout_achat" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Valeur Actuelle</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" name="valeur_actuelle" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Image</label>
                            <div class="col-sm-8">
                                <input type="text" name="image" class="form-control">
                            </div>
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

    {{-- Modal Attribution Projet (inchangé) --}}
    <div class="modal fade" id="assignProjetActifModal" tabindex="-1" role="dialog" aria-labelledby="assignProjetLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="assignProjetActifForm" method="POST" action="">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attribuer l'actif à un projet</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="actif_id" id="modalActifId" value="">
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
