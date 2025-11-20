<?php

namespace GupshupPartner;

class TemplateManagement
{
    protected GupshupPartnerClient $client;

    public function __construct(GupshupPartnerClient $client)
    {
        $this->client = $client;
    }

    /**
     * Lista todos os templates de um app
     */
    public function list(string $appId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get('/partner/app/template/list', [
            'appId' => $appId,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém um template específico pelo ID
     */
    public function get(string $appId, string $templateId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get("/partner/app/template/{$templateId}", [
            'appId' => $appId,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Cria template de texto
     */
    public function createText(string $appId, array $data): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = array_merge([
            'appId' => $appId,
            'templateType' => 'TEXT',
        ], $data);

        return $this->client->post('/partner/app/template', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Cria template com imagem
     */
    public function createImage(string $appId, array $data): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = array_merge([
            'appId' => $appId,
            'templateType' => 'IMAGE',
        ], $data);

        return $this->client->post('/partner/app/template', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Cria template com vídeo
     */
    public function createVideo(string $appId, array $data): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = array_merge([
            'appId' => $appId,
            'templateType' => 'VIDEO',
        ], $data);

        return $this->client->post('/partner/app/template', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Cria template com documento
     */
    public function createDocument(string $appId, array $data): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = array_merge([
            'appId' => $appId,
            'templateType' => 'DOCUMENT',
        ], $data);

        return $this->client->post('/partner/app/template', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Cria template com localização
     */
    public function createLocation(string $appId, array $data): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = array_merge([
            'appId' => $appId,
            'templateType' => 'LOCATION',
        ], $data);

        return $this->client->post('/partner/app/template', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Cria template de catálogo
     */
    public function createCatalog(string $appId, array $data): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = array_merge([
            'appId' => $appId,
            'templateType' => 'CATALOG',
        ], $data);

        return $this->client->post('/partner/app/template', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Cria template carrossel (imagem)
     */
    public function createCarouselImage(string $appId, array $data): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = array_merge([
            'appId' => $appId,
            'templateType' => 'CAROUSEL',
            'carouselType' => 'IMAGE',
        ], $data);

        return $this->client->post('/partner/app/template', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Cria template carrossel (vídeo)
     */
    public function createCarouselVideo(string $appId, array $data): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = array_merge([
            'appId' => $appId,
            'templateType' => 'CAROUSEL',
            'carouselType' => 'VIDEO',
        ], $data);

        return $this->client->post('/partner/app/template', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Cria template de autenticação
     */
    public function createAuthentication(string $appId, array $data): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = array_merge([
            'appId' => $appId,
            'category' => 'AUTHENTICATION',
        ], $data);

        return $this->client->post('/partner/app/template', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Edita um template existente
     */
    public function edit(string $appId, string $templateId, array $data): array
    {
        $token = $this->client->apps()->getToken($appId);

        $payload = array_merge([
            'appId' => $appId,
            'templateId' => $templateId,
        ], $data);

        return $this->client->put('/partner/app/template', $payload, [
            'apikey' => $token,
        ]);
    }

    /**
     * Deleta um template pelo ID
     */
    public function delete(string $appId, string $templateId): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->delete("/partner/app/template/{$templateId}", [
            'appId' => $appId,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Deleta um template pelo ID e nome do elemento
     */
    public function deleteByElementName(string $appId, string $templateId, string $elementName): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->delete('/partner/app/template', [
            'appId' => $appId,
            'templateId' => $templateId,
            'elementName' => $elementName,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Filtra templates por status
     */
    public function filterByStatus(string $appId, string $status): array
    {
        $templates = $this->list($appId);

        return array_filter($templates['templates'] ?? [], function ($template) use ($status) {
            return strtoupper($template['status']) === strtoupper($status);
        });
    }

    /**
     * Filtra templates por categoria
     */
    public function filterByCategory(string $appId, string $category): array
    {
        $templates = $this->list($appId);

        return array_filter($templates['templates'] ?? [], function ($template) use ($category) {
            return strtoupper($template['category']) === strtoupper($category);
        });
    }

    /**
     * Obtém templates aprovados
     */
    public function getApproved(string $appId): array
    {
        return $this->filterByStatus($appId, 'APPROVED');
    }

    /**
     * Obtém templates rejeitados
     */
    public function getRejected(string $appId): array
    {
        return $this->filterByStatus($appId, 'REJECTED');
    }

    /**
     * Obtém templates pendentes
     */
    public function getPending(string $appId): array
    {
        return $this->filterByStatus($appId, 'PENDING');
    }
}
