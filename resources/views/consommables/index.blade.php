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
                    @if(in_array("add_consommable", session("auto_action")))
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
                            <label>Quantité</label>
                            <input type="number" name="quantite" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Quantité Min</label>
                            <input type="number" name="qte_min" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="item_type" class="form-control">
                                <option value="">-- Sélectionner --</option>
                                <option value="Actif">Actif</option>
                                <option value="Accessoire">Accessoire</option>
                                <option value="Composant">Composant</option>
                                <option value="Consommable">Consommable</option>
                                <option value="Kit">Kit</option>
                            </select>
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
