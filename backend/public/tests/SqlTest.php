<?php

    use PHPUnit\Framework\TestCase;
    use CreditCard\Config\Sql;

    class SqlTest extends TestCase {
        public function testCriarPagamentoNoBancoDeDados()
        {
            // Cria uma instância da classe Sql
            $sql = new Sql();

            // Dados de exemplo
            $data = [
                'transaction_amount' => 100.00,
                'installments' => 1,
                'token' => 'abc123',
                'payment_method_id' => 'visa',
                'payer' => [
                    'entity_type' => 'individual',
                    'email' => 'test@example.com',
                    'identification' => [
                        'type' => 'CPF',
                        'number' => '12345678900'
                    ]
                ],
                'notification_url' => 'https://webhook.site'
            ];

            // Teste: Tenta inserir um pagamento no banco de dados
            $paymentId = $sql->criarPagamentoNoBancoDeDados($data);

            // Teste: Verifica se o ID do pagamento foi retornado
            $this->assertGreaterThan(0, $paymentId);
        }
    }
