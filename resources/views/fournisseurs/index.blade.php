@extends('layouts.template')

@section('content')
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="box-content bordered info js__card">
            <h4 class="box-title with-control">
                Liste des Fournisseurs :
                <span class="controls">
                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                </span>
            </h4>
            <div class="js__card_content">
                <div class="col-xs-12">
                    <center style="border-radius: 10px;top: 75%;">
                        @include('flash::message')
                    </center>
                </div>

                <div class="row small-spacing">
                    @if(in_array("add_fournisseur", session("auto_action")))
                        <button type="button" style="margin-left: 30px;"
                            class="btn btn-icon btn-icon-left btn-primary btn-sm waves-effect waves-light" data-toggle="modal"
                            data-target="#addFournisseur">
                            <i class="ico fa fa-plus"></i> Ajouter
                        </button>
                    @endif

                    {{-- Barre de recherche --}}
                    <form class="form-horizontal" action="" id="recherche">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group">
                            <div class="col-sm-3" style="margin-right: 30px; margin-top: -45px; float: right;">
                                <input class="form-control" type="text" id="search"
                                    placeholder="Rechercher un fournisseur..">
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
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Adresse</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($list as $fournisseur)
                                    <tr>
                                        <td>{{ $fournisseur->nom }}</td>
                                        <td>{{ $fournisseur->contact ?? '-' }}</td>
                                        <td>{{ $fournisseur->email ?? '-' }}</td>
                                        <td>{{ $fournisseur->telephone ?? '-' }}</td>
                                        <td>{{ $fournisseur->adresse ?? '-' }}</td>
                                        <td>{{ Str::limit($fournisseur->notes, 30) }}</td>
                                        <td>
                                            @if(in_array("update_fournisseur", session("auto_action")))
                                                <button class="btn btn-primary btn-circle btn-xs" data-toggle="modal"
                                                    data-target="#editFournisseur{{ $fournisseur->id }}">
                                                    <i class="ico fa fa-edit"></i>
                                                </button>
                                            @endif

                                            @if(in_array("delete_fournisseur", session("auto_action")))
                                                <form action="{{ route('fournisseurs.destroy', $fournisseur->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-circle btn-xs"
                                                        onclick="return confirm('Supprimer ce fournisseur ?')">
                                                        <i class="ico fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <center>Pas de fournisseur enregistré !!!</center>
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
            fetch(`{{ route('fournisseurs') }}?search=${search}`)
                .then(res => res.text())
                .then(html => document.getElementById("data").innerHTML = html);
        });
    </script>
@endsection

@section("model")
    {{-- Modal Ajout Fournisseur --}}
    <div class="modal fade" id="addFournisseur" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" method="post" action="{{ route('fournisseurs.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Ajouter un Fournisseur</h4>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nom">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control" id="nom" required>
                            </div>

                            <div class="form-group">
                                <label for="adresse">Adresse</label>
                                <input type="text" name="adresse" class="form-control" id="adresse">
                            </div>

                            <div class="form-group">
                                <label for="ville">Ville</label>
                                <input type="text" name="ville" class="form-control" id="ville">
                            </div>

                            <div class="form-group">
                                <label for="etat">État</label>
                                <input type="text" name="etat" class="form-control" id="etat">
                            </div>

                            <div class="form-group">
                                <label for="pays">Pays</label>
                                <input type="text" name="pays" class="form-control" id="pays">
                            </div>

                            <div class="form-group">
                                <label for="nom_personne_ressource">Personne Ressource</label>
                                <input type="text" name="nom_personne_ressource" class="form-control" id="nom_personne_ressource">
                            </div>

                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="text" name="telephone" class="form-control" id="telephone">
                            </div>

                            <div class="form-group">
                                <label for="fax">Fax</label>
                                <input type="text" name="fax" class="form-control" id="fax">
                            </div>

                            <div class="form-group">
                                <label for="messagerie_electronique">Email</label>
                                <input type="email" name="messagerie_electronique" class="form-control" id="messagerie_electronique">
                            </div>

                            <div class="form-group">
                                <label for="url">Site Web</label>
                                <input type="url" name="url" class="form-control" id="url">
                            </div>

                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea name="note" class="form-control" id="note" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="image">Image / Logo</label>
                                <input type="file" name="image" class="form-control-file" id="image">
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
