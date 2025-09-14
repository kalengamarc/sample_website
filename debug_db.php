<?php
// Simple database test to check table structure and connection
include_once('modele/base.php');

try {
    $pdo = new Database();
    $crud = $pdo->getConnection();
    
    echo "<h2>Database Connection Test</h2>";
    echo "Connection: OK<br><br>";
    
    // Check if table exists and get structure
    echo "<h3>Table Structure for 'utilisateurs':</h3>";
    $stmt = $crud->query("DESCRIBE utilisateurs");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . $col['Key'] . "</td>";
        echo "<td>" . $col['Default'] . "</td>";
        echo "<td>" . $col['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Check recent users with photos
    echo "<h3>Recent Users with Photos:</h3>";
    $stmt = $crud->query("SELECT id_utilisateur, nom, prenom, role, photo, date_creation FROM utilisateurs WHERE role = 'formateur' ORDER BY date_creation DESC LIMIT 10");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Role</th><th>Photo</th><th>Date</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id_utilisateur'] . "</td>";
        echo "<td>" . $user['nom'] . "</td>";
        echo "<td>" . $user['prenom'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "<td>" . ($user['photo'] ?: 'VIDE') . "</td>";
        echo "<td>" . $user['date_creation'] . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Test insert with photo
    echo "<h3>Test Insert with Photo:</h3>";
    $testSql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role, description, date_creation, photo, specialite, id_formation) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $testParams = [
        'TestNom',
        'TestPrenom', 
        'test@example.com',
        password_hash('testpass', PASSWORD_BCRYPT),
        '1234567890',
        'formateur',
        'Test description',
        date('Y-m-d H:i:s'),
        'uploads/utilisateurs/test_image.jpg',
        'Test specialite',
        null
    ];
    
    echo "SQL: " . $testSql . "<br>";
    echo "Params: " . print_r($testParams, true) . "<br>";
    
    $stmt = $crud->prepare($testSql);
    $result = $stmt->execute($testParams);
    
    if ($result) {
        echo "✅ Test insert successful! ID: " . $crud->lastInsertId() . "<br>";
        
        // Clean up test data
        $crud->exec("DELETE FROM utilisateurs WHERE email = 'test@example.com'");
        echo "Test data cleaned up.<br>";
    } else {
        echo "❌ Test insert failed!<br>";
        $errorInfo = $stmt->errorInfo();
        echo "Error: " . print_r($errorInfo, true) . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage();
}
?>
