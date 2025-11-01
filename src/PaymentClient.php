<?php

namespace MoneyFusion;

use Exception;

/**
 * Class PaymentClient
 *
 * SDK officiel pour interagir avec l'API Money Fusion Pay.
 * Permet de créer et de consulter un paiement facilement.
 *
 * Exemple d'utilisation :
 * -----------------------
 * use MoneyFusion\PaymentClient;
 *
 * $client = new PaymentClient("YourApiUrl");
 *
 * $payment = $client->createPayment(
 *     totalPrice: "10000",
 *     articles: [["name" => "Article 1", "price" => "5000", "quantity" => 1]],
 *     numeroSend: "0101010101",
 *     nomClient: "assemienDev",
 *     userId: 1,
 *     orderId: 123,
 *     returnUrl: "https://votre-domaine.com/callback",
 *     webhookUrl: "https://votre-domaine.com/webhook"
 * );
 *
 * print_r($payment);
 *
 * @package MoneyFusion
 */
class PaymentClient
{
    private string $apiKeyUrl;
    private array $headers;

    /**
     * Initialise le client de paiement
     *
     * @param string $apiKeyUrl URL de la clé API fournie par Money Fusion Pay
     */
    public function __construct(string $apiKeyUrl)
    {
        $this->apiKeyUrl = $apiKeyUrl;
        $this->headers = [
            'Content-Type: application/json'
        ];
    }

    /**
     * Crée un nouveau paiement avec les détails de la commande
     *
     * @param string $totalPrice Prix total de la commande
     * @param array $articles Liste des articles commandés
     * @param string $numeroSend Numéro de téléphone de l'expéditeur
     * @param string $nomClient Nom du client
     * @param int $userId Identifiant utilisateur
     * @param int $orderId Identifiant de la commande
     * @param string $returnUrl URL de redirection après paiement (https)
     * @param string $webhookUrl URL de notification du paiement en POST (https)
     * @return array Réponse de l’API
     * @throws Exception
     */
    public function createPayment(
        string $totalPrice,
        array $articles,
        string $numeroSend,
        string $nomClient,
        int $userId,
        int $orderId,
        string $returnUrl,
        string $webhookUrl
    ): array {
        $payload = [
            "totalPrice" => $totalPrice,
            "article" => $articles,
            "numeroSend" => $numeroSend,
            "nomclient" => $nomClient,
            "personal_Info" => [
                [
                    "userId" => $userId,
                    "orderId" => $orderId
                ]
            ],
            "return_url" => $returnUrl,
            "webhook_url" => $webhookUrl
        ];

        $ch = curl_init($this->apiKeyUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Erreur CURL : ' . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Récupère les informations détaillées d’un paiement existant
     *
     * @param string $paymentId Token unique du paiement
     * @return array Réponse contenant les détails du paiement
     * @throws Exception
     */
    public function getPayment(string $paymentId): array
    {
        $url = "https://www.pay.moneyfusion.net/paiementNotif/" . $paymentId;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Erreur CURL : ' . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}
