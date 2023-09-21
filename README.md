# Package d'Intégration des Tags Criteo pour PHP

Ce package PHP simplifie l'intégration des tags Criteo dans vos pages PHP, ce qui vous permet de suivre et d'optimiser vos campagnes publicitaires avec Criteo plus facilement. Suivez les étapes ci-dessous pour commencer à utiliser ce package.

## Table des matières
- Installation
- Utilisation
- Exemple
- Contribution

## Installation

Pour installer ce package, vous pouvez utiliser Composer. Exécutez la commande suivante dans le répertoire de votre projet :
```bash
    composer require mmoutih/criteo-tracker
```

Assurez-vous d'inclure Composer dans votre projet si ce n'est pas déjà le cas.

## Utilisation

Dans vos fichiers PHP où vous souhaitez intégrer les tags Criteo, incluez simplement le package et appelez la méthode init de la classe CriteoLoader en passant votre ID Criteo. Voici un exemple :

```php
<?php
// index.php

require 'vendor/autoload.php';

use Mmoutih\CriteoTracker\CriteoLoader  ;

// Initialisez Criteo en passant votre ID Criteo
// Vous pouvez également passer l'email du visiteur (optionnel)
// et spécifier si l'email doit être hashé (optionnel)
// et spécifier le code postal (optionnel)
// et spécifier si la page visit est une simple page (optionnel)
// et spécifier aussi le type de device utilise soit 'd' pour desktop  ou 'm' pour mobile
CriteoLoader::init(idCriteo:'VOTRE_ID_CRITEO', 
clientEmail:'EMAIL_DU_VISITEUR', 
shouldHashEmail:true,
zipCode:'CODE_POSTAL',
isViewPage:true
siteType: 'd'
)

// Obtenez le code JavaScript pour inclure le fichier distant  Criteo
$header = $this->loader->getCriteoLoaderFile();
echo '<head>';
echo $header;
echo '</head>';

// Affichez le tag dans votre page HTML avant la fermeture du body 
echo  $loader->getCriteoTracingScript();

```

## Exemple

### Home view event

```php
<?php
// index.php

require 'vendor/autoload.php';

use Mmoutih\CriteoTracker\CriteoLoader  ;


// Initialisez Criteo en passant votre ID Criteo
$loader  = CriteoLoader::init('VOTRE_ID_CRITEO');

// Obtenez le code JavaScript pour inclure le fichier distant  Criteo
$header = $this->loader->getCriteoLoaderFile();
echo '<head>';
echo $header;
echo '</head>';

// Ensuite, utilisez d'autres méthodes pour générer des tags Criteo
$loader->viewHomePage();

// Affichez le tag dans votre page HTML avant la fermeture du body 

echo  $loader->getCriteoTracingScript(5000);

```

### Item view event

```php
<?php
// index.php

require 'vendor/autoload.php';

use Mmoutih\CriteoTracker\CriteoLoader  ;


// Initialisez Criteo en passant votre ID Criteo
$loader  = CriteoLoader::init('VOTRE_ID_CRITEO');

// Obtenez le code JavaScript pour inclure le fichier distant  Criteo
$header = $this->loader->getCriteoLoaderFile();
echo '<head>';
echo $header;
echo '</head>';

// Affichez la vue d'une page de produit en passant l'ID du produit
// ainsi que des options spécifiques à la page, par exemple les dates de check-in et de check-out
$loader->viewItemPage(itemId:'ID_DU_PRODUIT', 
    checkin: 'DATE_CHECKIN',
    checkout: 'DATE_CHECKOUT',
);

// Affichez le tag dans votre page HTML avant la fermeture du body 

echo  $loader->getCriteoTracingScript(5000);

```

### List view event

```php
<?php
// index.php

require 'vendor/autoload.php';

use Mmoutih\CriteoTracker\CriteoLoader  ;


// Initialisez Criteo en passant votre ID Criteo
$loader  = CriteoLoader::init('VOTRE_ID_CRITEO');

// Obtenez le code JavaScript pour inclure le fichier distant  Criteo
$header = $this->loader->getCriteoLoaderFile();
echo '<head>';
echo $header;
echo '</head>';

// Affichez la vue d'une page de listing en passant les ID des produits listés
// ainsi que des options spécifiques à la page, par exemple les dates de check-in et de check-out
// la categorie des produit listé et les keywords
$loader->viewItemPage(itemsIds:['PRODUCT_1','PRODUCT_2'], 
    checkin: 'DATE_CHECKIN',
    checkout: 'DATE_CHECKOUT',
    categoryId: 'CATEGORY',
    keywords: 'keywords describing page'
);

// Affichez le tag dans votre page HTML avant la fermeture du body 

echo  $loader->getCriteoTracingScript(5000);

```