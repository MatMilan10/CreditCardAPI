<?php

    namespace CreditCard\Controllers;

    use CreditCard\Config\Sql;

    class PagamentosController {
        
        public function criarPagamento($request, $response) {

            $data = $request->getParsedBody();

            // Verica se os dados foram fornecidos
            if (empty($data)) {
                return $response->withStatus(400)->withJson(['error' => 'Payment not provided in the request body']);
            }

            // Verifique se os campos obrigatórios estão presentes
            $requiredFields = ['transaction_amount', 'installments', 'token', 'payment_method_id', 'payer'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    return $response->withStatus(422)->withJson(["error" => "Invalid payment provided. The field '{$field}' is null or with invalid values"]);
                }
            }

            // Define os valores padrão
            $data['payer']['entity_type'] = 'individual';
            $data['payer']['type'] = 'customer';
            $data['notification_url'] = 'https://webhook.site';

            // Crie uma instância da classe Sql
            $sql = new Sql();

            // Insere o pagamento no Banco de Dados
            $paymentId = $sql->criarPagamentoNoBancoDeDados($data);

            // Verifica se o pagamento foi criado com sucesso
            if ($paymentId) {
                $responseData = [
                    "id" => $paymentId,
                    "created_at" => date("Y-m-d H:i:s")
                ];
                return $response->withStatus(201)->withJson($responseData);
            } else {
                return $response->withStatus(500)->withJson(["error" => "Failed to create payment"]);
            }

        }

        public function listarPagamentos($response) {

            $sql = new Sql();

            // Busca os pagamentos
            $payments = $sql->buscarPagamentos();

            // Converte a lista de pagamentos para JSON
            $jsonResponse = json_encode($payments);

            // Defina o cabeçalho como JSON
            $response = $response->withHeader('Content-Type', 'application/json');

            // Retorna a resposta com a lista de pagamentos em JSON
            $response->getBody()->write($jsonResponse);
            return $response->withStatus(200);

        }

        public function verPagamento($response, $args) {

            $id = $args['id'];

            $sql = new Sql();

            // Busca o pagamento pelo ID fornecido
            $payment = $sql->buscarPagamentoPorId($id);

            if (!$payment) {
                // Se o pagamento não for encontrado
                return $response->withStatus(404)->withJson(['message' => 'Payment not found with the specified id']);
            }

            // Retorne os detalhes como JSON
            return $response->withJson($payment)->withStatus(200);
        }

        public function confirmarPagamento($response, $args) {
            $id = $args['id'];
    
            // Verifica se o pagamento existe
            $sql = new Sql();
            $payment = $sql->buscarPagamentoPorId($id);

            if (!$payment) {
                // Se o pagamento não for encontrado
                return $response->withStatus(404)->withJson(['message' => 'Bankslip not found with the specified id']);
            }

            // Atualiza o status do pagamento para PAID
            $sql->atualizarStatusPagamento($id, 'PAID');
        
            // Retorna indicando sucesso
            return $response->withStatus(204);

        }

        public function cancelarPagamento($response, $args) {
            
            $id = $args['id'];
            
            // Verifica se o pagamento existe
            $sql = new Sql();
            $payment = $sql->buscarPagamentoPorId($id);
        
            if (!$payment) {
                // Se o pagamento não for encontrado
                return $response->withStatus(404)->withJson(['message' => 'Payment not found with the specified id']);
            }
        
            // Atualiza o status do pagamento para CANCELED
            $sql->atualizarStatusPagamento($id, 'CANCELED');
        
            // Retorna indicando sucesso
            return $response->withStatus(204)->withJson(['message' => 'Payment canceled']);
            
        }
    }
