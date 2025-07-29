<?php

use Illuminate\Support\Facades\Artisan;

public function importerDonnees()
{
    $cheminFichier = storage_path('app/backups/table.sql');

    if (!file_exists($cheminFichier)) {
        return back()->with('error', 'Fichier de sauvegarde non trouvé.');
    }

    Artisan::call('db:restore-table', [
        '--file' => $cheminFichier
    ]);

    return redirect()->back()->with('success', 'Données restaurées avec succès.');
}
