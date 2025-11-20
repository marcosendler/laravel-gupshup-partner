<?php

namespace GupshupPartner;

use Illuminate\Support\Facades\Cache;

class AppManagement
{
    protected GupshupPartnerClient $client;

    public function __construct(GupshupPartnerClient $client)
    {
        $this->client = $client;
    }

    /**
     * Lista todos os apps vinculados ao parceiro
     */
    public function list(): array
    {
        return $this->client->get('/partner/account/api/partnerApps');
    }

    /**
     * Vincula um app ao parceiro
     */
    public function link(string $apiKey, string $appName): array
    {
        return $this->client->post('/partner/account/api/appLink', [
            'apiKey' => $apiKey,
            'appName' => $appName,
        ]);
    }

    /**
     * Obtém token de acesso para um app específico
     */
    public function getToken(string $appId, bool $forceRefresh = false): string
    {
        $cacheKey = "gupshup_app_token_{$appId}";

        if (!$forceRefresh && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = $this->client->get("/partner/app/{$appId}/token/");

        $token = $response['token']['token'] ?? null;

        if (!$token) {
            throw new Exceptions\GupshupPartnerException('Token não encontrado na resposta');
        }

        // Cache por 23 horas
        Cache::put($cacheKey, $token, now()->addHours(23));

        return $token;
    }

    /**
     * Obtém detalhes de um app específico
     */
    public function get(string $appId): array
    {
        $apps = $this->list();

        foreach ($apps['partnerAppsList'] ?? [] as $app) {
            if ($app['id'] === $appId) {
                return $app;
            }
        }

        throw new Exceptions\GupshupPartnerException("App {$appId} não encontrado");
    }

    /**
     * Habilita/Desabilita template messaging para um app
     */
    public function toggleTemplateMessaging(string $appId, bool $enable): array
    {
        return $this->client->post('/partner/app/toggleTemplateMessaging', [
            'appId' => $appId,
            'enable' => $enable ? 'true' : 'false',
        ]);
    }

    /**
     * Verifica Quality Rating e Messaging Limits de um app
     */
    public function getQualityAndLimits(string $appId): array
    {
        return $this->client->get("/partner/app/{$appId}/qualityRating");
    }

    /**
     * Marca app para migração
     */
    public function markForMigration(string $appId): array
    {
        return $this->client->post("/partner/app/{$appId}/markForMigration");
    }

    /**
     * Deleta um app sandbox
     */
    public function deleteSandbox(string $appId): array
    {
        return $this->client->delete("/partner/app/{$appId}/sandbox");
    }

    /**
     * Obtém detalhes do negócio (business)
     */
    public function getBusinessDetails(string $appId): array
    {
        $token = $this->getToken($appId);

        return $this->client->get("/partner/app/{$appId}/business", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém detalhes do perfil
     */
    public function getProfile(string $appId): array
    {
        $token = $this->getToken($appId);

        return $this->client->get("/partner/app/{$appId}/profile", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém a foto do perfil
     */
    public function getProfilePhoto(string $appId): array
    {
        $token = $this->getToken($appId);

        return $this->client->get("/partner/app/{$appId}/profile/photo", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Deleta a foto do perfil
     */
    public function deleteProfilePhoto(string $appId): array
    {
        $token = $this->getToken($appId);

        return $this->client->delete("/partner/app/{$appId}/profile/photo", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Configura ice breakers (até 4)
     */
    public function setIceBreakers(string $appId, array $iceBreakers): array
    {
        if (count($iceBreakers) > 4) {
            throw new Exceptions\GupshupPartnerException('Máximo de 4 ice breakers permitidos');
        }

        foreach ($iceBreakers as $breaker) {
            if (strlen($breaker) > 80) {
                throw new Exceptions\GupshupPartnerException('Ice breaker não pode ter mais de 80 caracteres');
            }
        }

        $token = $this->getToken($appId);

        return $this->client->post("/partner/app/{$appId}/icebreakers", [
            'iceBreakers' => $iceBreakers,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Envia mensagem de boas-vindas
     */
    public function sendWelcomeMessage(string $appId, string $message): array
    {
        $token = $this->getToken($appId);

        return $this->client->post("/partner/app/{$appId}/welcomeMessage", [
            'message' => $message,
        ], [
            'apikey' => $token,
        ]);
    }
}
