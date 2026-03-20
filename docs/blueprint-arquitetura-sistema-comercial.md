# Blueprint Arquitetural — Sistema Web Administrativo/Comercial

## 1. Visão macro da solução

## 1.1 Objetivo
Construir um sistema web administrativo/comercial para substituir a operação comercial/orçamentária atual, centralizando cadastro de clientes, produtos/equipamentos, propostas comerciais, importação/exportação Excel, controle de usuários/permissões, dashboard administrativo e auditoria.

## 1.2 Princípios arquiteturais
- **Stack obrigatória**: Laravel + PHP 8.x no backend, Vue 3 + Vuetify no frontend e MySQL como banco.
- **Monólito modular**: um único sistema, porém dividido em módulos de domínio bem isolados para facilitar evolução por equipe.
- **API-first**: toda interface web consome a API `/api/v1`; futuro app mobile Android/iOS consumirá a mesma camada.
- **Regra de negócio no backend**: cálculos, validações críticas, versionamento, auditoria, status e importações vivem no Laravel.
- **Frontend desacoplado**: Vue/Vuetify atua como cliente administrativo, com foco em UX corporativa e produtividade.
- **Excel como ponte de transição**: importação inicial e exportação compatível, sem depender do arquivo como motor de negócio.
- **Escalável sem microservices**: arquitetura preparada para crescimento horizontal por módulos, filas, storage externo e APIs móveis, sem quebrar o monólito.

## 1.3 Contexto macro de componentes

### Backend Laravel
Responsável por:
- autenticação com Sanctum;
- autorização com roles, permissions e policies;
- CRUDs de usuários, clientes, produtos e propostas;
- cálculo e consolidação de orçamentos;
- leitura, mapeamento e importação de Excel;
- exportação de proposta em planilha padronizada;
- auditoria, histórico e versionamento;
- dashboard summary;
- armazenamento e vinculação de arquivos.

### Frontend Vue 3 + Vuetify
Responsável por:
- login e gestão de sessão do operador;
- dashboard em estilo portal corporativo;
- CRUDs administrativos padronizados;
- formulários, tabelas e filtros;
- upload de planilhas/arquivos;
- visualização de logs e status;
- experiência consistente entre módulos.

### Banco MySQL
Responsável por:
- persistência relacional do domínio;
- histórico de status e versões;
- estruturas de autenticação/autorização;
- rastreabilidade e auditoria;
- apoio à busca, filtros e relatórios.

### Filas/Jobs
Inicialmente opcionais, mas já previstos para:
- importações pesadas de Excel;
- exportações demoradas;
- geração futura de notificações;
- processamento de anexos.

## 1.4 Visão de módulos de negócio
- **Auth**: login/logout/me e gestão de sessão.
- **Users**: gestão de usuários e perfil.
- **Roles & Permissions**: controle de acesso por módulo e ação.
- **Clients**: cadastro mestre de clientes.
- **Products**: catálogo de produtos/equipamentos/referências.
- **Proposals**: proposta comercial e seus metadados.
- **Proposal Items**: itens e composição comercial.
- **Proposal Templates**: templates para exportação Excel.
- **Proposal Versions**: snapshots e versionamento.
- **Dashboard**: indicadores, atalhos e atividade recente.
- **Files**: anexos e uploads.
- **Audit Logs**: trilha de auditoria.
- **Excel Import/Export**: integração de transição com o modelo operacional atual.

---

## 2. Arquitetura geral da solução

## 2.1 Estilo arquitetural
A solução deve seguir um **monólito modular orientado a domínio**. Não é um DDD formal completo, mas usa separação forte por responsabilidade para permitir evolução.

### Estrutura macro sugerida
- **Camada HTTP/API**: controllers, requests, resources, middleware.
- **Camada de aplicação**: actions/services para casos de uso.
- **Camada de domínio**: models, enums, policies, regras, cálculos.
- **Camada de infraestrutura**: jobs, storage, leitura de Excel, adapters, logging.
- **Camada de apresentação web**: SPA Vue 3 + Vuetify.

## 2.2 Decisão: monólito modular em vez de microservices
**Justificativa**:
- menor complexidade operacional para início solo;
- acelera entrega inicial e validação do processo comercial;
- evita overhead de autenticação distribuída, mensageria e observabilidade cedo demais;
- mantém transações de propostas/importações mais simples;
- preserva caminho de crescimento: no futuro, módulos como importação, BI ou documentos podem ser extraídos se fizer sentido.

## 2.3 API-first como contrato central
Todo consumo funcional deve passar pela API `/api/v1`. Isso garante:
- separação entre interface e regra de negócio;
- reaproveitamento para mobile;
- padronização de autenticação/autorização;
- testes de integração mais previsíveis;
- possibilidade futura de expor integrações B2B.

---

## 3. Módulos iniciais obrigatórios

## 3.1 Auth
### Responsabilidades
- autenticar usuário;
- encerrar sessão;
- retornar usuário autenticado;
- renovar ou revogar tokens futuros;
- preparar recuperação de senha futura.

### Limites
- não contém cadastro amplo de usuários;
- não define permissões, apenas aplica o contexto autenticado.

### Fluxo
1. usuário acessa login;
2. envia credenciais;
3. backend valida, autentica e entrega sessão/token;
4. frontend guarda contexto autenticado;
5. usuário é redirecionado ao dashboard.

## 3.2 Users
### Responsabilidades
- cadastro e edição de usuários;
- ativação/inativação;
- atualização de perfil;
- redefinição/troca de senha;
- associação a papéis.

### Limites
- permissões detalhadas ficam no módulo Roles & Permissions.

## 3.3 Roles & Permissions
### Papéis iniciais
- **admin**: acesso total.
- **gestor**: visão gerencial, aprovações e acompanhamento.
- **comercial**: gestão de clientes, produtos consultivos e propostas.
- **operacional**: suporte operacional, leitura e apoio.

### Ações por módulo
- visualizar
- criar
- editar
- excluir
- exportar
- aprovar

### Estratégia
- `roles` agrupam perfis de negócio;
- `permissions` descrevem capacidades finas por recurso/ação;
- policies do Laravel decidem acesso final;
- seeds carregam matriz inicial de permissões.

## 3.4 Clients
### Responsabilidades
- manter cadastro mestre de clientes;
- guardar razão social, fantasia, CNPJ, contatos e observações;
- suportar múltiplos contatos e endereços;
- receber dados importados do Excel e consolidar duplicidades.

### Limites
- proposta apenas referencia clientes;
- cálculos comerciais não ficam aqui.

## 3.5 Products
### Responsabilidades
- cadastro de produtos, equipamentos e referências;
- dados comerciais e técnicos básicos;
- base para composição de proposta;
- importação auxiliar de listas comerciais.

### Limites
- preço aplicado na proposta pode divergir do preço base;
- lógica de subtotal/total é do módulo de proposta.

## 3.6 Proposals
### Responsabilidades
- criar e editar propostas comerciais;
- vincular cliente, usuário responsável e template;
- armazenar datas, validade, observações, totais e status;
- consolidar itens e snapshots.

### Limites
- item detalhado fica em `proposal_items`;
- histórico de status e versões fica em módulos próprios.

## 3.7 Proposal Items
### Responsabilidades
- registrar composição da proposta;
- produto vinculado ou descrição livre;
- quantidade, preço unitário, desconto e totais;
- guardar dados congelados para preservar histórico.

### Decisão importante
Ao salvar proposta, o item deve armazenar também nome/descrição/código copiados do produto para evitar perda histórica caso o cadastro do produto mude depois.

## 3.8 Proposal Templates
### Responsabilidades
- registrar templates de proposta;
- mapear versão do layout Excel;
- definir arquivo-base usado em exportação;
- sustentar compatibilidade com o processo comercial atual.

## 3.9 Proposal Versions
### Responsabilidades
- criar snapshots completos da proposta;
- registrar usuário e momento da versão;
- suportar auditoria, comparação e rastreabilidade.

## 3.10 Dashboard
### Responsabilidades
- atalhos rápidos;
- KPIs resumidos;
- atividade recente;
- visualização macro operacional.

## 3.11 Files
### Responsabilidades
- upload de anexos e planilhas;
- vínculo polimórfico por entidade;
- armazenamento local inicial;
- base para futura migração para S3/objeto externo.

## 3.12 Audit Logs
### Responsabilidades
- registrar criação, edição, exclusão, exportação e mudança de status;
- guardar usuário, entidade, ação, antes/depois resumidos e contexto técnico.

---

## 4. Arquitetura backend Laravel

## 4.1 Estrutura sugerida

```text
app/
  Actions/
    Auth/
    Client/
    Product/
    Proposal/
    File/
    Import/
    Dashboard/
  Enums/
  Exceptions/
  Http/
    Controllers/
      Api/
        V1/
    Requests/
      Auth/
      User/
      Client/
      Product/
      Proposal/
      Import/
    Resources/
  Jobs/
  Models/
  Policies/
  Providers/
  Services/
  Support/
```

## 4.2 Responsabilidade de cada pasta

### `app/Http/Controllers/Api/V1`
**Responsabilidade**:
- receber requisições HTTP;
- delegar execução para actions/services;
- retornar resources/respostas padronizadas;
- não conter regra de negócio pesada.

**Quando usar**:
- sempre que for endpoint público da API.

**Exemplos**:
- `AuthController`
- `UserController`
- `ClientController`
- `ProductController`
- `ProposalController`
- `ExcelImportController`
- `DashboardController`

### `app/Http/Requests`
**Responsabilidade**:
- validar payload de entrada;
- autorizar requisição no nível preliminar quando fizer sentido;
- normalizar campos simples.

**Quando usar**:
- toda entrada relevante de criação, atualização, filtros complexos e importação.

**Exemplos**:
- `LoginRequest`
- `StoreClientRequest`
- `UpdateProductRequest`
- `StoreProposalRequest`
- `ImportClientsExcelRequest`

### `app/Actions`
**Responsabilidade**:
- representar casos de uso específicos;
- concentrar fluxos com começo, meio e fim;
- orquestrar models, services, transactions e logs.

**Quando usar**:
- quando houver um comando claro do negócio, como “criar proposta”, “importar clientes”, “exportar planilha”, “alterar status”, “gerar versão”.

**Exemplos**:
- `Auth/LoginAction`
- `Client/UpsertClientAction`
- `Product/ImportProductsAction`
- `Proposal/CreateProposalAction`
- `Proposal/ChangeProposalStatusAction`
- `Proposal/CreateProposalVersionAction`
- `Import/ImportClientsAction`
- `Import/ImportProposalTemplateAction`
- `Proposal/ProposalSpreadsheetExportAction`

### `app/Services`
**Responsabilidade**:
- encapsular lógica reutilizável e transversal;
- suportar actions mais complexas;
- operar com parsing, mapeamentos, cálculos, filtros, normalização.

**Quando usar**:
- quando a lógica não é um caso de uso final e sim um serviço reutilizável.

**Exemplos**:
- `ExcelMappingService`
- `ProposalCalculationService`
- `AuditLogService`
- `PermissionMatrixService`
- `GlobalSearchService`

### `app/Models`
**Responsabilidade**:
- entidades persistentes Eloquent;
- relacionamentos, casts, scopes simples, atributos derivados leves.

**Quando usar**:
- toda tabela principal do domínio.

**Boas práticas**:
- evitar encher model com regras de caso de uso;
- manter scopes e helpers pequenos;
- cálculos complexos ficam em services/actions.

### `app/Policies`
**Responsabilidade**:
- autorização orientada a recurso;
- verificar se usuário pode visualizar, criar, editar, excluir, exportar, aprovar.

**Quando usar**:
- em todos módulos sensíveis.

### `app/Http/Resources`
**Responsabilidade**:
- serializar respostas da API;
- padronizar estrutura exposta;
- ocultar detalhes internos do Eloquent.

**Quando usar**:
- listagem, detalhes, dashboard, proposal export metadata, audit logs.

### `app/Jobs`
**Responsabilidade**:
- processamentos assíncronos;
- importações grandes de Excel;
- exportações demoradas;
- envio futuro de notificações.

**Quando usar**:
- tarefas demoradas, repetíveis ou reprocessáveis.

### `app/Enums`
**Responsabilidade**:
- centralizar valores finitos do domínio.

**Exemplos**:
- `UserStatusEnum`
- `ProductStatusEnum`
- `ProposalStatusEnum`
- `ImportTypeEnum`
- `AuditActionEnum`

### `app/Exceptions`
**Responsabilidade**:
- exceções de domínio e aplicação;
- mensagens claras e tratáveis pelo handler global.

**Exemplos**:
- `InvalidProposalStatusTransitionException`
- `ExcelMappingException`
- `ImportValidationException`
- `ProposalCalculationException`

### `app/Support`
**Responsabilidade**:
- helpers e componentes de infraestrutura compartilhados;
- respostas padronizadas, traits, DTOs simples, parsers utilitários, normalizadores.

**Exemplos**:
- `ApiResponse`
- `CnpjNormalizer`
- `SpreadsheetCellResolver`
- `MoneyFormatter`

### `app/Providers`
**Responsabilidade**:
- registro de bindings, policies, observers e bootstrapping da aplicação.

---

## 5. Convenções backend por domínio

## 5.1 Controllers finos, Actions fortes
Regra principal: controllers apenas coordenam entrada/saída. O caso de uso real vive em `Actions`.

## 5.2 Services para reutilização transversal
Se a mesma lógica é usada por vários fluxos, sai da action e vira service.

## 5.3 Resources sempre na saída
Nunca retornar model crua diretamente em endpoints de negócio.

## 5.4 Policies em todos os módulos administrativos
Além de autenticar, o sistema precisa autorizar por recurso e ação.

## 5.5 Transações de banco
Fluxos como criação de proposta, alteração de status, importações por lote e geração de versão devem rodar em transação.

## 5.6 Auditoria como preocupação transversal
Toda mutation importante deve disparar `AuditLogService`.

---

## 6. Arquitetura frontend Vue 3 + Vuetify

## 6.1 Estrutura sugerida

```text
src/
  api/
  components/
    common/
    forms/
    tables/
    layout/
    dashboard/
  layouts/
  pages/
    auth/
    dashboard/
    users/
    clients/
    products/
    proposals/
    settings/
    imports/
    audits/
  plugins/
  router/
  stores/
  utils/
```

## 6.2 Responsabilidade das pastas

### `src/api/`
Clientes HTTP por recurso.
- `http.ts` ou `axios.ts`
- `authApi.ts`
- `usersApi.ts`
- `clientsApi.ts`
- `productsApi.ts`
- `proposalsApi.ts`
- `dashboardApi.ts`
- `importsApi.ts`

### `src/components/common/`
Componentes genéricos reutilizáveis.
- cards base;
- dialogs;
- loaders;
- chips de status;
- empty states;
- confirm dialogs.

### `src/components/forms/`
Padrões de formulário.
- campos compostos;
- seletor de cliente;
- seletor de produto;
- blocos de endereço/contato;
- validadores visuais.

### `src/components/tables/`
Tabelas administrativas padronizadas.
- data table server-side;
- toolbar com filtros;
- colunas com ações;
- paginação;
- export buttons.

### `src/components/layout/`
Blocos estruturais.
- `AppSidebar`
- `AppTopbar`
- `AppBreadcrumbs`
- `GlobalSearchBar`
- `NotificationPanel`
- `ShortcutGrid`

### `src/components/dashboard/`
Widgets do dashboard.
- cards KPI;
- atividade recente;
- atalhos por categoria;
- lembretes/comunicados;
- gráficos futuros.

### `src/layouts/`
- `AuthLayout.vue`
- `AppLayout.vue`

### `src/pages/`
Páginas por domínio.

### `src/router/`
Definição de rotas, meta de autenticação e permissões.

### `src/stores/`
Pinia para sessão, permissões, filtros e estado do dashboard.

### `src/utils/`
Formatadores, máscaras, helpers de permissão, debounce, download e datas.

## 6.3 Layouts principais

### `AuthLayout`
Uso exclusivo da área não autenticada.
Características:
- tela full viewport;
- fundo em degradê verde/água suave;
- card central branco/cinza claro;
- tipografia limpa;
- foco no formulário de login.

### `AppLayout`
Uso da área autenticada.
Características:
- topbar fixa vermelha institucional;
- logo no canto superior esquerdo;
- sidebar fixa recolhível com ícones e labels;
- miolo com container fluido, breadcrumbs e conteúdo;
- possibilidade de painel lateral de lembretes;
- comportamento responsivo com drawer em mobile/tablet.

## 6.4 Padrão visual geral
- Vuetify como base do design system;
- espaçamento consistente em grid de 8px;
- uso de `v-card` para blocos funcionais;
- tipografia clara e densa, adequada a ERP;
- tabelas com filtros persistidos;
- formulários segmentados por seções;
- feedback visual de loading, erro e sucesso;
- status com chips coloridos;
- ícones consistentes por módulo.

---

## 7. Direção visual baseada nas referências

## 7.1 Tela de Login
### Objetivo visual
Transmitir simplicidade, credibilidade e ambiente corporativo.

### Composição
- fundo com degradê suave entre verde água, aqua-claro e tons neutros;
- card central com largura moderada;
- cantos arredondados e sombra discreta;
- título “Login” centralizado;
- campo de e-mail/login;
- campo de senha;
- checkbox “Mostrar senha” abaixo do campo;
- botão principal centralizado;
- links inferiores para recuperação e cadastro futuro.

### Observações funcionais
- permitir login com credencial inicial `admin/admin` em ambiente dev via seed;
- no backend, idealmente armazenar `email` e opcionalmente aceitar username `admin` por conveniência;
- frontend pode rotular campo como “E-mail ou usuário” para acomodar seed inicial.

## 7.2 Dashboard / Sistema interno
### Objetivo visual
Parecer um portal corporativo/ERP com alta densidade útil e boa navegabilidade.

### Estrutura visual
- **topbar fixa vermelha institucional** com logo, nome do sistema e menu do usuário;
- **sidebar vertical** com ícones e textos, recolhível;
- **barra de busca global** logo no topo do conteúdo central;
- **blocos centrais por categoria**: Comercial, Cadastros, Gestão, Importação, Auditoria;
- **listas de atalhos** dentro de cards;
- **cards laterais** para lembretes, comunicados e atividades recentes;
- **área de KPI/BI** com totais e indicadores resumidos.

### Sensação desejada
- corporativo;
- organizado;
- produtivo;
- modular;
- navegável mesmo com muitos atalhos.

## 7.3 Padrão visual transversal
- sidebar fixa recolhível;
- topbar fixa institucional;
- páginas com cabeçalho, breadcrumbs e ações primárias;
- cards e subcards para separar contexto;
- grids densos mas legíveis;
- formulários com boa hierarquia;
- tabelas padronizadas com toolbar, filtros e paginação.

---

## 8. Modelagem inicial do banco de dados

## 8.1 Tabela `users`
Campos principais:
- `id`
- `name`
- `username` (opcional, útil para `admin`)
- `email`
- `password`
- `status` (ativo/inativo)
- `last_login_at`
- `created_at`
- `updated_at`
- `deleted_at` (opcional, se usar soft delete)

Relacionamentos:
- muitos para muitos com `roles`;
- um para muitos com `proposals` como responsável;
- um para muitos com `proposal_versions`;
- um para muitos com `audit_logs`;
- um para muitos com `proposal_status_histories`.

## 8.2 Tabela `roles`
Campos principais:
- `id`
- `name` (`admin`, `gestor`, `comercial`, `operacional`)
- `slug`
- `description`
- `created_at`
- `updated_at`

Relacionamentos:
- muitos para muitos com `users`;
- muitos para muitos com `permissions`.

## 8.3 Tabela `permissions`
Campos principais:
- `id`
- `module` (`users`, `clients`, `products`, `proposals`, `dashboard`, `audit_logs`, etc.)
- `action` (`view`, `create`, `update`, `delete`, `export`, `approve`)
- `name`
- `slug`
- `created_at`
- `updated_at`

Relacionamentos:
- muitos para muitos com `roles`.

## 8.4 Tabela `role_user`
Pivot:
- `user_id`
- `role_id`

## 8.5 Tabela `permission_role`
Pivot:
- `role_id`
- `permission_id`

## 8.6 Tabela `clients`
Campos principais:
- `id`
- `external_code` (opcional, vindo do Excel)
- `cnpj`
- `company_name` (razão social)
- `trade_name` (nome fantasia)
- `email`
- `phone`
- `primary_contact_name`
- `billing_range`
- `state`
- `city`
- `zip_code`
- `microregion`
- `notes`
- `source` (`manual`, `excel_import`, `sync`)
- `is_active`
- `created_by`
- `updated_by`
- `created_at`
- `updated_at`

Relacionamentos:
- um para muitos com `client_addresses`;
- um para muitos com `client_contacts`;
- um para muitos com `proposals`;
- um para muitos com `files` via polimorfismo;
- um para muitos com `audit_logs` via referência de entidade.

## 8.7 Tabela `client_addresses`
Campos principais:
- `id`
- `client_id`
- `type` (`principal`, `cobranca`, `entrega`)
- `street`
- `number`
- `complement`
- `district`
- `city`
- `state`
- `zip_code`
- `is_primary`
- `created_at`
- `updated_at`

Relacionamentos:
- pertence a `clients`.

## 8.8 Tabela `client_contacts`
Campos principais:
- `id`
- `client_id`
- `name`
- `role`
- `email`
- `phone`
- `mobile`
- `decision_maker` (bool)
- `notes`
- `created_at`
- `updated_at`

Relacionamentos:
- pertence a `clients`.

## 8.9 Tabela `products`
Campos principais:
- `id`
- `external_code`
- `code`
- `name`
- `description`
- `category`
- `unit`
- `base_price`
- `status`
- `technical_notes`
- `source`
- `created_by`
- `updated_by`
- `created_at`
- `updated_at`

Relacionamentos:
- um para muitos com `proposal_items`;
- um para muitos com `files` via polimorfismo.

## 8.10 Tabela `proposal_templates`
Campos principais:
- `id`
- `name`
- `code`
- `version`
- `description`
- `excel_file_path`
- `mapping_config` (JSON)
- `is_default`
- `is_active`
- `created_at`
- `updated_at`

Relacionamentos:
- um para muitos com `proposals`.

## 8.11 Tabela `proposals`
Campos principais:
- `id`
- `number` (identificador amigável)
- `client_id`
- `responsible_user_id`
- `proposal_template_id`
- `status`
- `issue_date`
- `valid_until`
- `title`
- `general_notes`
- `subtotal_amount`
- `discount_amount`
- `total_amount`
- `currency` (opcional)
- `source` (`manual`, `excel_prefill`, `imported`)
- `created_by`
- `updated_by`
- `approved_by` (nullable)
- `approved_at` (nullable)
- `created_at`
- `updated_at`

Relacionamentos:
- pertence a `client`;
- pertence a `user` responsável;
- pertence a `proposal_template`;
- um para muitos com `proposal_items`;
- um para muitos com `proposal_versions`;
- um para muitos com `proposal_status_histories`;
- um para muitos com `files` via polimorfismo;
- um para muitos com `audit_logs` por referência de entidade.

## 8.12 Tabela `proposal_items`
Campos principais:
- `id`
- `proposal_id`
- `product_id` (nullable para item livre)
- `line_number`
- `product_code_snapshot`
- `product_name_snapshot`
- `description`
- `category_snapshot`
- `unit`
- `quantity`
- `unit_price`
- `discount_amount`
- `subtotal_amount`
- `total_amount`
- `technical_notes_snapshot`
- `metadata` (JSON, útil para origem Excel/modalidade)
- `created_at`
- `updated_at`

Relacionamentos:
- pertence a `proposal`;
- opcionalmente pertence a `product`.

## 8.13 Tabela `proposal_versions`
Campos principais:
- `id`
- `proposal_id`
- `version_number`
- `snapshot` (JSON completo da proposta e itens)
- `created_by`
- `created_at`

Relacionamentos:
- pertence a `proposal`;
- pertence a `user`.

## 8.14 Tabela `proposal_status_histories`
Campos principais:
- `id`
- `proposal_id`
- `from_status`
- `to_status`
- `changed_by`
- `reason`
- `created_at`

Relacionamentos:
- pertence a `proposal`;
- pertence a `user`.

## 8.15 Tabela `files`
Campos principais:
- `id`
- `attachable_type`
- `attachable_id`
- `disk`
- `path`
- `original_name`
- `mime_type`
- `extension`
- `size`
- `category`
- `uploaded_by`
- `created_at`

Relacionamentos:
- polimórfico com `clients`, `products`, `proposals`, `proposal_templates` ou futuras entidades;
- pertence a `user` via `uploaded_by`.

## 8.16 Tabela `audit_logs`
Campos principais:
- `id`
- `user_id`
- `action`
- `entity_type`
- `entity_id`
- `message`
- `before_data` (JSON)
- `after_data` (JSON)
- `context` (JSON)
- `ip_address`
- `user_agent`
- `created_at`

Relacionamentos:
- pertence a `user`.

## 8.17 Relacionamentos principais resumidos
- `users` N:N `roles`
- `roles` N:N `permissions`
- `clients` 1:N `client_addresses`
- `clients` 1:N `client_contacts`
- `clients` 1:N `proposals`
- `products` 1:N `proposal_items`
- `proposal_templates` 1:N `proposals`
- `proposals` 1:N `proposal_items`
- `proposals` 1:N `proposal_versions`
- `proposals` 1:N `proposal_status_histories`
- `files` polimórfico para várias entidades
- `users` 1:N `audit_logs`

---

## 9. Estratégia de autenticação e segurança

## 9.1 Sanctum
Usar **Laravel Sanctum** como base porque atende bem:
- SPA web autenticada;
- emissão de tokens para app mobile futuro;
- baixo overhead frente a OAuth completo neste estágio.

## 9.2 Estratégia prática
### Web SPA
- autenticação via Sanctum para sessão/cookies ou token SPA controlado;
- rotas protegidas por middleware `auth:sanctum`.

### Mobile futuro
- emissão de personal access tokens;
- escopos e expiração podem ser introduzidos depois.

## 9.3 Seed inicial obrigatório
Criar seed com:
- role `admin`;
- permissões totais;
- usuário inicial:
  - login/username: `admin`
  - e-mail: `admin@local.test`
  - senha: `admin`

> Em ambiente real, forçar troca de senha no primeiro acesso é altamente recomendável.

## 9.4 Autorização
- uso de `Gate`/`Policy` por módulo;
- verificação de permissão por ação;
- frontend esconde ações sem permissão, mas backend sempre valida.

## 9.5 Auditoria e rastreabilidade
- logs de autenticação;
- logs de CRUD crítico;
- logs de exportação e mudança de status;
- contextualização com IP e user-agent quando aplicável.

---

## 10. Padrão de API

## 10.1 Convenções gerais
- prefixo `/api/v1`
- JSON como formato padrão
- paginação em listagens
- filtros por query string
- ordenação por campos controlados
- respostas padronizadas

## 10.2 Padrão de sucesso
```json
{
  "success": true,
  "message": "Operação realizada com sucesso",
  "data": {},
  "meta": {}
}
```

## 10.3 Padrão de erro
```json
{
  "success": false,
  "message": "Erro de validação",
  "errors": {}
}
```

## 10.4 Paginação
Sugestão:
- `page`
- `per_page`
- `sort`
- `direction`
- filtros específicos por recurso

`meta` pode conter:
- `current_page`
- `per_page`
- `total`
- `last_page`
- `filters`

## 10.5 Filtros por módulo
### Clients
- `search`
- `cnpj`
- `state`
- `city`
- `is_active`

### Products
- `search`
- `category`
- `status`

### Proposals
- `search`
- `status`
- `client_id`
- `responsible_user_id`
- `date_from`
- `date_to`

### Audit Logs
- `entity_type`
- `action`
- `user_id`
- `date_from`
- `date_to`

## 10.6 Rotas REST versionadas

### Auth
- `POST /api/v1/auth/login`
- `POST /api/v1/auth/logout`
- `GET /api/v1/auth/me`

### Users / Roles
- `GET /api/v1/users`
- `POST /api/v1/users`
- `GET /api/v1/users/{id}`
- `PUT /api/v1/users/{id}`
- `PATCH /api/v1/users/{id}/status`
- `PATCH /api/v1/users/{id}/password`
- `GET /api/v1/roles`
- `GET /api/v1/permissions`

### Clients
- `GET /api/v1/clients`
- `POST /api/v1/clients`
- `GET /api/v1/clients/{id}`
- `PUT /api/v1/clients/{id}`
- `DELETE /api/v1/clients/{id}`

### Products
- `GET /api/v1/products`
- `POST /api/v1/products`
- `GET /api/v1/products/{id}`
- `PUT /api/v1/products/{id}`
- `DELETE /api/v1/products/{id}`

### Proposals
- `GET /api/v1/proposals`
- `POST /api/v1/proposals`
- `GET /api/v1/proposals/{id}`
- `PUT /api/v1/proposals/{id}`
- `PATCH /api/v1/proposals/{id}/status`
- `POST /api/v1/proposals/{id}/versions`
- `GET /api/v1/proposals/{id}/versions`

### Proposal Templates
- `GET /api/v1/proposal-templates`
- `POST /api/v1/proposal-templates`
- `GET /api/v1/proposal-templates/{id}`
- `PUT /api/v1/proposal-templates/{id}`

### Import / Export
- `POST /api/v1/import/excel/clients`
- `POST /api/v1/import/excel/products`
- `POST /api/v1/import/excel/proposal-template`
- `POST /api/v1/import/excel/proposals/prefill`
- `GET /api/v1/export/proposals/{id}/excel`

### Dashboard
- `GET /api/v1/dashboard/summary`
- `GET /api/v1/dashboard/activity`
- `GET /api/v1/dashboard/shortcuts`

### Audit Logs / Files
- `GET /api/v1/audit-logs`
- `GET /api/v1/files`
- `POST /api/v1/files`
- `DELETE /api/v1/files/{id}`

---

## 11. Fluxos principais do sistema

## 11.1 Login
1. usuário acessa tela de login;
2. frontend renderiza `AuthLayout`;
3. operador informa `admin/admin` em ambiente dev;
4. `POST /api/v1/auth/login` valida credenciais;
5. backend autentica e retorna contexto do usuário;
6. frontend carrega permissões e redireciona para dashboard.

## 11.2 Cadastro de cliente
### Manual
1. usuário abre tela de clientes;
2. clica em “Novo cliente”;
3. preenche dados principais, contato e endereço;
4. frontend envia `POST /api/v1/clients`;
5. backend valida, salva, audita e retorna resource.

### Via Excel
1. usuário acessa tela de importação;
2. faz upload do Excel padrão;
3. backend interpreta aba de clientes;
4. clientes são cadastrados/atualizados;
5. sistema retorna resumo do processamento.

## 11.3 Cadastro de produto
### Manual
1. acessar módulo produtos;
2. criar novo produto;
3. salvar dados técnicos/comerciais;
4. backend valida e persiste.

### Importação auxiliar
1. upload ou seleção de planilha auxiliar;
2. leitura de abas/listas de referências;
3. sincronização com catálogo interno.

## 11.4 Criação de proposta
1. usuário seleciona cliente;
2. escolhe template;
3. adiciona produtos/equipamentos ou itens livres;
4. define quantidades, preços, descontos e observações;
5. backend recalcula totais;
6. salva proposta em transação;
7. gera snapshot em `proposal_versions`;
8. usuário pode exportar a proposta em Excel.

## 11.5 Atualização de status
Status sugeridos:
- `draft` / rascunho
- `in_progress` / em elaboração
- `sent` / enviada
- `approved` / aprovada
- `rejected` / recusada
- `cancelled` / cancelada

Fluxo:
1. usuário aciona mudança de status;
2. backend valida transição permitida;
3. grava histórico;
4. grava auditoria;
5. atualiza indicadores do dashboard.

## 11.6 Auditoria
Toda ação relevante gera:
- entidade afetada;
- usuário;
- ação;
- antes/depois resumidos;
- contexto técnico.

---

## 12. Arquitetura de importação do Excel

## 12.1 Premissas fundamentais
- o Excel tem múltiplas abas e fórmulas complexas;
- o sistema **não** dependerá do Excel para operar internamente;
- o backend Laravel será a fonte da verdade;
- fórmulas críticas serão reimplementadas no backend;
- o Excel será apenas:
  - fonte inicial de dados;
  - estrutura de mapeamento;
  - template de exportação e transição.

## 12.2 Tipos de importação previstos
1. **Importação de clientes**
2. **Importação de produtos/referências**
3. **Importação de base auxiliar comercial**
4. **Leitura de template de proposta para pré-preenchimento**

## 12.3 Componentes sugeridos

### `ExcelImportController`
Responsável por:
- receber upload;
- validar arquivo;
- encaminhar para action adequada;
- retornar resumo síncrono ou ID de job futuro.

### `ImportClientsAction`
Responsável por:
- ler aba de clientes;
- mapear colunas;
- normalizar CNPJ, e-mail, telefone e UF;
- fazer upsert de clientes;
- registrar inconsistências e resumo do lote.

### `ImportProductsAction`
Responsável por:
- ler listas de produtos/equipamentos/referências;
- mapear categoria, código, descrição, unidade e preço base;
- atualizar catálogo interno.

### `ImportProposalTemplateAction`
Responsável por:
- registrar template Excel padrão;
- capturar metadados de mapeamento por aba/célula/faixa;
- armazenar configuração JSON para exportação posterior.

### `ExcelMappingService`
Responsável por:
- centralizar o mapeamento entre abas/colunas/células e o domínio;
- manter configuração de aliases de nomes de colunas;
- abstrair diferenças entre versões do Excel.

### `ProposalSpreadsheetExportAction`
Responsável por:
- buscar proposta e itens no banco;
- aplicar template Excel cadastrado;
- preencher células e áreas nomeadas;
- gerar arquivo final para download.

### Jobs futuros
- `ProcessClientsExcelImportJob`
- `ProcessProductsExcelImportJob`
- `GenerateProposalSpreadsheetJob`

Esses jobs entram quando o volume crescer ou quando for necessário liberar a UI mais rápido.

## 12.4 Serviço central de mapeamento
O `ExcelMappingService` deve conhecer:
- nomes das abas equivalentes (`Rosto`, `Cálculos`, `Dados Cliente`, `Base Dados` etc.);
- aliases de colunas possíveis;
- ranges/células-chave do template;
- regras de normalização;
- versão do layout.

### Exemplo conceitual de responsabilidade
- identificar aba de clientes mesmo que nome varie levemente;
- localizar cabeçalho da tabela;
- mapear “Razão Social” para `company_name`;
- mapear “Nome Fantasia” para `trade_name`;
- mapear “Decisor” para contato primário ou contato decisor;
- mapear “UF” para `state`;
- consolidar registros por `cnpj` ou chave composta.

## 12.5 Estratégia de importação de clientes
### Entrada
Aba equivalente a “Dados Cliente”.

### Leitura esperada
Colunas como:
- CNPJ
- razão social
- nome fantasia
- decisor
- faixa de faturamento
- telefone
- email
- UF
- cidade
- microrregião
- observações

### Fluxo sugerido
1. upload da planilha;
2. identificação da aba correta;
3. leitura do cabeçalho;
4. resolução do mapa de colunas;
5. validação por linha;
6. normalização;
7. upsert por CNPJ;
8. criação de contatos/endereço quando disponíveis;
9. relatório final com importados, atualizados, ignorados e falhas.

## 12.6 Estratégia de importação de base auxiliar comercial
### Fonte
Aba equivalente a “Base Dados”.

### Possíveis dados
- vendedores;
- iniciais;
- equipes;
- função;
- referências auxiliares;
- classificações comerciais.

### Direção arquitetural
Nem tudo precisa virar tabela logo no início. Sugestão:
- usuários/vendedores podem ser sincronizados se houver identificador confiável;
- equipes e funções podem virar tabelas futuras ou enums/metadata provisória;
- referências auxiliares podem alimentar catálogo, lookup ou configurações JSON iniciais.

## 12.7 Leitura do template de proposta
### Abas relevantes
- `Rosto`: capa e campos principais da proposta;
- `Cálculos`: composição, quantidades, equipamentos, locação, fórmulas;
- abas de modalidades: variações do comercial;
- listas de referência: apoio à composição.

### Decisão essencial
O Excel **não executa a regra principal**. O backend precisa reimplementar:
- cálculo de subtotal;
- desconto;
- total;
- agrupamentos;
- regras de modalidade comercial;
- consistências de itens e quantidades.

### Uso correto do template
- como referência estrutural do modelo comercial atual;
- como arquivo-base para exportação final;
- como ponte de adoção para usuários acostumados ao processo antigo.

## 12.8 Fluxo de pré-preenchimento de proposta a partir do Excel
1. usuário envia planilha padrão;
2. sistema lê `Rosto` e `Cálculos`;
3. extrai cliente, itens, quantidades, observações e modalidade;
4. mapeia dados para DTO interno;
5. apresenta prévia para confirmação;
6. ao confirmar, cria proposta no banco;
7. backend recalcula tudo internamente;
8. gera primeira versão snapshot.

## 12.9 Estratégia de resiliência da importação
- não falhar o lote inteiro por uma linha inválida, salvo erro estrutural de layout;
- registrar erros por linha/aba;
- permitir modo “simulação” depois;
- versionar configuração de mapeamento;
- registrar arquivo importado como anexo para rastreabilidade.

---

## 13. Arquitetura de exportação para Excel

## 13.1 Objetivo
Gerar um Excel final compatível com o modelo comercial atual, mas abastecido pelo banco e não por fórmulas centrais da planilha.

## 13.2 Estratégia
- armazenar o template-base em `proposal_templates`;
- preencher células e blocos definidos em `mapping_config`;
- opcionalmente manter fórmulas cosméticas/secundárias, mas nunca depender delas para a regra principal;
- congelar valores essenciais vindos do backend.

## 13.3 Fluxo
1. usuário aciona exportação;
2. backend valida permissão;
3. carrega proposta, cliente, itens e template;
4. preenche planilha com dados atuais;
5. salva arquivo em storage;
6. registra exportação em `audit_logs`;
7. retorna URL/stream para download.

## 13.4 Conteúdo mínimo exportado
- dados do cliente;
- dados da proposta;
- itens e quantidades;
- totais;
- observações comerciais;
- metadados de responsável/validade;
- modalidade comercial aplicável.

---

## 14. Reimplementação da regra de negócio hoje dependente do Excel

## 14.1 O que deve sair do Excel e ir para o Laravel
- cálculos de quantidade x preço;
- descontos;
- subtotais e totais;
- regras de modalidade;
- validações de integridade de itens;
- composição de resumo comercial;
- decisão de status e versionamento.

## 14.2 O que pode permanecer no template de exportação
- formatação visual;
- layout institucional;
- abas auxiliares puramente expositivas;
- fórmulas não críticas para leitura humana, desde que o valor de negócio já venha consolidado do backend.

## 14.3 Benefícios
- elimina dependência operacional da planilha;
- melhora auditabilidade;
- prepara API para mobile;
- evita inconsistência de versões locais do Excel;
- facilita testes automatizados.

---

## 15. Serviços e Actions recomendados

## 15.1 Auth
- `LoginAction`
- `LogoutAction`
- `GetAuthenticatedUserAction`

## 15.2 Users / Roles
- `CreateUserAction`
- `UpdateUserAction`
- `ChangeUserStatusAction`
- `UpdateUserPasswordAction`
- `SyncUserRolesAction`

## 15.3 Clients
- `CreateClientAction`
- `UpdateClientAction`
- `UpsertClientAction`
- `ImportClientsAction`

## 15.4 Products
- `CreateProductAction`
- `UpdateProductAction`
- `ImportProductsAction`

## 15.5 Proposals
- `CreateProposalAction`
- `UpdateProposalAction`
- `RecalculateProposalTotalsAction`
- `ChangeProposalStatusAction`
- `CreateProposalVersionAction`
- `PrefillProposalFromSpreadsheetAction`
- `ProposalSpreadsheetExportAction`

## 15.6 Files
- `UploadFileAction`
- `DeleteFileAction`

## 15.7 Dashboard
- `GetDashboardSummaryAction`
- `GetRecentActivityAction`

## 15.8 Services transversais
- `ExcelMappingService`
- `ProposalCalculationService`
- `AuditLogService`
- `StatusTransitionService`
- `SearchIndexService` ou `GlobalSearchService`

---

## 16. Dashboard: definição funcional e visual

## 16.1 Objetivo
Oferecer uma porta de entrada do sistema com cara de portal/ERP, centralizando navegação, resumo operacional e informações prioritárias.

## 16.2 Estrutura sugerida
### Topo do conteúdo
- breadcrumbs;
- título “Dashboard”;
- busca global em destaque;
- ações rápidas como “Nova proposta”, “Novo cliente”, “Importar Excel”.

### Coluna central principal
#### Bloco 1: BI / Resumos
- total de propostas;
- propostas por status;
- clientes recentes;
- produtos recentes;
- propostas vencendo.

#### Bloco 2: Atalhos administrativos
- usuários;
- permissões;
- templates;
- auditoria;
- importações.

#### Bloco 3: Cadastros
- clientes;
- produtos;
- propostas;
- anexos.

#### Bloco 4: Atividade recente
- últimas propostas alteradas;
- últimas importações;
- últimos logs relevantes.

### Coluna lateral direita
- lembretes;
- comunicados;
- notificações internas futuras;
- tarefas pendentes ou observações operacionais.

## 16.3 Busca global
Inicialmente pode pesquisar em:
- clientes;
- produtos;
- propostas.

Depois pode evoluir para:
- usuários;
- logs;
- arquivos.

---

## 17. Definição visual e funcional das telas principais

## 17.1 Tela de Login
### Objetivo
Autenticar o usuário com experiência limpa e corporativa.

### Layout
- `AuthLayout` full-screen;
- fundo degradê verde/água;
- card central arredondado.

### Componentes
- input e-mail/usuário;
- input senha;
- checkbox mostrar senha;
- botão entrar;
- links inferiores.

### Ações principais
- login;
- futura recuperação de senha;
- futuro cadastro controlado.

### Integrações API
- `POST /api/v1/auth/login`
- `GET /api/v1/auth/me`

## 17.2 Tela de Dashboard
### Objetivo
Ser hub operacional do sistema.

### Layout
- `AppLayout`;
- sidebar, topbar, busca global, cards e blocos.

### Componentes
- KPI cards;
- lista de atalhos;
- atividade recente;
- painel lateral de lembretes.

### Ações principais
- navegar para módulos;
- pesquisar;
- criar registros rapidamente.

### Integrações API
- `GET /api/v1/dashboard/summary`
- `GET /api/v1/dashboard/activity`

## 17.3 Listagem de clientes
### Objetivo
Consultar, filtrar e iniciar ações de cadastro/edição.

### Layout
- cabeçalho com título e botão “Novo cliente”;
- tabela com filtros;
- drawer ou modal opcional para filtros avançados.

### Componentes
- tabela server-side;
- chips de status;
- busca por nome/CNPJ;
- paginação.

### Ações principais
- criar;
- editar;
- visualizar;
- importar base Excel.

### Integrações API
- `GET /api/v1/clients`
- `POST /api/v1/clients`
- `PUT /api/v1/clients/{id}`
- `POST /api/v1/import/excel/clients`

## 17.4 Formulário de cliente
### Objetivo
Cadastrar ou editar cliente de forma completa e legível.

### Layout
- formulário em cards/seções;
- abas ou blocos: dados gerais, contato, endereço, observações.

### Componentes
- campos de CNPJ;
- razão social;
- fantasia;
- contatos;
- endereço;
- observações.

### Ações principais
- salvar;
- salvar e continuar;
- inativar.

### Integrações API
- `POST /api/v1/clients`
- `PUT /api/v1/clients/{id}`
- `GET /api/v1/clients/{id}`

## 17.5 Listagem de produtos
### Objetivo
Gerenciar catálogo comercial/técnico.

### Layout
- tabela com filtros por categoria/status;
- cabeçalho com ações.

### Componentes
- busca;
- chips de categoria/status;
- tabela server-side.

### Ações principais
- novo produto;
- editar;
- importar lista auxiliar.

### Integrações API
- `GET /api/v1/products`
- `POST /api/v1/products`
- `PUT /api/v1/products/{id}`
- `POST /api/v1/import/excel/products`

## 17.6 Formulário de produto
### Objetivo
Cadastrar produto/equipamento com dados técnicos e comerciais.

### Layout
- seções de identificação, comercial e técnico.

### Componentes
- código;
- nome;
- descrição;
- categoria;
- unidade;
- preço base;
- status;
- observações técnicas.

### Ações principais
- salvar;
- inativar;
- anexar documento técnico futuro.

### Integrações API
- `POST /api/v1/products`
- `PUT /api/v1/products/{id}`

## 17.7 Listagem de propostas
### Objetivo
Acompanhar pipeline comercial e acessar propostas rapidamente.

### Layout
- tabela com filtros por status, cliente, responsável e período.

### Componentes
- chips de status;
- busca;
- ações por linha;
- indicador de validade.

### Ações principais
- nova proposta;
- editar;
- mudar status;
- exportar Excel;
- visualizar versões.

### Integrações API
- `GET /api/v1/proposals`
- `PATCH /api/v1/proposals/{id}/status`
- `GET /api/v1/export/proposals/{id}/excel`

## 17.8 Formulário de proposta
### Objetivo
Criar proposta completa com fluxo comercial estruturado.

### Layout
- cabeçalho com dados gerais;
- bloco de cliente;
- bloco de template/modalidade;
- grid de itens;
- resumo financeiro lateral ou inferior;
- histórico de status/versões em aba.

### Componentes
- seletor de cliente;
- seletor de template;
- data/validade;
- tabela editável de itens;
- campos de observação;
- resumo de subtotal/desconto/total;
- timeline de status;
- painel de versões.

### Ações principais
- salvar rascunho;
- recalcular;
- gerar versão;
- alterar status;
- exportar Excel;
- pré-preencher a partir de Excel.

### Integrações API
- `POST /api/v1/proposals`
- `PUT /api/v1/proposals/{id}`
- `PATCH /api/v1/proposals/{id}/status`
- `POST /api/v1/proposals/{id}/versions`
- `GET /api/v1/export/proposals/{id}/excel`
- `POST /api/v1/import/excel/proposals/prefill`

## 17.9 Tela de importação de Excel
### Objetivo
Operar importações de clientes, produtos e pré-preenchimento de propostas.

### Layout
- cards por tipo de importação;
- upload area;
- instruções do layout esperado;
- tabela de histórico de importações futura.

### Componentes
- upload drag-and-drop;
- select de tipo de importação;
- resumo do resultado;
- lista de erros por linha.

### Ações principais
- enviar arquivo;
- validar;
- importar;
- baixar relatório de erros futuro.

### Integrações API
- `POST /api/v1/import/excel/clients`
- `POST /api/v1/import/excel/products`
- `POST /api/v1/import/excel/proposal-template`
- `POST /api/v1/import/excel/proposals/prefill`

## 17.10 Tela de auditoria/logs
### Objetivo
Dar visibilidade a alterações sensíveis do sistema.

### Layout
- tabela filtrável com painel de detalhes.

### Componentes
- filtros por módulo, ação, usuário e período;
- tabela;
- drawer/modal com before/after em JSON amigável.

### Ações principais
- consultar;
- filtrar;
- inspecionar detalhes.

### Integrações API
- `GET /api/v1/audit-logs`

## 17.11 Tela de usuários e permissões
### Objetivo
Administrar acesso à plataforma.

### Layout
- grade com tabs: usuários, papéis, permissões.

### Componentes
- tabela de usuários;
- formulário lateral;
- chips de roles;
- matrizes simples de permissão futuras.

### Ações principais
- criar usuário;
- ativar/inativar;
- trocar senha;
- vincular papéis.

### Integrações API
- `GET /api/v1/users`
- `POST /api/v1/users`
- `PUT /api/v1/users/{id}`
- `PATCH /api/v1/users/{id}/status`
- `PATCH /api/v1/users/{id}/password`
- `GET /api/v1/roles`
- `GET /api/v1/permissions`

---

## 18. Estratégia de implementação por fases

## 18.1 Fase 1
- autenticação;
- seed `admin/admin`;
- layout base;
- dashboard inicial;
- usuários e permissões.

### Entregáveis
- login funcional;
- Sanctum configurado;
- AppLayout/AuthLayout;
- sidebar/topbar;
- RBAC inicial.

## 18.2 Fase 2
- clientes;
- produtos;
- CRUDs completos.

### Entregáveis
- listagens;
- formulários;
- filtros;
- policies e auditoria base.

## 18.3 Fase 3
- importação da base Excel de clientes;
- importação auxiliar de produtos/referências/comercial.

### Entregáveis
- upload de planilha;
- `ExcelMappingService`;
- import summary;
- upsert com rastreabilidade.

## 18.4 Fase 4
- propostas;
- itens;
- cálculos;
- status.

### Entregáveis
- criação e edição de proposta;
- proposal items;
- cálculo backend;
- histórico de status;
- versionamento inicial.

## 18.5 Fase 5
- exportação Excel;
- versionamento refinado;
- auditoria completa.

### Entregáveis
- template exportável;
- snapshot robusto;
- logs detalhados.

## 18.6 Fase 6
- refinamento visual;
- preparação para mobile.

### Entregáveis
- melhorias de UX;
- revisão de responsividade;
- contratos estáveis para aplicativo futuro.

---

## 19. Deploy inicial sugerido

## 19.1 Topologia simples
### Opção A — projeto único servido junto
- Laravel servindo API;
- build Vue compilado e servido pelo mesmo stack web;
- MySQL;
- storage local;
- queue worker opcional.

### Opção B — frontend separado
- Laravel API em domínio/subdomínio;
- Vue SPA em outro deploy;
- mesma autenticação baseada em API.

## 19.2 Recomendação prática inicial
Para começar sozinho, **Opção A** tende a ser mais simples operacionalmente.

## 19.3 Componentes de infraestrutura
- PHP 8.x
- Nginx ou Apache
- MySQL
- supervisor para queue worker futuro
- storage local
- Redis opcional depois para queue, cache e rate limiting refinado

---

## 20. Boas práticas obrigatórias e como aplicá-las

## 20.1 SRP
- requests validam;
- controllers recebem/devolvem;
- actions executam casos de uso;
- services reutilizam lógica transversal;
- policies autorizam;
- resources serializam.

## 20.2 Form Requests
Toda mutation relevante deve ter request dedicada.

## 20.3 Policies
Cada módulo com sua policy por ação.

## 20.4 Services/Actions
Toda regra de negócio fora do controller e fora do Vue.

## 20.5 API Resources
Toda saída relevante padronizada e estável.

## 20.6 Exceptions globais
Handler central traduz exceções para o padrão de erro da API.

## 20.7 Logs consistentes
- logs técnicos na aplicação;
- logs de negócio em `audit_logs`.

## 20.8 Nomes claros
Usar nomes explícitos, sem abreviações obscuras.

## 20.9 Preparação para testes
Sugestão de camadas de teste:
- unitários para services/enums/normalizers/cálculos;
- feature tests para endpoints;
- testes de importação com fixtures Excel controladas.

---

## 21. Crescimento futuro da solução

## 21.1 Curto prazo
- recuperação de senha;
- anexos por proposta;
- notificações internas;
- filtros salvos;
- mais indicadores no dashboard.

## 21.2 Médio prazo
- app mobile comercial consumindo a mesma API;
- filas para importações/exportações;
- Redis para cache/queue;
- storage em S3;
- versionamento avançado com diff entre snapshots;
- relatórios gerenciais.

## 21.3 Longo prazo
- integrações com ERP/CRM externos;
- workflow de aprovação multinível;
- assinatura eletrônica de propostas;
- motor de pricing mais sofisticado;
- eventual extração de módulos específicos se houver necessidade real.

---

## 22. Decisões arquiteturais-chave justificadas

## 22.1 Monólito modular
Escolhido para reduzir complexidade inicial e maximizar velocidade de entrega, sem sacrificar organização.

## 22.2 API-first
Escolhido para permitir múltiplos clientes de interface e preservar o backend como núcleo de negócio.

## 22.3 Sanctum
Escolhido por simplicidade, aderência ao Laravel e prontidão para SPA + mobile futuro.

## 22.4 Excel como integração de transição, não como motor
Escolhido para retirar dependência operacional da planilha e dar rastreabilidade ao processo.

## 22.5 Versionamento e auditoria desde cedo
Escolhido porque proposta comercial é entidade sensível, sujeita a revisão, negociação e rastreabilidade.

## 22.6 Snapshot de itens e dados do produto
Escolhido para preservar histórico comercial mesmo quando o cadastro mestre mudar.

---

## 23. Blueprint final de início

Se você for começar sozinho hoje, a linha mais segura é:
1. subir Laravel como núcleo da API;
2. implantar autenticação Sanctum;
3. criar seed `admin/admin`;
4. montar SPA Vue/Vuetify com `AuthLayout` e `AppLayout`;
5. entregar Users/Roles;
6. entregar Clients e Products;
7. implementar `ExcelMappingService` e importação de clientes;
8. construir Proposals com cálculo backend;
9. finalizar exportação Excel compatível;
10. consolidar auditoria, versionamento e preparação para mobile.

Esse caminho mantém a solução simples, profissional, sustentável e pronta para crescer com equipe sem reescrever a base.
