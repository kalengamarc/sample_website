<?php
/**
 * Script pour corriger les formulaires de favoris dans services.php
 */

$filePath = __DIR__ . '/vue/services.php';
$content = file_get_contents($filePath);

// Remplacer le premier formulaire de favoris
$pattern = '/<form method="post" style="display: inline;">\s*<input type="hidden" name="action" value="ajouter_favoris">\s*<input type="hidden" name="id_service" value="(.*?)">\s*<button type="submit" class="icon fas fa-star (.*?)"\s*title="(.*?)">\s*<\/button>/s';
$replacement = '<form method="post" action="../controle/index.php" style="display: inline;">
                                        <input type="hidden" name="do" value="add_to_favorites">
                                        <input type="hidden" name="type" value="formation">
                                        <input type="hidden" name="id_element" value="$1">
                                        <button type="submit" class="icon fas fa-star $2"
                                                title="$3">
                                        </button>';
$content = preg_replace($pattern, $replacement, $content);

// Remplacer le deuxième formulaire de favoris (dans la modale de détails)
$pattern = '/<form method="post" style="display: inline;">\s*<input type="hidden" name="action" value="ajouter_favoris">\s*<input type="hidden" name="id_service" id="details_favoris_id">\s*<button type="submit" class="btn-details">\s*<i class="fas fa-star"><\/i> Ajouter aux favoris\s*<\/button>/s';
$replacement = '<form method="post" action="../controle/index.php" style="display: inline;">
                                    <input type="hidden" name="do" value="add_to_favorites">
                                    <input type="hidden" name="type" value="formation">
                                    <input type="hidden" name="id_element" id="details_favoris_id">
                                    <button type="submit" class="btn-details">
                                        <i class="fas fa-star"></i> Ajouter aux favoris
                                    </button>';
$content = preg_replace($pattern, $replacement, $content);

// Sauvegarder les modifications
file_put_contents($filePath, $content);
echo "Les formulaires de favoris ont été mis à jour avec succès.\n";
