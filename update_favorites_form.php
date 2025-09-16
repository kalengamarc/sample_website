<?php
// Script pour mettre à jour le formulaire de favoris dans services.php

$filePath = __DIR__ . '/vue/services.php';
$content = file_get_contents($filePath);

// Remplacer le formulaire de favoris
$pattern = '/<form method="post" style="display: inline;">\s*<input type="hidden" name="action" value="ajouter_favoris">\s*<input type="hidden" name="id_service" value="(.*?)">\s*<button type="submit" class="icon fas fa-star (.*?)"\s*title="(.*?)">\s*<\/button>/s';
$replacement = '<?php
                                    // Vérifier si l\'utilisateur est connecté et si l\'élément est dans les favoris
                                    $isFavorite = false;
                                    if (isset($_SESSION[\'user_id\'])) {
                                        $checkFavorite = $favoriController->isFavorite($_SESSION[\'user_id\'], \'formation\', $1);
                                        $isFavorite = $checkFavorite[\'success\'] && $checkFavorite[\'is_favorite\'];
                                    }
                                    ?>
                                    <form method="post" action="../controle/index.php" style="display: inline;">
                                        <input type="hidden" name="do" value="toggle_favorite">
                                        <input type="hidden" name="type" value="formation">
                                        <input type="hidden" name="id_element" value="$1">
                                        <button type="submit" class="icon fas fa-star <?= $isFavorite ? \'favori-actif\' : \'\' ?>" 
                                                title="<?= $isFavorite ? \'Retirer des favoris\' : \'Ajouter aux favoris\' ?>">
                                        </button>';

$newContent = preg_replace($pattern, $replacement, $content);

if ($newContent !== $content) {
    file_put_contents($filePath, $newContent);
    echo "Le formulaire de favoris a été mis à jour avec succès.\n";
} else {
    echo "Aucune modification n'a été effectuée. Le motif de recherche n'a pas été trouvé.\n";
}
