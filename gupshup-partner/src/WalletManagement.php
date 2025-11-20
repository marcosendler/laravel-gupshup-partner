<?php

namespace GupshupPartner;

class WalletManagement
{
    protected GupshupPartnerClient $client;

    public function __construct(GupshupPartnerClient $client)
    {
        $this->client = $client;
    }

    /**
     * Obtém saldo da carteira
     */
    public function getBalance(string $walletId): array
    {
        return $this->client->get("/partner/wallet/{$walletId}/balance");
    }

    /**
     * Obtém histórico da carteira
     */
    public function getHistory(string $walletId, string $startDate, string $endDate): array
    {
        return $this->client->get("/partner/wallet/{$walletId}/history", [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Obtém extrato da carteira
     */
    public function getStatement(string $walletId, string $month): array
    {
        return $this->client->get("/partner/wallet/{$walletId}/statement", [
            'month' => $month,
        ]);
    }

    /**
     * Obtém informações de overdraft
     */
    public function getOverdraft(string $walletId): array
    {
        return $this->client->get("/partner/wallet/{$walletId}/overdraft");
    }

    /**
     * Define limite de overdraft
     */
    public function setOverdraftLimit(string $walletId, float $limit): array
    {
        return $this->client->post("/partner/wallet/{$walletId}/overdraft", [
            'limit' => $limit,
        ]);
    }

    /**
     * Obtém histórico de consumo (últimos 90 dias)
     */
    public function getConsumptionHistory(string $walletId): array
    {
        return $this->client->get("/partner/wallet/{$walletId}/consumption");
    }

    /**
     * Obtém detalhes de comissão
     */
    public function getCommission(string $walletId): array
    {
        return $this->client->get("/partner/wallet/{$walletId}/commission");
    }

    /**
     * Obtém créditos expirados
     */
    public function getExpiredCredits(string $walletId): array
    {
        return $this->client->get("/partner/wallet/{$walletId}/expired");
    }
}
