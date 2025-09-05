@extends('layouts.template')

@section('content')
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="box-content bordered info js__card">
            <h4 class="box-title with-control">
                Liste des Catégories :
                <span class="controls">
                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                </span>
            </h4>
            <div class="js__card_content">
                <div class="col-xs-12">
                    <center style="border-radius: 10px;top: 75%;">@include('flash::message')</center>
                </div>

                <div class="row small-spacing">
                    {{-- Bouton Ajouter --}}
                    @if(in_array("add_categorie", session("auto_action")))
                        <button type="button" style="margin-left: 30px;"
                            class="btn btn-icon btn-icon-left btn-primary btn-sm waves-effect waves-light" data-toggle="modal"
                            data-target="#addCategorie">
                            <i class="ico fa fa-plus"></i> Ajouter
                        </button>
                    @endif

                    {{-- Barre de recherche --}}
                    <form class="form-horizontal" id="recherche">
                        <div class="form-group">
                            <div class="col-sm-3" style="margin-right: 30px; margin-top: -45px; float: right;">
                                <input class="form-control" type="text" id="search"
                                    placeholder="Rechercher une catégorie..">
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
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $categorie)
                                    <tr>
                                        <td>{{ $categorie->nom }}</td>
                                        <td>{{ $categorie->type }}</td>
                                        <td>{{ $categorie->description ?? '-' }}</td>
                                        <td>
                                            @if($categorie->image)
                                                <img src="{{ $categorie->image }}" alt="Image" style="width:50px;height:50px;">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $categorie->notes ?? '-' }}</td>
                                        <td>
                                            @if(in_array("update_categorie", session("auto_action")))
                                                <a href="{{ route('categories.edit', $categorie->id) }}" class="btn btn-primary btn-circle btn-xs">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif

                                            @if(in_array("delete_categorie", session("auto_action")))
                                                <form action="{{ route('categories.destroy', $categorie->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-circle btn-xs" onclick="return confirm('Supprimer ?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <center>Pas de catégorie enregistrée !!!</center>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $categories->links() }}
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
            fetch(`{{ route('categories.search') }}?q=${search}`)
                .then(res => res.text())
                .then(html => document.getElementById("data").innerHTML = html);
        });
    </script>
@endsection

@section("model")
    {{-- Modal Ajout Catégorie --}}
    <div class="modal fade" id="addCategorie" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" method="post" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Ajouter une Catégorie</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nom</label>
                            <div class="col-sm-8">
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Type</label>
                            <div class="col-sm-8">
                                <select name="type" class="form-control" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="Actif">Actif</option>
                                    <option value="Accessoire">Accessoire</option>
                                    <option value="Consommable">Consommable</option>
                                    <option value="Composant">Composant</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Description</label>
                            <div class="col-sm-8">
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Image (URL)</label>
                            <div class="col-sm-8">
                                <input type="text" name="image" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Notes</label>
                            <div class="col-sm-8">
                                <textarea name="notes" class="form-control"></textarea>
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
@endsection
