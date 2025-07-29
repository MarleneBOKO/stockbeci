#!/bin/bash

# Base de la date pour générer les timestamps
base_date="2025_07_28"
hour=10
minute=00
increment=1

declare -a tables_ordered=(
  "create_fabricants_table"
  "create_amortissements_table"
  "create_categories_table"
  "create_models_table"
  "create_etiquette_etats_table"
  "create_fournisseurs_table"
  "create_projets_table"
  "create_emplacements_table"
  "create_utilisateurs_table"
  "create_actifs_table"
  "create_consommables_table"
  "create_composants_table"
  "create_accessoires_table"
)

index=0
for table in "${tables_ordered[@]}"
do
  timestamp=$(printf "%s_%02d%02d%02d" "$base_date" "$hour" "$minute" "$index")
  file=$(ls *$table.php 2>/dev/null)

  if [[ -n "$file" ]]; then
    newname="${timestamp}_${table}.php"
    echo "🔁 Renomme : $file → $newname"
    mv "$file" "$newname"
    index=$((index + increment))
  else
    echo "⚠️  Aucun fichier trouvé pour $table"
  fi
done
