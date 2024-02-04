GIGS

toutes les gigs => index
```
SELECT * FROM `gigs`
```

un gig en fonction de son id => show
```
SELECT * FROM `gigs` WHERE id=1;
```

insérer un gig => store
```
INSERT INTO `gigs`(`id`, `user_id`, `category_id`, `title`, `picture`, `description`, `price`, `is_active`, `created_at`, `updated_at`) VALUES (80,2,3,'blabla','http://blabla','blabla',10.5,1,NOW(),NOW());
```

modifier un gig => update
```
UPDATE `gigs` SET `title`='lilili' WHERE id=80;
```

supprimer un gig => destroy
```
DELETE FROM `gigs` WHERE id=80;
```

3 derniers gigs crees => featuredGigs
```
SELECT * FROM `gigs` ORDER BY created_at DESC LIMIT 3;
```

tous les gigs en fonction d'une catégorie=> getGigsByCategory
```
SELECT * FROM `gigs` WHERE category_id = 1;
```

CATEGORY

toutes les catégories => index
```
SELECT * FROM `categories`;
```

une catégorie en fonction de son id => show
```
SELECT * FROM `categories` WHERE id=1;
```

insérer une catégorie => store
```
INSERT INTO `categories`(`id`, `name`, `picture`, `created_at`, `updated_at`) VALUES (9,'shooping','https://picsum.photos/2400/480?random=', NOW(), NOW());
```

update une categorie => update
```
UPDATE `categories` SET `name`='shoppingggggggg' WHERE id=9;
```

delete une catégorie => destroy
```
DELETE FROM `categories` WHERE id=9;
```


