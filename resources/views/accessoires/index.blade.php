@extends('layouts.template')

@section('content')
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="box-content bordered info js__card">
            <h4 class="box-title with-control">
                Liste des Accessoires :
                <span class="controls">
                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                </span>
            </h4>
            <div class="js__card_content">
                <div class="col-xs-12">
                    <center style="border-radius: 10px;top: 75%;">@include('flash::message')</center>
                </div>

                <div class="row small-spacing">
                    @if(in_array("add_acc", session("auto_action")))
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
                                    placeholder="Rechercher un accessoire..">
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
                                        <th>Catégorie</th>
                                        <th>Quantité</th>
                                        <th>Quantité Min</th>
                                        <th>Fabricant</th>
                                        <th>Emplacement</th>
                                        <th>Fournisseur</th>
                                        <th>Numéro Modèle</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($list as $accessoire)
                                        <tr>
                                            <td>{{ $accessoire->nom }}</td>
                                            <td>{{ $accessoire->categorie->nom ?? '-' }}</td>
                                            <td>{{ $accessoire->quantite }}</td>
                                            <td>{{ $accessoire->qte_min ?? '-' }}</td>
                                            <td>{{ $accessoire->fabricant->nom ?? '-' }}</td>
                                            <td>{{ $accessoire->emplacement->nom ?? '-' }}</td>
                                            <td>{{ $accessoire->fournisseur->nom ?? '-' }}</td>
                                            <td>{{ $accessoire->numero_model ?? '-' }}</td>
                                            <td>
                                                @if(in_array("update_accessoire", session("auto_action")))
                                                    <button class="btn btn-primary btn-circle btn-xs">
                                                        <a href="/accessoires/edit/{{ $accessoire->id }}" style="color:white;"><i
                                                                class="ico fa fa-edit"></i></a>
                                                    </button>
                                                @endif

                                                @if(in_array("delete_accessoire", session("auto_action")))
                                                    <button class="btn btn-danger btn-circle btn-xs">
                                                        <a href="/accessoires/delete/{{ $accessoire->id }}" style="color:white;"><i
                                                                class="ico fa fa-trash"></i></a>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9">
                                                <center>Pas d'accessoire enregistré !!!</center>
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
            fetch(`{{ route('accessoires.search') }}?q=${search}`)
                .then(res => res.text())
                .then(html => document.getElementById("data").innerHTML = html);
        });
    </script>
@endsection

@section("model")
    {{-- Modal Ajout Accessoire --}}
    <div class="modal fade" id="add" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" method="post" action="{{ route('accessoires.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Ajouter un Accessoire</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nom</label>
                            <div class="col-sm-8">
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Catégorie</label>
                            <div class="col-sm-8">
                                <select name="categorie_id" class="form-control">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Quantité</label>
                            <div class="col-sm-8">
                                <input type="number" name="quantite" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Quantité Min</label>
                            <div class="col-sm-8">
                                <input type="number" name="qte_min" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Fabricant</label>
                            <div class="col-sm-8">
                                <select name="fabricant_id" class="form-control">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($fabricants as $fab)
                                        <option value="{{ $fab->id }}">{{ $fab->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Emplacement</label>
                            <div class="col-sm-8">
                                <select name="emplacement_id" class="form-control">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($emplacements as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Fournisseur</label>
                            <div class="col-sm-8">
                                <select name="fournisseur_id" class="form-control">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($fournisseurs as $fou)
                                        <option value="{{ $fou->id }}">{{ $fou->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Numéro Modèle</label>
                            <div class="col-sm-8">
                                <input type="text" name="numero_model" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Cout Achat</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" name="cout_achat" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Date Achat</label>
                            <div class="col-sm-8">
                                <input type="date" name="date_achat" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Notes</label>
                            <div class="col-sm-8">
                                <textarea name="notes" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Images</label>
                            <div class="col-sm-8">
                                <input type="text" name="images" class="form-control">
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
