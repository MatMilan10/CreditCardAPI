import React, { useState, useEffect } from 'react';
import { initMercadoPago, getInstallments, getPaymentMethods, createCardToken } from '@mercadopago/sdk-react';
import './App.css'

function Pagamento() {
  const [email, setEmail] = useState('');
  const [documento, setDocumento] = useState('CPF');
  const [numeroIdentificacao, setNumeroIdentificacao] = useState('');
  const [valorPagamento, setValorPagamento] = useState('');
  const [numeroCartao, setNumeroCartao] = useState('');
  const [nomeTitular, setNomeTitular] = useState('');
  const [mesExpiracao, setMesExpiracao] = useState('');
  const [anoExpiracao, setAnoExpiracao] = useState('');
  const [cvv, setCvv] = useState('');
  const [parcelas, setParcelas] = useState([]);
  const [metodosPagamento, setMetodosPagamento] = useState([]);

  useEffect(() => {
      // Inicializa o SDK do Mercado Pago
      initMercadoPago('TEST-29662c7c-eda1-4eb7-b5b9-fd67705ccdb5');

      // Carrega as opções de parcelamento e os métodos de pagamento
      carregarParcelas();
      carregarMetodosPagamento();
  }, []);

  const carregarParcelas = async () => {
      try {
          const dadosDoCartaoEPagamento = {
              amount: parseFloat(valorPagamento),
              payment_method_id: carregarMetodosPagamento(),
          };

          const parcelas = await getInstallments(dadosDoCartaoEPagamento);
          setParcelas(parcelas);
      } catch (error) {
          console.error('Erro ao carregar parcelas:', error);
      }
  };

  const carregarMetodosPagamento = async () => {
      try {
          const metodos = await getPaymentMethods();
          setMetodosPagamento(metodos);
      } catch (error) {
          console.error('Erro ao carregar métodos de pagamento:', error);
      }
  };

  const handlePagamento = async () => {
      try {
          const cardToken = await createCardToken({
              cardNumber: numeroCartao,
              cardholderName: nomeTitular,
              cardExpirationMonth: mesExpiracao,
              cardExpirationYear: anoExpiracao,
              securityCode: cvv
          });

          const pagamento = {
              transaction_amount: parseFloat(valorPagamento),
              installments: parcelas,
              token: cardToken.id,
              payment_method_id: 'visa',
              payer: {
                  email: email,
                  identification: {
                      type: documento,
                      number: numeroIdentificacao
                  }
              }
          };

          enviarPagamentoParaBackend(pagamento);
      } catch (error) {
          console.error('Erro ao processar o pagamento:', error);
      }
  };

  const enviarPagamentoParaBackend = async (dadosPagamento) => {
      try {
          const response = await fetch('http://localhost/rest/payments', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify(dadosPagamento)
          });

          if (response.ok) {
              console.log('Pagamento efetuado com sucesso!');
          } else {
              console.error('Erro ao processar o pagamento.');
          }
      } catch (error) {
          console.error('Erro ao processar o pagamento:', error);
      }
  };

    return (
        <div className="container">
          <div className="column">
            <h2>Dados do Pagador</h2>
            <div className="form-group">
                <label>Email do Pagador</label>
                <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} />
            </div>
            <div className="form-group">
                <label>Documento</label>
                <select value={documento} onChange={(e) => setDocumento(e.target.value)}>
                    <option value="CPF">CPF</option>
                    <option value="CNPJ">CNPJ</option>
                </select>
            </div>
            <div className="form-group">
                <label>Número de Identificação</label>
                <input type="text" value={numeroIdentificacao} onChange={(e) => setNumeroIdentificacao(e.target.value)} />
            </div>
          </div>
          <div className="column">
            <h2>Dados do Pagamento</h2>
            <div className="form-group">
                <label>Valor do Pagamento</label>
                <input type="text" value={valorPagamento} onChange={(e) => setValorPagamento(e.target.value)} />
            </div>
            <div className="form-group">
                <label>Número do Cartão</label>
                <input type="text" value={numeroCartao} onChange={(e) => setNumeroCartao(e.target.value)} />
            </div>
            <div className="form-group">
                <label>Nome do Titular</label>
                <input type="text" value={nomeTitular} onChange={(e) => setNomeTitular(e.target.value)} />
            </div>
            <div className="form-group">
                <label>Mês de Expiração</label>
                <input type="text" value={mesExpiracao} onChange={(e) => setMesExpiracao(e.target.value)} />
            </div>
            <div className="form-group">
                <label>Ano de Expiração</label>
                <input type="text" value={anoExpiracao} onChange={(e) => setAnoExpiracao(e.target.value)} />
            </div>
            <div className="form-group">
                <label>CVV</label>
                <input type="text" value={cvv} onChange={(e) => setCvv(e.target.value)} />
            </div>
            <div className="form-group">
                <label>Parcelas</label>
                <select value={parcelas} onChange={(e) => setParcelas(e.target.value)}>
                    <option value="1">1x</option>
                    <option value="2">2x</option>
                    <option value="3">3x</option>
                </select>
            </div>
          </div>
          <div className="button-container">
            <button onClick={handlePagamento}>Pagar</button>
          </div>
        </div>
    );
}

export default Pagamento;