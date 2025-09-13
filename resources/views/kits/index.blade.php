@extends('layouts.template')

@section('content')
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="box-content bordered info js__card">
            <h4 class="box-title with-control">
                Liste des Kits :
                <span class="controls">
                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                </span>
            </h4>

            <div class="js__card_content">
                <div class="col-xs-12">
                    <center style="border-radius: 10px; top: 75%;">@include('flash::message')</center>
                </div>

                <div class="row small-spacing">
                    @if(in_array("add_kit", session("auto_action")))
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
                                <input class="form-control" type="text" id="search" placeholder="Rechercher un kit..">
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
                                        <th>Description</th>
                                        <th>Éléments</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kits as $kit)
                                    <tr>
                                        <td>{{ $kit->nom }}</td>
                                        <td>{{ $kit->description ?? '-' }}</td>
                                        <td>
                                            <ul>
                                                @foreach($kit->items as $item)
                                                <li>{{ $item->item_type }} (ID: {{ $item->item_id }}) x
                                                    {{ $item->quantite }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            @if(in_array("update_kit", session("auto_action")))
                                            <button class="btn btn-primary btn-circle btn-xs">
                                                <a href="{{ route('kits.edit', $kit) }}" style="color:white;"><i
                                                        class="ico fa fa-edit"></i></a>
                                            </button>
                                            @endif

                                            @if(in_array("delete_kit", session("auto_action")))
                                            <button class="btn btn-danger btn-circle btn-xs">
                                                <a href="{{ route('kits.destroy', $kit) }}" style="color:white"
                                                    onclick="event.preventDefault();document.getElementById('delete-{{ $kit->id }}').submit();"><i
                                                        class="ico fa fa-trash"></i></a>
                                            </button>

                                            <form id="delete-{{ $kit->id }}"
                                                action="{{ route('kits.destroy', $kit) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4">
                                            <center>Pas de kit enregistré !!!</center>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $kits->links() }}
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
    fetch(`{{ route('kits.search') }}?q=${search}`)
        .then(res => res.text())
        .then(html => document.getElementById("data").innerHTML = html);
});
</script>
@endsection

@section("model")
{{-- Modal Ajout Kit --}}
<div class="modal fade" id="add" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal" method="post" action="{{ route('kits.store') }}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter un Kit</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nom</label>
                        <div class="col-sm-8">
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Description</label>
                        <div class="col-sm-8">
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Éléments</label>
                        <div class="col-sm-8">
                            <small>Ajouter des éléments (Composants, Accessoires, etc.)</small>
                            <div id="kit-items">
                                <div class="kit-item row mb-2">
                                    <div class="col-xs-4">
                                        <select name="items[0][item_type]" class="form-control" required>
                                            <option value="">-- Type --</option>
                                            <option value="Actif">Actif</option>
                                            <option value="Composant">Composant</option>
                                            <option value="Accessoire">Accessoire</option>
                                            <option value="Consommable">Consommable</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-4">
                                        <input type="number" name="items[0][item_id]" class="form-control" placeholder="ID" required>
                                    </div>
                                    <div class="col-xs-3">
                                        <input type="number" name="items[0][quantite]" class="form-control" placeholder="Quantité" value="1" required>
                                    </div>
                                    <div class="col-xs-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-item">&times;</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-info btn-sm" id="add-item">Ajouter un élément</button>
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

<script>
let itemIndex = 1;
document.getElementById('add-item').addEventListener('click', function() {
    let container = document.getElementById('kit-items');
    let html = `
    <div class="kit-item row mb-2">
        <div class="col-xs-4">
            <select name="items[${itemIndex}][item_type]" class="form-control" required>
                <option value="">-- Type --</option>
                <option value="Actif">Actif</option>
                <option value="Composant">Composant</option>
                <option value="Accessoire">Accessoire</option>
                <option value="Consommable">Consommable</option>
            </select>
        </div>
        <div class="col-xs-4">
            <input type="number" name="items[${itemIndex}][item_id]" class="form-control" placeholder="ID" required>
        </div>
        <div class="col-xs-3">
            <input type="number" name="items[${itemIndex}][quantite]" class="form-control" placeholder="Quantité" value="1" required>
        </div>
        <div class="col-xs-1">
            <button type="button" class="btn btn-danger btn-sm remove-item">&times;</button>
        </div>
    </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    itemIndex++;
});

// Supprimer un élément
document.addEventListener('click', function(e){
    if(e.target && e.target.classList.contains('remove-item')){
        e.target.closest('.kit-item').remove();
    }
});
</script>
@endsection
