#  Money Fusion Payment - PHP SDK

Cette bibliothèque PHP permet d’interagir facilement avec l’API de paiement **Money Fusion**.
Elle facilite la **création** et la **récupération** des paiements via des requêtes HTTP sécurisées.

---

##  Installation

Installez la bibliothèque via **Composer** :

```bash
composer require assemien-dev/money-fusion-php
```

---

##  Utilisation

### Importation

```php
use MoneyFusion\PaymentClient;
```

---

### Initialisation du client

```php
$client = new PaymentClient("YOUR_API_URL");
```

---

### Créer un paiement

```php
$payment = $client->createPayment(
    totalPrice: "10000",
    articles: [
        ["name" => "Article 1", "price" => "5000", "quantity" => 1]
    ],
    numeroSend: "0101010101",
    nomClient: "assemienDev",
    userId: 1,
    orderId: 123,
    returnUrl: "https://votre-domaine.com/callback",
    webhookUrl: "https://your-domain.com/my-webhook-url"
);

print_r($payment);
```

> ⚠️ **Note :**
>
> * `webhookUrl` doit **accepter les requêtes POST**.
> * `returnUrl` et `webhookUrl` doivent être **en HTTPS** avec un **nom de domaine valide**.

---

###  Réponse attendue

```json
{
    "statut": true,
    "token": "f5EJ33JYmxqosi8BvaEt",
    "message": "paiement en cours",
    "url": "https://www.pay.moneyfusion.net/pay/f5EJ33JYmxqosi8BvaEt"
}
```

---

###  Récupérer un paiement

```php
$paymentInfo = $client->getPayment("8L5teSc5TaIkP3ds9Dlx");
print_r($paymentInfo);
```

---

###  Réponse attendue

```json
{
    "statut": true,
    "data": {
        "_id": "6748d365967cb4766fdd1616",
        "tokenPay": "8L5teSc5TaIkP3ds9Dlx",
        "numeroSend": "None",
        "nomclient": "assemienDev",
        "personal_Info": [{"userId": 1, "orderId": 123}],
        "numeroTransaction": "",
        "Montant": 475,
        "frais": 25,
        "statut": "paid",
        "moyen": "card",
        "return_url": "https://votre-domaine.com/callback",
        "createdAt": "2024-11-28T20:32:37.037Z"
    },
    "message": "details paiement"
}
```

---

##  Exemple complet

```php
require 'vendor/autoload.php';

use MoneyFusion\PaymentClient;

$client = new PaymentClient("https://api.moneyfusion.net/api/create-payment");

try {
    $payment = $client->createPayment(
        totalPrice: "10000",
        articles: [
            ["name" => "Article 1", "price" => "5000", "quantity" => 1]
        ],
        numeroSend: "0101010101",
        nomClient: "assemienDev",
        userId: 1,
        orderId: 123,
        returnUrl: "https://votre-domaine.com/callback",
        webhookUrl: "https://votre-domaine.com/webhook"
    );

    print_r($payment);

    // Exemple : récupération du paiement après confirmation
    $paymentInfo = $client->getPayment($payment['token']);
    print_r($paymentInfo);

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
```

---

##  Méthodes disponibles

| Méthode           | Description                                                   |
| ----------------- | ------------------------------------------------------------- |
| `createPayment()` | Crée un nouveau paiement avec les détails de la commande      |
| `getPayment()`    | Récupère les informations d’un paiement à partir de son token |

---