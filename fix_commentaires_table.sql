-- Fix commentaires table to allow NULL values for id_formation
-- This allows comments to be associated with either formations OR products

ALTER TABLE `commentaires` 
MODIFY COLUMN `id_formation` int NULL;

-- Also ensure id_produit allows NULL (it should already)
ALTER TABLE `commentaires` 
MODIFY COLUMN `id_produit` int NULL;

-- Add a constraint to ensure at least one of id_formation or id_produit is not null
ALTER TABLE `commentaires` 
ADD CONSTRAINT `chk_formation_or_product` 
CHECK ((`id_formation` IS NOT NULL) OR (`id_produit` IS NOT NULL));
