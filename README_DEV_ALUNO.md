# 🎓 Scripts para Criar Alunos de Teste

Ferramentas criadas para facilitar o desenvolvimento da área de membros, permitindo criar alunos de teste sem precisar fazer pagamentos reais.

## 📋 Opções Disponíveis

### 1. Via Interface Web (Mais Fácil) 🌐

Acesse: **`/dev/create-aluno`**

Uma interface bonita e intuitiva onde você pode:
- Criar alunos com dados personalizados
- Gerar CPF automaticamente (ou informar um)
- Associar um produto e criar pedido pago automaticamente
- Ver lista de todos os alunos criados

**Exemplo de uso:**
1. Acesse `http://seu-dominio.local/dev/create-aluno`
2. Preencha os dados (ou use os valores padrão)
3. Marque "Criar pedido pago associado"
4. Clique em "Criar Aluno"
5. Faça login na área de membros com as credenciais criadas

### 2. Via Comando Artisan (Terminal) 💻

#### Criar Aluno com Pedido Pago

```bash
php artisan aluno:create-test --with-pedido
```

**Opções disponíveis:**
```bash
php artisan aluno:create-test \
  --name="João Silva" \
  --email="joao@teste.com" \
  --password="12345678" \
  --cpf="123.456.789-00" \
  --produto=1 \
  --with-pedido
```

**Parâmetros:**
- `--name`: Nome do aluno (padrão: "Aluno Teste")
- `--email`: Email do aluno (padrão: "aluno@teste.com")
- `--password`: Senha do aluno (padrão: "12345678")
- `--cpf`: CPF do aluno (se não informado, gera automaticamente)
- `--produto`: ID do produto para associar (opcional)
- `--with-pedido`: Cria um pedido pago associado

#### Adicionar Pedido a Aluno Existente

```bash
php artisan aluno:add-pedido 1 --produto=1
```

**Parâmetros:**
- `aluno_id`: ID do aluno (obrigatório)
- `--produto`: ID do produto (se não informado, usa o primeiro produto ativo)
- `--valor`: Valor do pedido (se não informado, usa o preço do produto)

#### Listar Todos os Alunos

```bash
php artisan aluno:list
```

## 🚀 Exemplos Práticos

### Exemplo 1: Criar aluno rápido com pedido
```bash
php artisan aluno:create-test --with-pedido
```

### Exemplo 2: Criar aluno personalizado
```bash
php artisan aluno:create-test \
  --name="Maria Santos" \
  --email="maria@teste.com" \
  --password="senha123" \
  --with-pedido \
  --produto=2
```

### Exemplo 3: Adicionar mais produtos a um aluno existente
```bash
# Aluno ID 1, adicionar produto ID 3
php artisan aluno:add-pedido 1 --produto=3
```

## ⚠️ Importante

- **Estas rotas só funcionam em ambiente de desenvolvimento** (local/development ou quando `APP_DEBUG=true`)
- **NUNCA deixe essas rotas ativas em produção!**
- Os pedidos criados são marcados como "pago" mas não têm transação real
- Os CPFs gerados são apenas para teste e não são válidos para uso real

## 📝 Estrutura Criada

Quando você cria um aluno com pedido:
1. ✅ Aluno é criado na tabela `alunos`
2. ✅ Pedido é criado na tabela `pedidos` com status `'pago'`
3. ✅ Pedido é associado ao aluno (`aluno_id`)
4. ✅ Pedido é associado ao produto (`produto_id`)
5. ✅ Dados do comprador são salvos no campo `comprador` (JSON)

## 🎯 Próximos Passos

Após criar o aluno:
1. Acesse `/alunos` e faça login
2. Você verá os produtos que o aluno tem acesso
3. Pode navegar pela área de membros normalmente
4. Teste todas as funcionalidades!

## 🔧 Troubleshooting

**Erro: "Aluno com email já existe"**
- Use um email diferente ou delete o aluno existente

**Erro: "Nenhum produto ativo encontrado"**
- Crie um produto primeiro no painel administrativo

**Rotas não funcionam**
- Verifique se `APP_DEBUG=true` no `.env`
- Verifique se está em ambiente `local` ou `development`

---

**Desenvolvido para facilitar o desenvolvimento da área de membros! 🚀**
