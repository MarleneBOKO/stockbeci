@extends('layouts.template')

@section('content')
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="box-content bordered info js__card">
            <h4 class="box-title with-control">
                Liste des Modèles
                <span class="controls">
                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                </span>
            </h4>

            <div class="js__card_content">
                <div class="col-xs-12">
                    <center>@include('flash::message')</center>
                </div>

                <div class="row small-spacing">
                    @if(in_array("add_model", session("auto_action")))
                        <button type="button" class="btn btn-icon btn-icon-left btn-primary btn-sm waves-effect waves-light"
                            data-toggle="modal" data-target="#addModele">
                            <i class="ico fa fa-plus"></i> Ajouter
                        </button>
                    @endif

                    {{-- Barre de recherche --}}
                    <form class="form-horizontal" id="recherche">
                        <div class="form-group">
                            <div class="col-sm-3" style="float:right;">
                                <input class="form-control" type="text" id="search" placeholder="Rechercher un modèle..">
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
                                        <th>Libellé</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($modeles as $modele)
                                        <tr>
                                            <td>{{ $modele->libelle }}</td>
                                            <td>{{ $modele->description }}</td>
                                            <td>
                                                @if(in_array("update_modele", session("auto_action")))
                                                    <button class="btn btn-primary btn-xs" data-toggle="modal"
                                                        data-target="#editModele{{ $modele->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif

                                                @if(in_array("delete_modele", session("auto_action")))
                                                    <form method="POST" action="{{ route('models.destroy', $modele->id) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-xs"
                                                            onclick="return confirm('Supprimer ce modèle ?')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Modal édition --}}
                                        <div class="modal fade" id="editModele{{ $modele->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('models.update', $modele->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Modifier Modèle</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Libellé</label>
                                                                <input type="text" name="libelle" class="form-control"
                                                                    value="{{ $modele->libelle }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                <textarea name="description"
                                                                    class="form-control">{{ $modele->description }}</textarea>
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
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Aucun modèle trouvé</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $modeles->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("model")
        {{-- Modal ajout --}}
   <form method="POST" action="{{ route('models.store') }}">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">Ajouter un Modèle</h4>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>

        {{-- Select Catégorie --}}
        <div class="form-group">
            <label>Catégorie</label>
            <select name="categorie_id" class="form-control">
                <option value="">-- Sélectionner --</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                @endforeach
            </select>
        </div>

        {{-- Select Fabricant --}}
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
            <label>Numéro de modèle</label>
            <input type="text" name="model_num" class="form-control">
        </div>

        <div class="form-group">
            <label>Quantité minimale</label>
            <input type="number" name="qte_min" class="form-control">
        </div>

        <div class="form-group">
            <label>Fin de vie</label>
            <input type="number" name="findevie" class="form-control">
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>Champs additionnels</label>
            <input type="text" name="ensemble_champs" class="form-control">
        </div>

        <div class="form-group">
            <label>Images</label>
            <input type="text" name="images" class="form-control">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </div>
</form>


@endsection

@section("js")
    <script>
        document.getElementById("search").addEventListener("keyup", function () {
            let search = this.value;
            fetch(`{{ route('models.index') }}?q=${search}`)
                .then(res => res.text())
                .then(html => document.getElementById("data").innerHTML = html);
        });
    </script>
@endsection
