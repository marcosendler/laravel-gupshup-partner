<?php

namespace GupshupPartner;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use GupshupPartner\Exceptions\GupshupPartnerException;

class GupshupPartnerClient
{
    protected string $baseUrl = 'https://partner.gupshup.io';
    protected ?string $partnerToken = null;
    protected string $email;
    protected string $password;
    protected int $tokenExpiryHours = 24;

    public function __construct(?string $email = null, ?string $password = null)
    {
        $this->email = $email ?? config('gupshup.partner.email');
        $this->password = $password ?? config('gupshup.partner.password');
    }

    /**
     * Obtém o Partner Token (com cache automático)
     */
    public function getPartnerToken(bool $forceRefresh = false): string
    {
        if ($this->partnerToken && !$forceRefresh) {
            return $this->partnerToken;
        }

        $cacheKey = 'gupshup_partner_token_' . md5($this->email);

        if (!$forceRefresh && Cache::has($cacheKey)) {
            $this->partnerToken = Cache::get($cacheKey);
            return $this->partnerToken;
        }

        $response = Http::asForm()
            ->accept('application/json')
            ->post("{$this->baseUrl}/partner/account/login", [
                'email' => $this->email,
                'password' => $this->password,
            ]);

        if ($response->failed()) {
            throw new GupshupPartnerException('Falha ao obter Partner Token: ' . $response->body());
        }

        $data = $response->json();
        $this->partnerToken = $data['token'];

        // Cache por 23 horas (token expira em 24h)
        Cache::put($cacheKey, $this->partnerToken, now()->addHours($this->tokenExpiryHours - 1));

        return $this->partnerToken;
    }

    /**
     * Faz uma requisição autenticada com o Partner Token
     */
    protected function makeRequest(string $method, string $endpoint, array $data = [], array $headers = [])
    {
        $token = $this->getPartnerToken();

        $request = Http::withHeaders(array_merge([
            'Authorization' => $token,
        ], $headers));

        $url = $this->baseUrl . $endpoint;

        $response = match (strtoupper($method)) {
            'GET' => $request->get($url, $data),
            'POST' => $request->asForm()->post($url, $data),
            'PUT' => $request->asForm()->put($url, $data),
            'DELETE' => $request->delete($url, $data),
            'PATCH' => $request->asForm()->patch($url, $data),
            default => throw new \InvalidArgumentException("Método HTTP inválido: {$method}"),
        };

        if ($response->failed()) {
            throw new GupshupPartnerException(
                "Erro na requisição {$method} {$endpoint}: " . $response->body(),
                $response->status()
            );
        }

        return $response->json();
    }

    /**
     * Serviço de Gerenciamento de Apps
     */
    public function apps(): AppManagement
    {
        return new AppManagement($this);
    }

    /**
     * Serviço de Templates
     */
    public function templates(): TemplateManagement
    {
        return new TemplateManagement($this);
    }

    /**
     * Serviço de Mensagens
     */
    public function messages(): MessageManagement
    {
        return new MessageManagement($this);
    }

    /**
     * Serviço de Análises e Relatórios
     */
    public function analytics(): AnalyticsManagement
    {
        return new AnalyticsManagement($this);
    }

    /**
     * Serviço de Wallet (Carteira)
     */
    public function wallet(): WalletManagement
    {
        return new WalletManagement($this);
    }

    /**
     * Serviço de Flows
     */
    public function flows(): FlowManagement
    {
        return new FlowManagement($this);
    }

    /**
     * Métodos auxiliares para acesso direto
     */
    public function get(string $endpoint, array $params = [])
    {
        return $this->makeRequest('GET', $endpoint, $params);
    }

    public function post(string $endpoint, array $data = [])
    {
        return $this->makeRequest('POST', $endpoint, $data);
    }

    public function put(string $endpoint, array $data = [])
    {
        return $this->makeRequest('PUT', $endpoint, $data);
    }

    public function delete(string $endpoint, array $data = [])
    {
        return $this->makeRequest('DELETE', $endpoint, $data);
    }
}
