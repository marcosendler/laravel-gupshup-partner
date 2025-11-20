<?php

namespace GupshupPartner;

class MessageManagement
{
    protected GupshupPartnerClient $client;

    public function __construct(GupshupPartnerClient $client)
    {
        $this->client = $client;
    }

    /**
     * Envia mensagem usando template de texto
     */
    public function sendTextTemplate(string $appId, string $destination, string $templateId, array $params = []): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post('/partner/app/message/send', [
            'appId' => $appId,
            'destination' => $destination,
            'templateId' => $templateId,
            'params' => $params,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Envia mensagem usando template com imagem
     */
    public function sendImageTemplate(string $appId, string $destination, string $templateId, array $params = [], ?string $imageUrl = null): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = [
            'appId' => $appId,
            'destination' => $destination,
            'templateId' => $templateId,
            'params' => $params,
        ];

        if ($imageUrl) {
            $payload['imageUrl'] = $imageUrl;
        }

        return $this->client->post('/partner/app/message/send', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Envia mensagem usando template com vídeo
     */
    public function sendVideoTemplate(string $appId, string $destination, string $templateId, array $params = [], ?string $videoUrl = null): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = [
            'appId' => $appId,
            'destination' => $destination,
            'templateId' => $templateId,
            'params' => $params,
        ];

        if ($videoUrl) {
            $payload['videoUrl'] = $videoUrl;
        }

        return $this->client->post('/partner/app/message/send', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Envia mensagem usando template com documento
     */
    public function sendDocumentTemplate(string $appId, string $destination, string $templateId, array $params = [], ?string $documentUrl = null): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = [
            'appId' => $appId,
            'destination' => $destination,
            'templateId' => $templateId,
            'params' => $params,
        ];

        if ($documentUrl) {
            $payload['documentUrl'] = $documentUrl;
        }

        return $this->client->post('/partner/app/message/send', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Envia mensagem usando template com localização
     */
    public function sendLocationTemplate(string $appId, string $destination, string $templateId, array $params = [], array $location = []): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = [
            'appId' => $appId,
            'destination' => $destination,
            'templateId' => $templateId,
            'params' => $params,
        ];

        if (!empty($location)) {
            $payload['location'] = $location;
        }

        return $this->client->post('/partner/app/message/send', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Envia mensagem usando template de catálogo
     */
    public function sendCatalogTemplate(string $appId, string $destination, string $templateId, array $params = []): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post('/partner/app/message/send', [
            'appId' => $appId,
            'destination' => $destination,
            'templateId' => $templateId,
            'params' => $params,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Envia mensagem usando template carrossel
     */
    public function sendCarouselTemplate(string $appId, string $destination, string $templateId, array $cards = []): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post('/partner/app/message/send', [
            'appId' => $appId,
            'destination' => $destination,
            'templateId' => $templateId,
            'cards' => $cards,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Envia mensagem LTO (Limited Time Offer)
     */
    public function sendLTO(string $appId, string $destination, string $templateId, array $params = [], array $ltoData = []): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = [
            'appId' => $appId,
            'destination' => $destination,
            'templateId' => $templateId,
            'params' => $params,
        ];

        if (!empty($ltoData)) {
            $payload['ltoData'] = $ltoData;
        }

        return $this->client->post('/partner/app/message/send', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Envia mensagem de produto
     */
    public function sendProduct(string $appId, string $destination, array $product): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post('/partner/app/message/product', [
            'appId' => $appId,
            'destination' => $destination,
            'product' => $product,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Envia mensagem de sessão CTA (Call To Action)
     */
    public function sendCTASession(string $appId, string $destination, array $ctaData): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post('/partner/app/message/cta', [
            'appId' => $appId,
            'destination' => $destination,
            'ctaData' => $ctaData,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Gera Media ID para upload de mídia
     */
    public function generateMediaId(string $appId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post('/partner/app/media/generate', [
            'appId' => $appId,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Faz upload de mídia
     */
    public function uploadMedia(string $appId, string $mediaId, string $filePath): array
    {
        $token = $this->client->apps()->getToken($appId);

        // Aqui você precisaria implementar o upload de arquivo
        // usando Http::attach() do Laravel
        throw new \Exception('Método uploadMedia precisa ser implementado com Http::attach()');
    }

    /**
     * Define período de validade para templates utility
     */
    public function setUtilityValidity(string $appId, string $templateId, int $validityMinutes): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post('/partner/app/template/validity', [
            'appId' => $appId,
            'templateId' => $templateId,
            'validity' => $validityMinutes,
            'category' => 'UTILITY',
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Define período de validade para templates authentication
     */
    public function setAuthenticationValidity(string $appId, string $templateId, int $validityMinutes): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post('/partner/app/template/validity', [
            'appId' => $appId,
            'templateId' => $templateId,
            'validity' => $validityMinutes,
            'category' => 'AUTHENTICATION',
        ], [
            'apikey' => $token,
        ]);
    }
}
