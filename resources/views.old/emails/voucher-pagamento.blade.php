<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher X-Coin Confirmado</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #1a6b5f 0%, #2d8b7c 100%);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        
        .header img {
            max-width: 120px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        
        .content {
            padding: 30px 20px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1a6b5f;
            margin-bottom: 20px;
        }
        
        .intro-text {
            font-size: 15px;
            color: #666666;
            margin-bottom: 25px;
        }
        
        .voucher-info {
            background-color: #f8f9fa;
            border-left: 4px solid #2d8b7c;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        
        .voucher-info-item {
            margin-bottom: 15px;
        }
        
        .voucher-info-item:last-child {
            margin-bottom: 0;
        }
        
        .voucher-info-label {
            font-weight: 600;
            color: #333333;
            display: inline-block;
            min-width: 140px;
        }
        
        .voucher-info-value {
            color: #555555;
            word-break: break-all;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a6b5f;
            margin: 30px 0 15px 0;
        }
        
        .corretoras-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        
        .corretora-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
            vertical-align: middle;
        }
        
        .corretora-placeholder {
            width: 100px;
            height: 60px;
            background-color: #e9ecef;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #6c757d;
            margin: 0 auto;
        }
        
        .corretora-item img {
            max-width: 100px;
            max-height: 60px;
            height: auto;
            border-radius: 8px;
        }
        
        .thank-you {
            background-color: #e8f5f3;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            margin: 25px 0;
        }
        
        .thank-you p {
            margin: 0;
            color: #1a6b5f;
            font-weight: 500;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 25px 20px;
            font-size: 12px;
            color: #6c757d;
            line-height: 1.8;
        }
        
        .footer p {
            margin: 8px 0;
        }
        
        .footer-links {
            margin-top: 15px;
            text-align: center;
        }
        
        .footer-links a {
            color: #2d8b7c;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        .website {
            text-align: center;
            margin-top: 10px;
            font-weight: 600;
            color: #1a6b5f;
        }
        
        /* Responsividade */
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .header h1 {
                font-size: 20px;
            }
            
            .greeting {
                font-size: 16px;
            }
            
            .voucher-info {
                padding: 15px;
            }
            
            .voucher-info-label {
                display: block;
                min-width: auto;
                margin-bottom: 5px;
            }
            
            .corretoras-grid {
                display: block;
            }
            
            .corretora-item {
                display: block;
                width: 100%;
                margin-bottom: 15px;
            }
            
            .corretora-placeholder {
                width: 120px;
                height: 70px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>✅ Voucher Confirmado!</h1>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p class="greeting">Olá, {{ $clientName }}</p>
            
            <p class="intro-text">
                Segue abaixo as informações do seu voucher:
            </p>
            
            <!-- Voucher Info -->
            <div class="voucher-info">
                <div class="voucher-info-item">
                    <span class="voucher-info-label">Código:</span>
                    <span class="voucher-info-value">{{ $codigoVoucher }}</span>
                </div>
                <div class="voucher-info-item">
                    <span class="voucher-info-label">Valor do Voucher:</span>
                    <span class="voucher-info-value">R$ {{ $valor }}</span>
                </div>
                <div class="voucher-info-item">
                    <span class="voucher-info-label">Status:</span>
                    <span class="voucher-info-value">{{ $status }}</span>
                </div>
            </div>
            
            <!-- Corretoras -->
            <p class="section-title">Corretoras compatíveis com o Xcoin:</p>
            
            <div class="corretoras-grid">
                <div class="corretora-item">
                    <div class="corretora-placeholder">Corretora 1</div>
                </div>
                <div class="corretora-item">
                    <div class="corretora-placeholder">Corretora 2</div>
                </div>
                <div class="corretora-item">
                    <div class="corretora-placeholder">Corretora 3</div>
                </div>
                <div class="corretora-item">
                    <div class="corretora-placeholder">Corretora 4</div>
                </div>
            </div>
            
            <!-- Thank You -->
            <div class="thank-you">
                <p>👍 Obrigado por utilizar nossos serviços!</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>
                A XCoin está em conformidade com o Padrão de Segurança de Dados do Setor de 
                Cartões de Pagamento (PCI DSS) para garantir sua segurança e privacidade. 
                Realizamos regularmente varreduras de vulnerabilidades e testes de penetração 
                de acordo com os requisitos do PCI DSS para o nosso modelo de negócio.
            </p>
            
            <p style="margin-top: 15px;">
                O Voucher X-Coin é vinculado a X-pay.<br>
                <strong>CNPJ:</strong> 52.979.370/0001-16
            </p>
            
            <div class="footer-links">
                <a href="https://www.xcoinpay.cash/termos" target="_blank">Termos de uso</a>
                |
                <a href="https://www.xcoinpay.cash/privacidade" target="_blank">Política de privacidade</a>
            </div>
            
            <p class="website">
                <a href="https://www.xcoinpay.cash" target="_blank" style="color: #1a6b5f; text-decoration: none;">
                    www.xcoinpay.cash
                </a>
            </p>
        </div>
    </div>
</body>
</html>