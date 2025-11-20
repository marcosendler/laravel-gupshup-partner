# 📱 Gupshup Partner API - Laravel Package

[![Latest Version](https://img.shields.io/github/v/release/marcosendler/GupshupPartnerLibrary)](https://github.com/marcosendler/GupshupPartnerLibrary/releases)
[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.x%7C11.x-red)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

Biblioteca completa e moderna para integração com as **APIs de Parceiro do Gupshup** em projetos Laravel. Gerencie apps, templates, mensagens, analytics, wallet e flows do WhatsApp Business de forma simples e eficiente.

## 🚀 Características

- ✅ **Completo**: Suporte a todas as APIs de Parceiro do Gupshup
- ✅ **Modular**: Arquitetura orientada a serviços
- ✅ **Cache Inteligente**: Cache automático de tokens
- ✅ **Type-Safe**: Tipagem forte em PHP 8.1+
- ✅ **Laravel-First**: Integração nativa com Laravel (Facades, Service Providers, Config)
- ✅ **Fácil de Usar**: API fluente e intuitiva
- ✅ **Tratamento de Erros**: Exceções customizadas e informativas
- ✅ **Testável**: Pronto para testes unitários e de integração

## 📦 Funcionalidades

### 🔧 Gerenciamento de Apps
- Listar apps vinculados ao parceiro
- Vincular novos apps
- Obter tokens de acesso
- Configurar ice breakers e mensagens de boas-vindas
- Verificar quality rating e messaging limits
- Gerenciar perfil e fotos

### 📝 Gerenciamento de Templates
- CRUD completo de templates
- Suporte a todos os tipos: texto, imagem, vídeo, documento, localização, catálogo
- Templates carrossel (imagem e vídeo)
- Templates de autenticação
- Filtros por status e categoria

### 💬 Envio de Mensagens
- Envio via templates
- Suporte a todos os tipos de mídia
- Mensagens LTO (Limited Time Offer)
- Mensagens de produto
- Mensagens CTA (Call To Action)
- Geração de Media IDs

### 📊 Analytics e Relatórios
- Logs de mensagens (inbound/outbound)
- Estatísticas de entrega, leitura e falhas
- Breakdown de uso diário
- Conversas por categoria
- Métricas resumidas e KPIs
- Análise de autenticação internacional

### 💰 Gerenciamento de Wallet
- Consulta de saldo
- Histórico de transações
- Extratos mensais
- Gerenciamento de overdraft
- Histórico de consumo
- Controle de comissões

### 🔄 Gerenciamento de Flows
- CRUD de flows
- Importação de JSON do Meta Playground
- Preview de flows
- Publicação e depreciação
- Gerenciamento de subscrições

---

## 📋 Requisitos

- PHP 8.1 ou superior
- Laravel 10.x ou 11.x
- Extensão cURL habilitada
- Conta de Parceiro Gupshup ativa

---

## 📦 Instalação

### Via Composer

```bash
composer require marcosendler/gupshup-partner-library
```

### Publicar Configuração

```bash
php artisan vendor:publish --provider="GupshupPartner\GupshupPartnerServiceProvider" --tag="gupshup-config"
```

### Configurar Credenciais

Adicione no arquivo `.env`:

```env
GUPSHUP_PARTNER_EMAIL=seu-email@gupshup.com
GUPSHUP_PARTNER_PASSWORD=sua-senha
GUPSHUP_DEFAULT_APP_ID=seu-app-id-padrao
GUPSHUP_CACHE_ENABLED=true
```

### Limpar Cache

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 🎯 Uso Rápido

```php
use GupshupPartner\Facades\GupshupPartner;

// Listar apps
$apps = GupshupPartner::apps()->list();

// Obter templates
$templates = GupshupPartner::templates()->list('app-id');

// Enviar mensagem
GupshupPartner::messages()->sendTextTemplate(
    'app-id',
    '5511999999999',
    'template-id',
    ['Parâmetro 1', 'Parâmetro 2']
);

// Obter analytics de hoje
$analytics = GupshupPartner::analytics()->getTodayAnalytics('app-id');
```

---

## 💡 Exemplos de Uso

### Exemplo em Controller

```php
namespace App\Http\Controllers;

use GupshupPartner\Facades\GupshupPartner;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    public function sendWelcome(Request $request)
    {
        $appId = config('gupshup.default_app.id');
        
        try {
            $response = GupshupPartner::messages()->sendTextTemplate(
                $appId,
                $request->phone,
                'welcome_template',
                [$request->name]
            );
            
            return response()->json([
                'success' => true,
                'message_id' => $response['messageId'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

### Gerenciamento de Apps

```php
use GupshupPartner\Facades\GupshupPartner;

// Listar todos os apps
$apps = GupshupPartner::apps()->list();

// Vincular um app
$linkedApp = GupshupPartner::apps()->link('API_KEY', 'Nome do App');

// Obter token de acesso
$token = GupshupPartner::apps()->getToken('app-id');

// Configurar ice breakers
GupshupPartner::apps()->setIceBreakers('app-id', [
    'Olá! Como posso ajudar?',
    'Ver catálogo',
    'Falar com atendente'
]);
```

### Gerenciamento de Templates

```php
// Listar templates
$templates = GupshupPartner::templates()->list($appId);

// Criar template de texto
$template = GupshupPartner::templates()->createText($appId, [
    'elementName' => 'boas_vindas',
    'category' => 'UTILITY',
    'languageCode' => 'pt_BR',
    'data' => 'Olá {{1}}, bem-vindo à {{2}}!'
]);

// Obter templates aprovados
$approved = GupshupPartner::templates()->getApproved($appId);
```

### Analytics

```php
use Carbon\Carbon;

$appId = 'seu-app-id';
$hoje = Carbon::now()->format('Y-m-d');
$semanaAtras = Carbon::now()->subWeek()->format('Y-m-d');

// Analytics de hoje
$today = GupshupPartner::analytics()->getTodayAnalytics($appId);

// Métricas resumidas
$metrics = GupshupPartner::analytics()->getSummaryMetrics(
    $appId,
    $semanaAtras,
    $hoje
);

// Resultado:
// [
//     'total_sent' => 1000,
//     'total_delivered' => 980,
//     'delivery_rate' => 98.0,
//     'read_rate' => 86.7,
//     'failure_rate' => 2.0
// ]
```

Para exemplos detalhados, consulte [EXEMPLOS_DE_USO.md](EXEMPLOS_DE_USO.md).

---

## 🏗️ Arquitetura

A biblioteca segue uma arquitetura modular e orientada a serviços:

```
GupshupPartnerClient (Cliente Principal)
    ├── AppManagement (Gerenciamento de Apps)
    ├── TemplateManagement (Templates)
    ├── MessageManagement (Mensagens)
    ├── AnalyticsManagement (Analytics)
    ├── WalletManagement (Carteira)
    └── FlowManagement (Flows)
```

Cada módulo é independente e pode ser usado separadamente ou através da Facade principal.

---

## 🔐 Autenticação e Cache

A biblioteca gerencia automaticamente:

- **Partner Token**: Obtido via login, cached por 23 horas
- **App Tokens**: Obtidos por app, cached por 23 horas
- **Refresh Automático**: Tokens são renovados automaticamente quando expiram

---

## 🛡️ Tratamento de Erros

```php
use GupshupPartner\Facades\GupshupPartner;
use GupshupPartner\Exceptions\GupshupPartnerException;

try {
    $result = GupshupPartner::apps()->list();
} catch (GupshupPartnerException $e) {
    // Erro específico da API
    Log::error('Gupshup Error', [
        'status' => $e->getStatusCode(),
        'message' => $e->getMessage(),
        'data' => $e->getResponseData()
    ]);
}
```

---

## 🧪 Testes

```php
// Teste de exemplo
public function test_pode_listar_apps()
{
    $client = new GupshupPartner\GupshupPartnerClient(
        'test@example.com',
        'password'
    );
    
    $apps = $client->apps()->list();
    
    $this->assertIsArray($apps);
    $this->assertArrayHasKey('partnerAppsList', $apps);
}
```

Para rodar os testes localmente:

```bash
composer install
composer test
```

Ou:

```bash
vendor/bin/phpunit
```

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Por favor:

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

---

## 📝 Changelog

### v1.0.0 (2024)
- ✨ Versão inicial
- ✅ Suporte completo às APIs de Parceiro
- ✅ Cache automático de tokens
- ✅ Integração nativa com Laravel
- ✅ Documentação completa

---

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

---

## 🆘 Suporte

- 🐛 **Issues**: [GitHub Issues](https://github.com/marcosendler/GupshupPartnerLibrary/issues)
- 📖 **Documentação Gupshup**: [https://partner-docs.gupshup.io/](https://partner-docs.gupshup.io/)
- 💬 **Discussões**: [GitHub Discussions](https://github.com/marcosendler/GupshupPartnerLibrary/discussions)

---

## 🙏 Agradecimentos

- [Gupshup](https://www.gupshup.io/) pela excelente plataforma de WhatsApp Business
- Comunidade Laravel pelo framework incrível
- Todos os contribuidores do projeto

---

**Desenvolvido com ❤️ para a comunidade Laravel**

**Nota**: Esta é uma biblioteca não-oficial. Para suporte oficial, consulte a [documentação do Gupshup](https://partner-docs.gupshup.io/).