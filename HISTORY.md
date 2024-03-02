
# Documentação do projeto

## Entidades

### Entidade `Materia`

A entidade `Materia` representa o núcleo de nosso sistema de gerenciamento de conteúdo, onde cada instância de `Materia` simboliza um artigo ou publicação específica. A estrutura da entidade foi definida através de uma migration no Laravel, estabelecendo a tabela `materias` no banco de dados com os seguintes campos:

- **ID (`uuid`)**: Optamos por utilizar UUIDs em vez de IDs autoincrementais tradicionais para garantir a unicidade global das nossas chaves primárias. Isso nos permite maior flexibilidade na distribuição e escalabilidade do sistema, além de aumentar a segurança ao tornar os identificadores menos previsíveis.

- **Título (`string`)**: O campo `titulo` armazena o título da matéria, sendo uma informação obrigatória para cada registro. Optamos pelo tipo `string` para suportar uma ampla gama de títulos, mantendo a necessidade de otimização e eficiência na busca e exibição desses dados.

- **Descrição (`text`)**: O campo `descricao` contém um resumo ou uma breve descrição da matéria. Utilizamos o tipo `text` para acomodar descrições mais longas que o tipo `string` padrão, proporcionando flexibilidade para representar o conteúdo da matéria de forma concisa.

- **Imagem (`string`)**: Armazena o caminho relativo ou a URL para a imagem de destaque da matéria. A decisão de armazenar o caminho em vez da imagem propriamente dita visa facilitar a gestão dos recursos de mídia e otimizar o armazenamento e acesso a esses arquivos.

- **Texto Completo (`text`)**: Este campo, que é opcional (`nullable`), contém o corpo completo da matéria. A opção por torná-lo `nullable` reflete nosso desejo de permitir matérias que possam ser apenas teasers ou anúncios, sem um corpo de texto completo.

- **Data de Publicação (`timestamp`)**: Registra o momento exato da publicação da matéria. Escolhemos o tipo `timestamp` para capturar tanto a data quanto a hora da publicação, permitindo precisão na ordenação e filtragem das matérias por data.

- **Timestamps (`created_at`, `updated_at`)**: Laravel automaticamente inclui esses campos para rastrear a criação e a última atualização de cada matéria. Esses campos são essenciais para manter um registro da linha do tempo de cada conteúdo, facilitando a gestão e auditoria.

### Rotas da Entidade `Materia`

As operações CRUD (Create, Read, Update, Delete) para a entidade `Materia` são gerenciadas através de um conjunto de rotas API RESTful definidas no arquivo `routes/api.php`. A seguir, detalhamos as rotas disponíveis e suas respectivas funcionalidades:

#### Rota Padrão de Recursos

```php
Route::apiResource('materias', MateriaController::class);
```

Esta rota é uma conveniência do Laravel que automaticamente mapeia as operações CRUD básicas para métodos dentro do `MateriaController`. Ela gera as seguintes rotas:

- `GET /materias`: Lista todas as matérias (método `index`).
- `POST /materias`: Cria uma nova matéria (método `store`).
- `GET /materias/{id}`: Mostra uma matéria específica (método `show`).
- `PATCH /materias/{id}`: Atualiza uma matéria existente (método `update`).
- `DELETE /materias/{id}`: Exclui uma matéria específica (método `destroy`).

Essas rotas permitem a manipulação completa da entidade `Materia` através da API, seguindo os princípios RESTful para uma interface clara e consistente.

#### Rota de Atualização de Imagem

```php
Route::post('/materias/{id}/imagem', [MateriaController::class, 'updateImage'])->name('materias.updateImage');
```

- **Endpoint**: `POST /materias/{id}/imagem`
- **Controller**: `MateriaController@updateImage`
- **Nome da Rota**: `materias.updateImage`

Esta rota adicional foi criada para tratar especificamente a atualização da imagem associada a uma matéria. Diferentemente das operações CRUD padrão, a atualização da imagem é realizada através de um endpoint dedicado para melhor acomodar o upload de arquivos:

- **Funcionalidade**: Permite o upload de uma nova imagem para substituir a imagem atual de uma matéria específica.
- **Motivação**: Devido às limitações no suporte a `multipart/form-data` em requisições `PUT` ou `PATCH`, optamos por separar a atualização da imagem em uma requisição `POST` dedicada. Isso facilita o manuseio do upload de arquivos enquanto mantém a semântica e a integridade da nossa API.

### Decisão de Armazenamento de Imagens

Durante o desenvolvimento do projeto, tive a decisão crucial sobre onde e como armazenar as imagens associadas a cada matéria. Optei por utilizar o armazenamento local por uma razão específica, embora reconheçamos as vantagens de usar serviços de bucket para armazenamento de imagens.

#### Uso do Armazenamento Local

A escolha pelo armazenamento local das imagens foi feita principalmente devido à não disponibilidade de um serviço de bucket externo, como o Amazon S3, no momento do desenvolvimento. O armazenamento local, configurado através do disco `public` do Laravel, ofereceu uma solução prática e imediata que permitiu prosseguir com o desenvolvimento sem a necessidade de integração externa.

#### Vantagens do Armazenamento Local

- **Facilidade de Configuração**: O armazenamento local é facilmente configurável no Laravel, sem a necessidade de dependências externas ou configurações complexas.
- **Desenvolvimento Ágil**: Permitiu um desenvolvimento mais ágil, focando em outras áreas críticas do projeto sem se preocupar com limitações de acesso ou custos associados a serviços externos.
- **Acesso Imediato**: O acesso direto às imagens armazenadas localmente simplifica o processo de desenvolvimento e teste.

#### Abordagem Ideal: Uso de Buckets

Reconheço que, para ambientes de produção e aplicações em escala, o uso de um serviço de bucket dedicado, como o Amazon S3, é a abordagem ideal para o armazenamento de imagens e outros arquivos estáticos. Essa preferência é baseada em várias vantagens que esses serviços oferecem:

- **Escalabilidade**: Serviços como o Amazon S3 são altamente escaláveis, permitindo o armazenamento de uma grande quantidade de dados sem preocupações com o espaço em disco.
- **Desempenho**: A entrega de conteúdo pode ser otimizada através da integração com redes de distribuição de conteúdo (CDNs), melhorando significativamente o tempo de carregamento das imagens.
- **Segurança**: Serviços de bucket oferecem recursos avançados de segurança e gerenciamento de acesso, protegendo as imagens contra acesso não autorizado.
- **Disponibilidade e Durabilidade**: Garantem alta disponibilidade e durabilidade dos dados, reduzindo o risco de perda de dados.

### Model `Materia`

O model `Materia` encapsula a estrutura de dados e a lógica necessária para manipular as informações relativas às matérias publicadas.

#### Estrutura e Características

O modelo é definido com os seguintes componentes principais:

- **Uso de `HasFactory`**: Facilita a criação de instâncias do modelo para testes e seeding, proporcionando uma maneira conveniente de gerar dados de teste.

- **Chave Primária Não Autoincrementável (`$incrementing = false`)**: Indica que o modelo não utiliza IDs autoincrementáveis. Essa escolha está alinhada com a decisão de usar UUIDs como identificadores únicos para cada matéria, oferecendo vantagens em termos de escalabilidade e segurança.

- **Tipo de Chave Primária (`$keyType = 'string'`)**: Define o tipo da chave primária como `string`, necessário para suportar o uso de UUIDs em vez de inteiros.

- **Chave Primária (`$primaryKey = 'id'`)**: Especifica `id` como a chave primária do modelo, seguindo a convenção padrão, mas adaptada para aceitar UUIDs.

- **Atribuição em Massa (`$fillable`)**: Lista os atributos que podem ser atribuídos em massa, protegendo contra a atribuição em massa indesejada e facilitando a criação e atualização de instâncias do modelo a partir de arrays de dados.

- **Evento `creating`**: Um hook do evento `creating` é utilizado para garantir que, ao criar uma nova instância do modelo `Materia`, um UUID seja gerado e atribuído automaticamente ao `id` se ainda não estiver definido. Isso garante a unicidade e a consistência dos identificadores em toda a aplicação.

#### Justificativas e Decisões

- **Uso de UUIDs**: A escolha por UUIDs ao invés de IDs numéricos autoincrementáveis foi motivada pela necessidade de garantir a unicidade global dos identificadores, o que é particularmente útil em sistemas distribuídos ou em cenários onde a fusão de bancos de dados pode ocorrer. Além disso, UUIDs reduzem a previsibilidade dos IDs, aumentando a segurança.

- **Atributos Fillable**: Definir explicitamente os atributos `fillable` é uma prática de segurança essencial para prevenir a atribuição em massa indesejada, permitindo que apenas os campos especificados sejam atualizados diretamente a partir de arrays de entrada.

- **Geração Automática de UUID**: A lógica incorporada no evento `creating` para a geração automática de UUIDs simplifica a criação de novas matérias, removendo a necessidade de gerar e atribuir manualmente um UUID cada vez que uma nova matéria é criada.

### Motivações para o Uso do `MateriaResource`

O uso do `MateriaResource`  nessa aplicação Laravel foi motivado por várias considerações estratégicas, visando otimizar a apresentação e o gerenciamento dos dados da entidade `Materia` nas respostas da API:

#### 1. **Consistência nas Respostas da API**

O `MateriaResource` nos permite definir uma estrutura de dados consistente para as respostas da API, garantindo que todos os consumidores da API recebam dados formatados de maneira uniforme. Isso é crucial para a manutenibilidade e escalabilidade da aplicação.

#### 2. **Flexibilidade na Manipulação de Dados**

Permite uma manipulação flexível dos dados antes de serem enviados como resposta, incluindo a possibilidade de adicionar, remover ou modificar campos conforme necessário, sem alterar a estrutura do modelo subjacente.

#### 3. **Controle Refinado Sobre a Exposição de Dados**

Utilizando resources, ganhamos controle refinado sobre quais dados são expostos pela API, permitindo ocultar informações sensíveis ou desnecessárias e incluir dados derivados ou calculados, melhorando assim a segurança e a relevância das respostas.

#### 4. **Simplificação do Código do Controlador**

Ao delegar a responsabilidade pela formatação dos dados ao `MateriaResource`, reduzimos a complexidade nos controladores, tornando o código mais limpo e focado na lógica de negócios, ao invés de detalhes de implementação da apresentação de dados.

### Materia Controller

O `MateriaController` serve como o ponto de entrada para todas as operações relacionadas às matérias na nossa API. Ele é responsável por intermediar as requisições do cliente, realizar a lógica de negócios necessária e retornar as respostas apropriadas. Aqui estão as motivações e considerações principais para sua implementação:

#### Estrutura e Responsabilidades

- **CRUD Padrão**: Implementamos métodos padrão de CRUD (Create, Read, Update, Delete) utilizando as conveniências do Laravel, como o `apiResource`, para manter a consistência e seguir as melhores práticas RESTful.

- **Separação de Preocupações**: O controller foca exclusivamente na lógica de manipulação de requisições e respostas, delegando a lógica de negócios específica e o acesso a dados para a camada de repositório.

- **Validação de Dados**: Utilizamos `Requests` personalizados para a validação de dados de entrada, garantindo que apenas dados válidos e seguros sejam processados.

- **Manipulação de Imagens**: Para tratar especificamente da atualização de imagens, criamos um endpoint dedicado, refletindo a necessidade de tratar uploads de arquivos separadamente das demais operações de atualização.

#### Decisões de Design

- **Resources para Formatação de Respostas**: Adotamos o uso de `Resources` para a formatação das respostas, garantindo uma apresentação de dados consistente e facilitando futuras modificações na estrutura de dados retornada pela API.

- **Tratamento de Exceções**: Implementamos uma estratégia robusta de tratamento de exceções para garantir que erros sejam tratados de forma elegante e que respostas de erro apropriadas sejam retornadas para o cliente.

### Materia Repository

O padrão de repositório foi adotado para abstrair a lógica de acesso e manipulação dos dados da entidade `Materia`, promovendo a separação de preocupações e melhorando a testabilidade e manutenibilidade do código.

#### Estrutura e Responsabilidades

- **Abstração de Acesso a Dados**: O repositório age como uma ponte entre o modelo de dados `Materia` e os controladores, encapsulando todas as operações de banco de dados.

- **Reutilização de Código**: Facilita a reutilização de código de acesso a dados, visto que múltiplos controladores podem necessitar realizar operações similares.

- **Flexibilidade e Testabilidade**: A separação do acesso a dados em um repositório permite a substituição e a mockagem em testes, tornando o código mais flexível e fácil de testar.

#### Decisões de Design

- **Interface de Repositório**: Definimos uma interface para o repositório de `Materia`, garantindo que a implementação possa ser facilmente substituída sem afetar os consumidores do repositório.

- **Injeção de Dependências**: Utilizamos injeção de dependências para desacoplar a implementação concreta do repositório de seus consumidores, aumentando a modularidade do código.

- **Lógica de Negócios Centralizada**: Ao concentrar a lógica de negócios relacionada ao acesso a dados no repositório, mantemos os controladores enxutos e focados na lógica de aplicação.

### Uso do Request Personalizado para Store

A criação de uma matéria no sistema requer a coleta e validação cuidadosa de várias informações para garantir que os dados sejam não apenas completos, mas também válidos e seguros para serem persistidos no banco de dados. Para atingir esse objetivo, implementamos um `Request` personalizado, especificamente para a operação de `store` (`StoreMateriaRequest`), que encapsula todas as regras de validação necessárias.

#### Motivações

- **Segurança**: Garantir que todos os dados de entrada sejam validados antes de serem processados pelo sistema, ajudando a prevenir injeções de SQL, ataques XSS e outros vetores de vulnerabilidades comuns.
- **Integridade dos Dados**: Assegurar que apenas dados válidos e completos sejam armazenados, mantendo a integridade dos dados da aplicação.
- **Melhor UX**: Fornecer feedback imediato e detalhado sobre erros de validação aos usuários da API, permitindo correções rápidas e eficientes.

#### Regras de Validação

- **Título (`titulo`)**: Obrigatório, deve ser uma string e ter no máximo 255 caracteres. Garante que cada matéria tenha um título identificável e que esteja dentro de um limite de tamanho razoável.
- **Descrição (`descricao`)**: Obrigatória, deve ser uma string. Essencial para fornecer um resumo ou contexto sobre a matéria.
- **Imagem (`imagem`)**: Obrigatória, deve ser um arquivo de imagem e ter um tamanho máximo de 10 MB (10240 KB). Isso assegura que uma imagem esteja presente para cada matéria e que o tamanho do arquivo seja gerenciável.
- **Texto Completo (`texto_completo`)**: Obrigatório, deve ser uma string. Contém o corpo principal da matéria, sendo essencial para o conteúdo completo da publicação.
- **Data de Publicação (`data_de_publicacao`)**: Obrigatória, deve ser uma data válida. Importante para registrar quando a matéria foi ou será publicada.

### Uso do Request Personalizado para Update

O `UpdateMateriaRequest` encapsula as regras de validação aplicáveis às solicitações de atualização de uma matéria. Diferentemente da criação de uma nova matéria, a atualização pode não exigir que todos os campos sejam fornecidos; portanto, as regras são ajustadas para refletir essa flexibilidade.

#### Motivações

- **Flexibilidade na Atualização**: Permitir que os usuários atualizem uma ou mais propriedades da matéria sem a necessidade de enviar todos os detalhes novamente. Isso é particularmente útil para interfaces de usuário que permitem edições incrementais.
- **Manutenção da Integridade dos Dados**: Assegurar que quaisquer dados fornecidos na atualização sejam validados para manter a integridade e a consistência dos dados armazenados.
- **Segurança**: Proteger contra dados inválidos ou maliciosos, mantendo a aplicação segura contra potenciais vulnerabilidades.

#### Regras de Validação

- **Título (`titulo`)**: Validado se fornecido (`sometimes`), deve ser uma string e ter no máximo 255 caracteres. Isso permite a atualização do título sem impor a necessidade de reenviar um título existente.
- **Descrição (`descricao`)**: Aplica a mesma lógica do título, permitindo atualizações incrementais da descrição da matéria.
- **Texto Completo (`texto_completo`)**: Também segue a regra `sometimes`, permitindo que o corpo da matéria seja atualizado separadamente.
- **Data de Publicação (`data_de_publicacao`)**: Validada apenas quando fornecida, deve ser uma data válida, permitindo a atualização da data de publicação conforme necessário.

### Considerações Especiais

- **Ausência do Campo `Imagem`**: Nota-se que, diferentemente do `StoreMateriaRequest`, o campo `imagem` não é incluído neste conjunto de validações. Isso reflete a decisão de tratar atualizações de imagens através de um endpoint separado, otimizando a manipulação de uploads de arquivos e separando a lógica de atualização de dados textuais da manipulação de arquivos binários.

## Melhorias Futuras

Embora o projeto já ofereça um conjunto robusto de funcionalidades, há sempre espaço para aprimoramentos e adições que podem enriquecer ainda mais a aplicação. Algumas das áreas identificadas para melhorias futuras incluem:

### Testes Unitários e de Integração

- **Cobertura de Testes**: Ampliar a cobertura de testes unitários e de integração para garantir a confiabilidade e a qualidade do código. Isso inclui testar todas as operações CRUD, validações, e comportamentos específicos do domínio da aplicação.

- **Automatização de Testes**: Implementar uma pipeline CI/CD que inclua a execução automatizada de testes a cada push ou pull request, ajudando a identificar e corrigir regressões mais rapidamente.

### Segurança

- **Autenticação e Autorização**: Reforçar a segurança da aplicação implementando ou expandindo sistemas de autenticação e autorização, assegurando que apenas usuários autorizados possam criar, atualizar ou excluir matérias.

- **Auditoria**: Implementar logs de auditoria para rastrear as alterações feitas nas matérias, incluindo quem fez a mudança e quando.

### Recursos Adicionais

- **Comentários e Avaliações**: Adicionar a capacidade de usuários deixarem comentários ou avaliações em cada matéria, aumentando a interatividade e o engajamento na plataforma.

- **APIs de Terceiros**: Integrar com APIs de terceiros para enriquecer as matérias com informações adicionais ou para compartilhar as matérias em redes sociais diretamente da aplicação.

## Conclusão

O projeto já alcançou vários marcos importantes, estabelecendo uma base sólida sobre a qual podemos construir. As melhorias sugeridas visam não apenas aprimorar a funcionalidade e a segurança da aplicação, mas também garantir uma experiência de usuário superior e facilitar a manutenção e expansão futuras do projeto.
