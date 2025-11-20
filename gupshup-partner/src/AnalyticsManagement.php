<?php

namespace GupshupPartner;

use Carbon\Carbon;

class AnalyticsManagement
{
    protected GupshupPartnerClient $client;

    public function __construct(GupshupPartnerClient $client)
    {
        $this->client = $client;
    }

    /**
     * Obtém logs de mensagens de entrada (inbound) para um período específico
     */
    public function getInboundLogs(string $appId, string $startDate, string $endDate): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get('/partner/app/logs/inbound', [
            'appId' => $appId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém logs de mensagens de saída (outbound) para uma data específica
     */
    public function getOutboundLogs(string $appId, string $date): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get('/partner/app/logs/outbound', [
            'appId' => $appId,
            'date' => $date,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém breakdown de uso diário entre duas datas
     */
    public function getDailyUsage(string $appId, string $startDate, string $endDate): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get('/partner/app/analytics/daily', [
            'appId' => $appId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém relatório de conversas por categoria
     */
    public function getConversationsByCategory(string $appId, string $startDate, string $endDate): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get('/partner/app/analytics/conversations', [
            'appId' => $appId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém estatísticas de mensagens entregues
     */
    public function getDeliveryStats(string $appId, string $startDate, string $endDate): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get('/partner/app/analytics/delivery', [
            'appId' => $appId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém estatísticas de mensagens lidas
     */
    public function getReadStats(string $appId, string $startDate, string $endDate): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get('/partner/app/analytics/read', [
            'appId' => $appId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém estatísticas de mensagens falhadas
     */
    public function getFailedStats(string $appId, string $startDate, string $endDate): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get('/partner/app/analytics/failed', [
            'appId' => $appId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém análise de autenticação internacional
     */
    public function getInternationalAuthAnalytics(string $appId, string $startDate, string $endDate): array
    {
        $token = $this->client->apps()->getToken($appId);

        return $this->client->get('/partner/app/analytics/international-auth', [
            'appId' => $appId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ], [
            'apikey' => $token,
        ]);
    }

    /**
     * Obtém relatório completo de analytics
     */
    public function getFullReport(string $appId, string $startDate, string $endDate): array
    {
        return [
            'inbound' => $this->getInboundLogs($appId, $startDate, $endDate),
            'dailyUsage' => $this->getDailyUsage($appId, $startDate, $endDate),
            'conversations' => $this->getConversationsByCategory($appId, $startDate, $endDate),
            'delivery' => $this->getDeliveryStats($appId, $startDate, $endDate),
            'read' => $this->getReadStats($appId, $startDate, $endDate),
            'failed' => $this->getFailedStats($appId, $startDate, $endDate),
        ];
    }

    /**
     * Obtém analytics do último mês
     */
    public function getLastMonthAnalytics(string $appId): array
    {
        $endDate = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::now()->subMonth()->format('Y-m-d');

        return $this->getFullReport($appId, $startDate, $endDate);
    }

    /**
     * Obtém analytics de hoje
     */
    public function getTodayAnalytics(string $appId): array
    {
        $date = Carbon::now()->format('Y-m-d');

        return [
            'outbound' => $this->getOutboundLogs($appId, $date),
            'inbound' => $this->getInboundLogs($appId, $date, $date),
            'dailyUsage' => $this->getDailyUsage($appId, $date, $date),
        ];
    }

    /**
     * Obtém analytics da semana atual
     */
    public function getWeekAnalytics(string $appId): array
    {
        $endDate = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::now()->startOfWeek()->format('Y-m-d');

        return $this->getFullReport($appId, $startDate, $endDate);
    }

    /**
     * Exporta relatório para CSV (retorna dados formatados)
     */
    public function exportToCSV(string $appId, string $startDate, string $endDate): array
    {
        $data = $this->getFullReport($appId, $startDate, $endDate);

        // Aqui você pode adicionar lógica para formatar os dados em CSV
        // ou usar uma biblioteca como League\Csv

        return $data;
    }

    /**
     * Obtém métricas resumidas
     */
    public function getSummaryMetrics(string $appId, string $startDate, string $endDate): array
    {
        $delivery = $this->getDeliveryStats($appId, $startDate, $endDate);
        $read = $this->getReadStats($appId, $startDate, $endDate);
        $failed = $this->getFailedStats($appId, $startDate, $endDate);

        $totalSent = ($delivery['total'] ?? 0) + ($failed['total'] ?? 0);
        $totalDelivered = $delivery['total'] ?? 0;
        $totalRead = $read['total'] ?? 0;
        $totalFailed = $failed['total'] ?? 0;

        return [
            'total_sent' => $totalSent,
            'total_delivered' => $totalDelivered,
            'total_read' => $totalRead,
            'total_failed' => $totalFailed,
            'delivery_rate' => $totalSent > 0 ? ($totalDelivered / $totalSent) * 100 : 0,
            'read_rate' => $totalDelivered > 0 ? ($totalRead / $totalDelivered) * 100 : 0,
            'failure_rate' => $totalSent > 0 ? ($totalFailed / $totalSent) * 100 : 0,
        ];
    }
}
