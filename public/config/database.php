<?php

	namespace CreditCard\Config;

	use PDO;

	class Sql {

		const HOSTNAME = "mysql";
		const USERNAME = "root";
		const PASSWORD = "root";
		const DBNAME = "mysql";

		private $conn;

		public function __construct() {
			$this->conn = new PDO(
				"mysql:dbname=".self::DBNAME.";host=".self::HOSTNAME, 
				self::USERNAME,
				self::PASSWORD
			);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		public function criarPagamentoNoBancoDeDados($data) {
			
			// Consulta SQL de inserção
			$sql = "INSERT INTO payments_info (transaction_amount, installments, token, payment_method_id, payer_entity_type, payer_type, payer_email, payer_identification_type, payer_identification_number, notification_url, created_at, updated_at, status)
			VALUES (:transaction_amount, :installments, :token, :payment_method_id, :payer_entity_type, :payer_type, :payer_email, :payer_identification_type, :payer_identification_number, :notification_url, NOW(), NULL, 'PENDING')";

			$stmt = $this->conn->prepare($sql);

			// Executa a consulta com os valores dos parâmetros
			$stmt->execute([
				'transaction_amount' => $data['transaction_amount'],
				'installments' => $data['installments'],
				'token' => $data['token'],
				'payment_method_id' => $data['payment_method_id'],
				'payer_entity_type' => $data['payer']['entity_type'],
				'payer_type' => $data['payer']['entity_type'],
				'payer_email' => $data['payer']['email'],
				'payer_identification_type' => $data['payer']['identification']['type'],
				'payer_identification_number' => $data['payer']['identification']['number'],
				'notification_url' => $data['notification_url']
			]);
	
			// Retorna o ID inserido
			return $this->conn->lastInsertId();

		}

		public function buscarPagamentos() {

			// Consulta SQL para selecionar todos os pagamentos
			$sql = "SELECT * FROM payments_info";
			$stmt = $this->conn->prepare($sql);
		
			// Executa a consulta
			$stmt->execute();
		
			// Obtém os resultados como um array associativo
			$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
			// Retorna os pagamentosc
			return $payments;
	
		}

		public function buscarPagamentoPorId($id) {

			// Consulta SQL para selecionar um pagamento pelo ID
			$sql = "SELECT * FROM payments_info WHERE id = :id";
			$stmt = $this->conn->prepare($sql);
			
			// Executa a consulta com o ID como parâmetro
			$stmt->execute(['id' => $id]);
			
			// Obtém o pagamento como um array associativo
			$payment = $stmt->fetch(PDO::FETCH_ASSOC);
			
			// Retorna o pagamento encontrado
			return $payment;
	
		}

		public function atualizarStatusPagamento($id, $status) {
			// Consulta SQL para atualizar o status do pagamento com base no ID
			$sql = "UPDATE payments_info SET status = :status WHERE id = :id";
			$stmt = $this->conn->prepare($sql);
			
			// Executa a consulta com os parâmetros fornecidos
			$stmt->execute(['id' => $id, 'status' => $status]);
		}

	}