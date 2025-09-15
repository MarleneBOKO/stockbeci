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
                    @if(in_array("add_composant", session("auto_action")))
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
    {{-- Modal ajout --}}
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
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Emplacement</label>
                            <select name="emplacement_id" class="form-control">
                                @foreach($emplacements as $emplacement)
                                    <option value="{{ $emplacement->id }}">{{ $emplacement->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Fournisseur</label>
                            <select name="fournisseur_id" class="form-control">
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Fabricant</label>
                            <select name="fabricant_id" class="form-control">
                                @foreach($fabricants as $fabricant)
                                    <option value="{{ $fabricant->id }}">{{ $fabricant->nom }}</option>
                                @endforeach
                            </select>
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
        fetch(`{{ route('composants.index') }}?q=${search}`)
            .then(res => res.text())
            .then(html => document.getElementById("data").innerHTML = html);
    });
</script>
@endsection
