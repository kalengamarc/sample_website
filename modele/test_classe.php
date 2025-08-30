<script>
// Fonction debug pour tester la connexion API
async function testAPIConnection() {
    console.log('Testing API connection...');
    
    try {
        // Test simple sans données
        const response = await fetch('/controle/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'entity': 'utilisateur',
                'action': 'getAll'
            })
        });
        
        const result = await response.json();
        console.log('API Response:', result);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return result;
        
    } catch (error) {
        console.error('API Connection failed:', error);
        showNotification('Impossible de se connecter au serveur. Vérifiez votre connexion.', 'error');
        return null;
    }
}

// Appeler le test au chargement
document.addEventListener('DOMContentLoaded', function() {
    testAPIConnection();
});
</script>