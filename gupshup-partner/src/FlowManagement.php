<?php

namespace GupshupPartner;

class FlowManagement
{
    protected GupshupPartnerClient $client;

    public function __construct(GupshupPartnerClient $client)
    {
        $this->client = $client;
    }

    /**
     * Lista todos os flows de um app
     */
    public function list(string $appId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get("/partner/app/{$appId}/flows", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Cria um novo flow
     */
    public function create(string $appId, string $name, array $categories = []): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post("/partner/app/{$appId}/flow", [
            'name' => $name,
            'categories' => $categories,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Atualiza um flow existente
     */
    public function update(string $appId, string $flowId, array $data): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->put("/partner/app/{$appId}/flow/{$flowId}", $data, [
            'apikey' => $token,
        ]);
    }

    /**
     * Atualiza o JSON de um flow
     */
    public function updateJson(string $appId, string $flowId, array $json): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post("/partner/app/{$appId}/flow/{$flowId}/json", [
            'json' => json_encode($json),
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém o JSON de um flow
     */
    public function getJson(string $appId, string $flowId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get("/partner/app/{$appId}/flow/{$flowId}/json", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém URL de preview do flow
     */
    public function getPreviewUrl(string $appId, string $flowId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get("/partner/app/{$appId}/flow/{$flowId}/preview", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Deprecia um flow
     */
    public function deprecate(string $appId, string $flowId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post("/partner/app/{$appId}/flow/{$flowId}/deprecate", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Deleta um flow
     */
    public function delete(string $appId, string $flowId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->delete("/partner/app/{$appId}/flow/{$flowId}", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Publica um flow
     */
    public function publish(string $appId, string $flowId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post("/partner/app/{$appId}/flow/{$flowId}/publish", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém detalhes de um flow específico
     */
    public function get(string $appId, string $flowId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get("/partner/app/{$appId}/flow/{$flowId}", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Gerencia subscrições de flow
     */
    public function subscribe(string $appId, string $flowId, array $subscriptionData): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->post("/partner/app/{$appId}/flow/{$flowId}/subscribe", $subscriptionData, [
            'apikey' => $token,
        ]);
    }

    /**
     * Remove subscrição específica
     */
    public function unsubscribe(string $appId, string $flowId, string $subscriptionId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->delete("/partner/app/{$appId}/flow/{$flowId}/subscription/{$subscriptionId}", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Remove todas as subscrições de um flow
     */
    public function unsubscribeAll(string $appId, string $flowId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->delete("/partner/app/{$appId}/flow/{$flowId}/subscriptions", [], [
            'apikey' => $token,
        ]);
    }

    /**
     * Importa flow do Meta Playground JSON
     */
    public function importFromMeta(string $appId, string $name, string $metaJsonPath): array
    {
        $jsonContent = file_get_contents($metaJsonPath);
        $jsonData = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exceptions\GupshupPartnerException('JSON inválido: ' . json_last_error_msg());
        }

        // Criar flow
        $flow = $this->create($appId, $name);
        $flowId = $flow['flow']['id'] ?? null;

        if (!$flowId) {
            throw new Exceptions\GupshupPartnerException('Falha ao criar flow');
        }

        // Atualizar com o JSON
        return $this->updateJson($appId, $flowId, $jsonData);
    }
}
