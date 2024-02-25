CREATE DATABASE credit_card_api;

CREATE TABLE payments_info (
    id VARCHAR(50) PRIMARY KEY AUTO_INCREMENT COMMENT 'Identificador único de cada pagamento',
    transaction_amount FLOAT NOT NULL,
    installments INT NOT NULL,
    token VARCHAR(50) NOT NULL,
    payment_method_id VARCHAR(20) NOT NULL COMMENT 'Id do método de pagamento',
    payer_entity_type VARCHAR(20) NOT NULL DEFAULT 'individual',
    payer_type VARCHAR(20) NOT NULL DEFAULT 'customer',
    payer_email VARCHAR(50) NOT NULL,
    payer_identification_type VARCHAR(4) NOT NULL,
    payer_identification_number VARCHAR(11) NOT NULL,
    notification_url VARCHAR(50) NOT NULL COMMENT 'URL que recebe os hooks com as notificações do pagamento',
    created_at DATE NOT NULL,
    updated_at DATE NULL,
    status ENUM('PENDING', 'PAID', 'CANCELED') NOT NULL
);
