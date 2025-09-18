@extends('layouts.template')

@section('content')
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="box-content bordered info js__card">
            <h4 class="box-title with-control">
                Liste des Emplacements
                <span class="controls">
                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                </span>
            </h4>

            <div class="js__card_content">
                <div class="col-xs-12">
                    <center>@include('flash::message')</center>
                </div>

                <div class="row small-spacing">
                    @if(in_array("add_emplacement", session("auto_action")))
                        <button type="button" class="btn btn-icon btn-icon-left btn-primary btn-sm waves-effect waves-light"
                            data-toggle="modal" data-target="#addEmplacement">
                            <i class="ico fa fa-plus"></i> Ajouter
                        </button>
                    @endif

                    {{-- Barre de recherche --}}
                    <form class="form-horizontal" id="recherche">
                        <div class="form-group">
                            <div class="col-sm-3" style="float:right;">
                                <input class="form-control" type="text" id="search"
                                    placeholder="Rechercher un emplacement..">
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
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($emplacements as $emplacement)
                                        <tr>
                                            <td>{{ $emplacement->nom }}</td>
                                            <td>{{ $emplacement->description }}</td>
                                            <td>
                                                @if(in_array("update_emplacement", session("auto_action")))
                                                    <button class="btn btn-primary btn-xs" data-toggle="modal"
                                                        data-target="#editEmplacement{{ $emplacement->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif

                                                @if(in_array("delete_emplacement", session("auto_action")))
                                                    <form method="POST"
                                                        action="{{ route('emplacements.destroy', $emplacement->id) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-xs"
                                                            onclick="return confirm('Supprimer cet emplacement ?')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Modal édition --}}
                                        <div class="modal fade" id="editEmplacement{{ $emplacement->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST"
                                                        action="{{ route('emplacements.update', $emplacement->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Modifier Emplacement</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Nom</label>
                                                                <input type="text" name="nom" class="form-control"
                                                                    value="{{ $emplacement->nom }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                <textarea name="description"
                                                                    class="form-control">{{ $emplacement->description }}</textarea>
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
                                            <td colspan="3" class="text-center">Aucun emplacement trouvé</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $emplacements->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("model")
    {{-- Modal ajout --}}
   <div class="modal fade" id="addEmplacement" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('emplacements.store') }}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter un Emplacement</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Ville</label>
                        <input type="text" name="ville" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Pays</label>
                        <input type="text" name="pays" class="form-control" required>
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

@endsection

@section("js")
    <script>
        document.getElementById("search").addEventListener("keyup", function () {
            let search = this.value;
            fetch(`{{ route('emplacements.index') }}?q=${search}`)
                .then(res => res.text())
                .then(html => document.getElementById("data").innerHTML = html);
        });
    </script>
@endsection
