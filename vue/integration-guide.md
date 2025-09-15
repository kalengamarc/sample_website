# JosNet Features Integration Guide

## Overview
This guide explains how to integrate the comment, favorites, share, and shopping cart functionality into your JosNet application.

## Features Implemented

### 1. Comments System
- **Backend**: `controleur_commentaire.php` with full CRUD operations
- **Frontend**: JavaScript class with form handling and display
- **Database**: Comments stored with ratings, replies, and moderation

### 2. Favorites/Wishlist
- **Backend**: `controleur_favori.php` for managing user favorites
- **Frontend**: Toggle buttons with visual feedback
- **Database**: User-specific favorites for products and formations

### 3. Shopping Cart (Panier)
- **Backend**: `controleur_panier.php` with quantity management
- **Frontend**: Add to cart, update quantities, remove items
- **Database**: Persistent cart storage per user

### 4. Social Sharing
- **Backend**: URL generation for different platforms
- **Frontend**: Share buttons for Facebook, Twitter, WhatsApp, Email
- **Features**: Platform-specific URL formatting

## Backend Routes Added

All routes are accessible via `controle/index.php` with the `do` parameter:

### Comments
- `comment_create` - Add new comment
- `comment_get` - Get specific comment
- `comment_getByFormation` - Get comments for formation
- `comment_getByProduit` - Get comments for product
- `comment_update` - Update comment
- `comment_delete` - Delete comment

### Favorites
- `favori_add` - Add to favorites
- `favori_remove` - Remove from favorites
- `favori_getByUser` - Get user's favorites
- `favori_check` - Check if item is favorited

### Shopping Cart
- `panier_add` - Add product to cart
- `panier_update` - Update quantity
- `panier_remove` - Remove from cart
- `panier_get` - Get cart items
- `panier_clear` - Clear entire cart
- `panier_count` - Get cart item count

### Sharing
- `share_generate` - Generate share URLs

## Frontend Integration

### 1. Include Required Files

Add to your HTML pages:

```html
<link rel="stylesheet" href="styles/features.css">
<script src="javascript/features.js"></script>
```

### 2. HTML Structure Examples

#### Add to Cart Button
```html
<button class="add-to-cart-btn" data-product-id="1" data-quantity="1">
    <i class="fas fa-shopping-cart"></i>
    Ajouter au panier
</button>
```

#### Favorite Button
```html
<button class="favorite-btn" data-favorite-id="1" data-favorite-type="produit" title="Ajouter aux favoris">
    <i class="far fa-heart"></i>
</button>
```

#### Share Buttons
```html
<div class="share-buttons">
    <button class="share-btn facebook" data-share-type="produit" data-share-id="1" data-share-platform="facebook">
        <i class="fab fa-facebook-f"></i>
    </button>
    <button class="share-btn twitter" data-share-type="produit" data-share-id="1" data-share-platform="twitter">
        <i class="fab fa-twitter"></i>
    </button>
</div>
```

#### Comment Form
```html
<form class="comment-form" data-product-id="1">
    <input type="hidden" name="id_produit" value="1">
    <textarea name="commentaire" placeholder="Votre commentaire..." required></textarea>
    <div class="rating-input">
        <span class="star" data-rating="1"><i class="fas fa-star"></i></span>
        <span class="star" data-rating="2"><i class="fas fa-star"></i></span>
        <span class="star" data-rating="3"><i class="fas fa-star"></i></span>
        <span class="star" data-rating="4"><i class="fas fa-star"></i></span>
        <span class="star" data-rating="5"><i class="fas fa-star"></i></span>
    </div>
    <input type="hidden" name="note" value="">
    <button type="submit" class="comment-submit">Publier</button>
</form>
```

#### Comments Display Container
```html
<div id="comments-produit-1">
    <!-- Comments will be loaded here -->
</div>
```

#### Cart Display
```html
<div class="cart-icon">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-count">0</span>
</div>

<div id="cart-items">
    <!-- Cart items will be loaded here -->
</div>
<div class="cart-total">
    <h3>Total: <span id="cart-total">0.00€</span></h3>
</div>
```

### 3. JavaScript Usage

The `JosNetFeatures` class is automatically initialized. You can also call methods directly:

```javascript
// Add to cart
josnetFeatures.addToCart(productId, quantity);

// Toggle favorite
josnetFeatures.toggleFavorite(id, type, button);

// Load comments
josnetFeatures.loadComments(id, type);

// Share content
josnetFeatures.shareContent(type, id, platform, contentData);
```

## Database Requirements

Ensure these tables exist with the proper structure:

### Comments Table
```sql
CREATE TABLE commentaire (
    id_commentaire INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT,
    id_formation INT NULL,
    id_produit INT NULL,
    commentaire TEXT,
    note INT,
    date_commentaire DATETIME,
    statut VARCHAR(20),
    parent_id INT NULL
);
```

### Favorites Table
```sql
CREATE TABLE favori (
    id_favori INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT,
    id_formation INT NULL,
    id_produit INT NULL,
    date_ajout DATETIME
);
```

### Cart Table
```sql
CREATE TABLE panier (
    id_panier INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT,
    id_produit INT,
    quantite INT,
    date_ajout DATETIME
);
```

## Authentication Requirements

Most features require user authentication. The system checks for:
- `$_SESSION['user_id']` - Current user ID
- `$_SESSION['user_role']` - User role (for admin features)

## Error Handling

All backend operations return standardized responses:

```json
{
    "success": true/false,
    "message": "Status message",
    "data": {} // Response data if applicable
}
```

Frontend automatically displays notifications for user feedback.

## Customization

### CSS Variables
You can customize the appearance by modifying the CSS variables in `features.css`:

```css
:root {
    --primary-color: #05f07a;
    --secondary-color: #00d4aa;
    --error-color: #f44336;
    --success-color: #4CAF50;
}
```

### JavaScript Configuration
Modify the `JosNetFeatures` class constructor to change default settings:

```javascript
constructor() {
    this.baseUrl = '../controle/index.php';
    this.notificationDuration = 5000; // 5 seconds
    this.init();
}
```

## Testing

Use the demo page at `examples/features-demo.html` to test all functionality:

1. Open the demo page in your browser
2. Test each feature (comments, favorites, cart, share)
3. Check browser console for any errors
4. Verify database operations in your admin panel

## Troubleshooting

### Common Issues

1. **Features not working**: Ensure `features.js` is loaded after the DOM
2. **Authentication errors**: Check session management
3. **Database errors**: Verify table structure and permissions
4. **CSS not applied**: Check file paths and includes

### Debug Mode

Enable debug mode by adding to your PHP files:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Production Deployment

1. Minify CSS and JavaScript files
2. Enable PHP error logging (disable display_errors)
3. Set up proper database indexes
4. Configure caching for better performance
5. Test all features with real user accounts

## Support

For issues or questions about the features implementation, check:
1. Browser console for JavaScript errors
2. PHP error logs for backend issues
3. Database logs for query problems
4. Network tab for API request failures
