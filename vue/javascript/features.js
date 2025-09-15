// JosNet Features JavaScript - Comments, Favorites, Share, Shopping Cart
// Author: JosNet Development Team

class JosNetFeatures {
    constructor() {
        this.baseUrl = '../controle/index.php';
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCartCount();
    }

    // Utility function for AJAX requests
    async makeRequest(action, data = {}, method = 'POST') {
        const formData = new FormData();
        formData.append('do', action);
        
        Object.keys(data).forEach(key => {
            formData.append(key, data[key]);
        });

        try {
            const response = await fetch(this.baseUrl, {
                method: method,
                body: formData
            });
            return await response.json();
        } catch (error) {
            console.error('Request failed:', error);
            return { success: false, message: 'Erreur de connexion' };
        }
    }

    // ========== COMMENTS FUNCTIONALITY ==========
    
    async addComment(formData) {
        const result = await this.makeRequest('comment_create', formData);
        
        if (result.success) {
            this.showNotification('Commentaire ajouté avec succès!', 'success');
            this.refreshComments(formData.id_produit || formData.id_formation, formData.id_produit ? 'produit' : 'formation');
        } else {
            this.showNotification(result.message || 'Erreur lors de l\'ajout du commentaire', 'error');
        }
        
        return result;
    }

    async loadComments(id, type) {
        const action = type === 'produit' ? 'comment_getByProduit' : 'comment_getByFormation';
        const param = type === 'produit' ? 'id_produit' : 'id_formation';
        
        const result = await this.makeRequest(action, { [param]: id });
        
        if (result.success) {
            this.displayComments(result.data, id, type);
        }
        
        return result;
    }

    displayComments(comments, id, type) {
        const container = document.getElementById(`comments-${type}-${id}`);
        if (!container) return;

        let html = '<div class="comments-section">';
        
        if (comments && comments.length > 0) {
            comments.forEach(comment => {
                html += this.generateCommentHTML(comment);
            });
        } else {
            html += '<p class="no-comments">Aucun commentaire pour le moment.</p>';
        }
        
        html += '</div>';
        container.innerHTML = html;
    }

    generateCommentHTML(comment) {
        const stars = this.generateStars(comment.note);
        const date = new Date(comment.date_commentaire).toLocaleDateString('fr-FR');
        
        return `
            <div class="comment-item" data-comment-id="${comment.id_commentaire}">
                <div class="comment-header">
                    <span class="comment-author">${comment.nom_utilisateur || 'Utilisateur'}</span>
                    <span class="comment-date">${date}</span>
                    ${comment.note ? `<div class="comment-rating">${stars}</div>` : ''}
                </div>
                <div class="comment-content">${comment.commentaire}</div>
                <div class="comment-actions">
                    <button onclick="josnetFeatures.replyToComment(${comment.id_commentaire})" class="reply-btn">
                        <i class="fas fa-reply"></i> Répondre
                    </button>
                </div>
            </div>
        `;
    }

    generateStars(rating) {
        if (!rating) return '';
        
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="fas fa-star ${i <= rating ? 'active' : ''}"></i>`;
        }
        return stars;
    }

    async refreshComments(id, type) {
        await this.loadComments(id, type);
    }

    // ========== FAVORITES FUNCTIONALITY ==========
    
    async toggleFavorite(id, type, button) {
        const isFavorite = button.classList.contains('favorited');
        const action = isFavorite ? 'favori_remove' : 'favori_add';
        
        const result = await this.makeRequest(action, {
            type: type,
            id_element: id
        });
        
        if (result.success) {
            this.updateFavoriteButton(button, !isFavorite);
            const message = isFavorite ? 'Retiré des favoris' : 'Ajouté aux favoris';
            this.showNotification(message, 'success');
        } else {
            this.showNotification(result.message || 'Erreur lors de la mise à jour des favoris', 'error');
        }
        
        return result;
    }

    updateFavoriteButton(button, isFavorite) {
        const icon = button.querySelector('i');
        
        if (isFavorite) {
            button.classList.add('favorited');
            icon.classList.remove('far');
            icon.classList.add('fas');
            button.title = 'Retirer des favoris';
        } else {
            button.classList.remove('favorited');
            icon.classList.remove('fas');
            icon.classList.add('far');
            button.title = 'Ajouter aux favoris';
        }
    }

    async loadUserFavorites() {
        const result = await this.makeRequest('favori_getByUser');
        
        if (result.success) {
            this.updateFavoriteButtons(result.data);
        }
        
        return result;
    }

    updateFavoriteButtons(favorites) {
        favorites.forEach(favorite => {
            const button = document.querySelector(`[data-favorite-id="${favorite.id_element}"][data-favorite-type="${favorite.type}"]`);
            if (button) {
                this.updateFavoriteButton(button, true);
            }
        });
    }

    // ========== SHOPPING CART FUNCTIONALITY ==========
    
    async addToCart(productId, quantity = 1, button = null) {
        const result = await this.makeRequest('panier_add', {
            id_produit: productId,
            quantite: quantity
        });
        
        if (result.success) {
            this.showNotification('Produit ajouté au panier!', 'success');
            this.loadCartCount();
            this.updateCartButton(button, true);
        } else {
            this.showNotification(result.message || 'Erreur lors de l\'ajout au panier', 'error');
        }
        
        return result;
    }

    async updateCartQuantity(cartId, quantity) {
        const result = await this.makeRequest('panier_update', {
            id_panier: cartId,
            quantite: quantity
        });
        
        if (result.success) {
            this.showNotification('Quantité mise à jour', 'success');
            this.loadCartCount();
        } else {
            this.showNotification(result.message || 'Erreur lors de la mise à jour', 'error');
        }
        
        return result;
    }

    async removeFromCart(cartId) {
        const result = await this.makeRequest('panier_remove', {
            id_panier: cartId
        });
        
        if (result.success) {
            this.showNotification('Produit retiré du panier', 'success');
            this.loadCartCount();
            this.refreshCartDisplay();
        } else {
            this.showNotification(result.message || 'Erreur lors de la suppression', 'error');
        }
        
        return result;
    }

    async loadCartCount() {
        const result = await this.makeRequest('panier_count');
        
        if (result.success) {
            this.updateCartCountDisplay(result.data.count || 0);
        }
        
        return result;
    }

    updateCartCountDisplay(count) {
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(element => {
            element.textContent = count;
            element.style.display = count > 0 ? 'inline' : 'none';
        });
    }

    updateCartButton(button, added) {
        if (!button) return;
        
        if (added) {
            button.innerHTML = '<i class="fas fa-check"></i> Ajouté';
            button.classList.add('added');
            setTimeout(() => {
                button.innerHTML = '<i class="fas fa-shopping-cart"></i> Ajouter au panier';
                button.classList.remove('added');
            }, 2000);
        }
    }

    async refreshCartDisplay() {
        const result = await this.makeRequest('panier_get');
        
        if (result.success) {
            this.displayCartItems(result.data);
        }
        
        return result;
    }

    displayCartItems(items) {
        const container = document.getElementById('cart-items');
        if (!container) return;

        let html = '';
        let total = 0;
        
        if (items && items.length > 0) {
            items.forEach(item => {
                const itemTotal = item.prix * item.quantite;
                total += itemTotal;
                
                html += `
                    <div class="cart-item" data-cart-id="${item.id_panier}">
                        <div class="item-image">
                            <img src="${item.photo || 'images/default-product.jpg'}" alt="${item.nom}">
                        </div>
                        <div class="item-details">
                            <h4>${item.nom}</h4>
                            <p class="item-price">${item.prix}€</p>
                        </div>
                        <div class="item-quantity">
                            <button onclick="josnetFeatures.updateCartQuantity(${item.id_panier}, ${item.quantite - 1})" ${item.quantite <= 1 ? 'disabled' : ''}>-</button>
                            <span>${item.quantite}</span>
                            <button onclick="josnetFeatures.updateCartQuantity(${item.id_panier}, ${item.quantite + 1})">+</button>
                        </div>
                        <div class="item-total">${itemTotal.toFixed(2)}€</div>
                        <button onclick="josnetFeatures.removeFromCart(${item.id_panier})" class="remove-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });
        } else {
            html = '<p class="empty-cart">Votre panier est vide</p>';
        }
        
        container.innerHTML = html;
        
        const totalElement = document.getElementById('cart-total');
        if (totalElement) {
            totalElement.textContent = `${total.toFixed(2)}€`;
        }
    }

    // ========== SHARE FUNCTIONALITY ==========
    
    async shareContent(type, id, platform, contentData) {
        const result = await this.makeRequest('share_generate', {
            type: type,
            id: id,
            platform: platform,
            url: contentData.url,
            title: contentData.title,
            description: contentData.description,
            image: contentData.image
        });
        
        if (result.success) {
            this.openShareWindow(result.data.share_url, platform);
        } else {
            this.showNotification('Erreur lors du partage', 'error');
        }
        
        return result;
    }

    openShareWindow(url, platform) {
        if (platform === 'email') {
            window.location.href = url;
        } else {
            const width = 600;
            const height = 400;
            const left = (window.innerWidth - width) / 2;
            const top = (window.innerHeight - height) / 2;
            
            window.open(url, 'share', `width=${width},height=${height},left=${left},top=${top}`);
        }
    }

    // ========== EVENT BINDING ==========
    
    bindEvents() {
        // Comment form submissions
        document.addEventListener('submit', (e) => {
            if (e.target.classList.contains('comment-form')) {
                e.preventDefault();
                this.handleCommentSubmit(e.target);
            }
        });

        // Favorite buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.favorite-btn')) {
                e.preventDefault();
                const button = e.target.closest('.favorite-btn');
                const id = button.dataset.favoriteId;
                const type = button.dataset.favoriteType;
                this.toggleFavorite(id, type, button);
            }
        });

        // Add to cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.add-to-cart-btn')) {
                e.preventDefault();
                const button = e.target.closest('.add-to-cart-btn');
                const productId = button.dataset.productId;
                const quantity = button.dataset.quantity || 1;
                this.addToCart(productId, quantity, button);
            }
        });

        // Share buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.share-btn')) {
                e.preventDefault();
                const button = e.target.closest('.share-btn');
                this.handleShare(button);
            }
        });
    }

    handleCommentSubmit(form) {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        this.addComment(data).then(() => {
            form.reset();
        });
    }

    handleShare(button) {
        const type = button.dataset.shareType;
        const id = button.dataset.shareId;
        const platform = button.dataset.sharePlatform;
        
        const contentData = {
            url: window.location.href,
            title: document.title,
            description: document.querySelector('meta[name="description"]')?.content || '',
            image: document.querySelector('meta[property="og:image"]')?.content || ''
        };
        
        this.shareContent(type, id, platform, contentData);
    }

    // ========== UTILITY FUNCTIONS ==========
    
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <span>${message}</span>
            <button onclick="this.parentElement.remove()">&times;</button>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    // Initialize on page load
    static init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                window.josnetFeatures = new JosNetFeatures();
            });
        } else {
            window.josnetFeatures = new JosNetFeatures();
        }
    }
}

// Auto-initialize
JosNetFeatures.init();

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = JosNetFeatures;
}
