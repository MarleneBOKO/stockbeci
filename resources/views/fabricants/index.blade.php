@extends('layouts.template')

@section('content')
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="box-content bordered info js__card">
            <h4 class="box-title with-control">
                Liste des Fabricants :
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
                    @if(in_array("add_fabricant", session("auto_action")))
                        <button type="button" style="margin-left: 30px;"
                            class="btn btn-icon btn-icon-left btn-primary btn-sm waves-effect waves-light" data-toggle="modal"
                            data-target="#addFabricant">
                            <i class="ico fa fa-plus"></i> Ajouter
                        </button>
                    @endif

                    {{-- Barre de recherche --}}
                    <form class="form-horizontal" action="" id="recherche">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group">
                            <div class="col-sm-3" style="margin-right: 30px; margin-top: -45px; float: right;">
                                <input class="form-control" type="text" id="search" placeholder="Rechercher un fabricant..">
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
                                        <th>URL</th>
                                        <th>Assistance</th>
                                        <th>Email Assistance</th>
                                        <th>Téléphone Assistance</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($list as $fabricant)
                                        <tr>
                                            <td>{{ $fabricant->nom }}</td>
                                            <td><a href="{{ $fabricant->url }}" target="_blank">{{ $fabricant->url ?? '-' }}</a></td>
                                            <td><a href="{{ $fabricant->url_assistance }}" target="_blank">{{ $fabricant->url_assistance ?? '-' }}</a></td>
                                            <td>{{ $fabricant->email_assistance ?? '-' }}</td>
                                            <td>{{ $fabricant->tel_assistance ?? '-' }}</td>
                                            <td>{{ Str::limit($fabricant->notes, 30) }}</td>
                                            <td>
                                                @if(in_array("update_fabricant", session("auto_action")))
                                                    <button class="btn btn-primary btn-circle btn-xs" data-toggle="modal"
                                                        data-target="#editFabricant{{ $fabricant->id }}">
                                                        <i class="ico fa fa-edit"></i>
                                                    </button>
                                                @endif

                                                @if(in_array("delete_fabricant", session("auto_action")))
                                                    <form action="{{ route('fabricants.destroy', $fabricant->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-circle btn-xs"
                                                            onclick="return confirm('Supprimer ce fabricant ?')">
                                                            <i class="ico fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Modal Édition --}}
                                            <div class="modal fade" id="editFabricant{{ $fabricant->id }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form class="form-horizontal" method="POST"
                                                            action="{{ route('fabricants.update', $fabricant->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Modifier Fabricant</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                            {{-- resources/views/fabricants/partials/form.blade.php --}}

                                                            <div class="form-group">
                                                                <label for="nom">Nom</label>
                                                                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $fabricant->nom ?? '') }}"
                                                                    required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="url">URL</label>
                                                                <input type="url" name="url" id="url" class="form-control" value="{{ old('url', $fabricant->url ?? '') }}">
                                                            </div>

                                                            {{-- Ajoutez ici les autres champs de votre formulaire de fabricant --}}

                                                            <div class="form-group">
                                                                <label for="image">Image</label>
                                                                <input type="file" name="image" id="image" class="form-control-file">
                                                                @if(!empty($fabricant->image))
                                                                    <img src="{{ asset('storage/' . $fabricant->image) }}" alt="Image fabricant"
                                                                        style="max-height: 100px; margin-top: 10px;">
                                                                @endif
                                                            </div>
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
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <center>Pas de fabricant enregistré !!!</center>
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

        // Recherche AJAX - Corrigé
        document.getElementById("search").addEventListener("keyup", function () {
            let search = this.value;
            fetch(`{{ route('fabricants.search') }}?q=${search}`)
                .then(res => res.text())
                .then(html => document.getElementById("data").innerHTML = html)
                .catch(err => console.error('Erreur de recherche:', err));
        });
    </script>
@endsection

@section("model")
    {{-- Modal Ajout Fabricant - CORRIGÉ --}}
    <div class="modal fade" id="addFabricant" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('fabricants.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Ajouter un Fabricant</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" name="nom" id="nom" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="url">Site Web</label>
                            <input type="url" name="url" id="url" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="url_assistance">URL Assistance</label>
                            <input type="url" name="url_assistance" id="url_assistance" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="url_search_garantie">URL Recherche Garantie</label>
                            <input type="url" name="url_search_garantie" id="url_search_garantie" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="tel_assistance">Téléphone Assistance</label>
                            <input type="text" name="tel_assistance" id="tel_assistance" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="email_assistance">Email Assistance</label>
                            <input type="email" name="email_assistance" id="email_assistance" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">Logo / Image</label>
                            <input type="file" name="image" id="image" class="form-control-file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">FERMER</button>
                        <button type="submit" class="btn btn-primary">ENREGISTRER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
