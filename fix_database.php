<?php
// Script pour corriger la structure de la table commentaires
// Permet aux commentaires d'être associés soit aux formations soit aux produits

require_once 'modele/base.php';

try {
    $db = new DataBase();
    $pdo = $db->getConnection();
    
    echo "Début de la correction de la table commentaires...\n";
    
    // 1. Modifier id_formation pour permettre NULL
    $sql1 = "ALTER TABLE `commentaires` MODIFY COLUMN `id_formation` int NULL";
    $pdo->exec($sql1);
    echo "✓ Colonne id_formation modifiée pour permettre NULL\n";
    
    // 2. S'assurer que id_produit permet NULL (devrait déjà être le cas)
    $sql2 = "ALTER TABLE `commentaires` MODIFY COLUMN `id_produit` int NULL";
    $pdo->exec($sql2);
    echo "✓ Colonne id_produit confirmée pour permettre NULL\n";
    
    // 3. Ajouter une contrainte pour s'assurer qu'au moins un des deux (formation ou produit) est renseigné
    try {
        // D'abord supprimer la contrainte existante si elle existe
        try {
            $pdo->exec("ALTER TABLE `commentaires` DROP CONSTRAINT `chk_formation_or_product`");
            echo "✓ Ancienne contrainte supprimée\n";
        } catch (PDOException $e) {
            // Ignore si la contrainte n'existe pas
        }
        
        $sql3 = "ALTER TABLE `commentaires` ADD CONSTRAINT `chk_formation_or_product` CHECK ((`id_formation` IS NOT NULL) OR (`id_produit` IS NOT NULL))";
        $pdo->exec($sql3);
        echo "✓ Contrainte ajoutée pour s'assurer qu'au moins formation ou produit est renseigné\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false) {
            echo "⚠ Contrainte déjà existante, ignorée\n";
        } else {
            echo "⚠ Erreur contrainte: " . $e->getMessage() . " (continuons...)\n";
        }
    }
    
    // 4. Ajouter la colonne note_moyenne à la table formations si elle n'existe pas
    try {
        $sql4 = "ALTER TABLE `formations` ADD COLUMN `note_moyenne` DECIMAL(3,2) DEFAULT 0.00";
        $pdo->exec($sql4);
        echo "✓ Colonne note_moyenne ajoutée à la table formations\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "⚠ Colonne note_moyenne déjà existante dans formations, ignorée\n";
        } else {
            echo "⚠ Erreur ajout colonne note_moyenne: " . $e->getMessage() . " (continuons...)\n";
        }
    }
    
    echo "\n✅ Correction de la base de données terminée avec succès!\n";
    echo "Les commentaires peuvent maintenant être associés aux formations OU aux produits.\n";
    echo "La colonne note_moyenne a été ajoutée à la table formations.\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur lors de la correction de la base de données: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    exit(1);
}
?>
