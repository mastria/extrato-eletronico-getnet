
# Extrato Eletrônico Getnet

💡Classe para ler o arquivo do **EXTRATO ELETRÔNICO** gerado pela **Getnet** e separar as informações de cada linha em um array com os valores em suas respectivas chaves.

🛟 Classe baseada na versão V.10.0 | V1.2023 do [manual de especificação técnica](https://github.com/mastria/extrato-eletronico-getnet/blob/main/manual/Manual%20Extrato%20Eletronico_V2.2023.pdf) cedido pela empresa.

🛠️ A classe foi criada para ser utilizada em um projeto [Laravel](https://laravel.com/), porém, pode ser adaptada a qualquer projeto (lembre-se de ajustar a conversão de valores de acordo com as suas preferências). 
  
  
## Manual Getnet

[Manual Extrato Eletronico_V2.2023](https://github.com/mastria/extrato-eletronico-getnet/blob/main/manual/Manual%20Extrato%20Eletronico_V2.2023.pdf)
  
  
## Exemplo de saída (JSON)

```json
[
    {
        "TipoRegistro": 0,
        "DataCriacaoArquivo": "2023-01-01",
        "HoraCriacaoArquivo": "10:00:00",
        "DataReferenciaMovimento": "2023-01-01",
        "VersaoArquivo": "CEADM100",
        "CodigoEstabelecimento": "123456",
        "CNPJAdquirente": 12345678910,
        "NomeAdquirente": "GETNET S.A.",
        "Sequencia": 12,
        "CodigoAdquirente": "GS",
        "VersaoLayout": "SANT. V.10 400 BYTES"
    },
    {
        "TipoRegistro": 1,
        "CodigoEstabelecimento": "123456",
        "CodigoProduto": "SM",
        "FormaCaptura": "INT",
        "NumeroRV": 123456789,
        "DataRV": "2023-01-01",
        "DataPagamentoRV": "2023-01-01",
        "Banco": 123,
        "Agencia": 123,
        "ContaCorrente": "0000012345678",
        "NumeroCVAceitos": 1,
        "NumeroCVRejeitados": 0,
        "ValorBruto": 1000,
        "ValorLiquido": 1000.50,
        "ValorTarifa": 0,
        "ValorTaxaDesconto": 30.50,
        "ValorRejeitado": 0,
        "ValorCredito": 1000.50,
        "ValorEncargos": 0,
        "IndicadorTipoPagamento": "Pagamento Normal",
        "NumeroParcelaRV": 3,
        "QuantidadesParcelasRV": 12,
        "CodigoEstabelecimentoComercialCentralizadorPagamentos": "123456",
        "NumeroOperacaoAntecipacao": 0,
        "DataVencimentoOriginalRVAntecipado": null,
        "CustoOperacao": 0,
        "ValorLiquidoRVAntecipado": 0,
        "NumeroControleOperacaoCobranca": 0,
        "ValorLiquidoCobranca": 0,
        "IdCompensacao": 0,
        "Moeda": "BRL",
        "IdentificadorBaixaCobrancaServicoExterna": null,
        "SinalTransacao": "+",
        "Metadado1": "CC",
        "ContaPagamento": 0
    },
    {
        "TipoRegistro": 2,
        "CodigoEstabelecimento": "123456",
        "NumeroRv": 123456789,
        "NSUAdquirente": 123456789,
        "DataTransacao": "2023-01-01",
        "HoraTransacao": "10:00:00",
        "NumeroCartao": "123456******1234",
        "ValorTransacao": 15780,
        "ValorSaque": 0,
        "ValorTaxaEmbarque": 0,
        "NumeroTotalParcelas": 12,
        "NumeroParcela": 3,
        "ValorParcela": 1315,
        "DataPagamento": "2023-01-01",
        "CodigoAutorizacao": "00000123456",
        "FormaCaptura": "INT",
        "StatusTransacao": "C",
        "CodigoEstabelecimentoComercialCentralizadorPagamentos": "123456",
        "CodigoTerminal": "DA0123456",
        "Moeda": "BRL",
        "OrigemEmissorCartao": "N",
        "SinalTransacao": "+",
        "CarteiraDigital": "",
        "ValorComissaoVenda": 30.50,
        "IdentificadorTipoProximoConteudo": "02"
    },
    {
        "TipoRegistro": 5,
        "CodigoEstabelecimento": "123456",
        "DataOperacao": "2023-01-01",
        "DataCredito": "2023-01-01",
        "NumeroOperacao": "00000000000000000000",
        "TipoOperacao": "Pagamento (pagamentos não negociados)",
        "ValorBrutoTotalOperacao": 1000.50,
        "ValorBrutoOperacao": 1000.50,
        "ValorCustoOperacao": 0,
        "ValorLiquidoOperacao": 1000.50,
        "TaxaOperacaoMes": "00000000000",
        "TipoContaEstabelecimento": "Conta Corrente",
        "Banco": 123,
        "Agencia": 123,
        "ContaCorrente": "00000000000000056370",
        "CanalOperacao": null,
        "TipoMovimento": "L",
        "TipoInstituicaoParticipante": "Instituição Financeira",
        "IDInstituicaoParticipante": 0,
        "TipoDocInstituicaoParticipante": "CNPJ",
        "DocInstituicaoParticipante": 1234567891011,
        "TipoContaInstituicaoParticipante": "Conta Corrente",
        "BancoInstituicaoParticipante": 123,
        "AgenciaInstituicaoParticipante": 123,
        "ContaInstituicaoParticipante": "00000000000000012345",
        "CodigoEstabelecimentoComercialCentralizadorPagamentos": "00000001234567"
    },
    {
        "TipoRegistro": 9,
        "QuantidadeRegistros": 5
    }
]
```
  

## Licença

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
  

<a href="https://www.buymeacoffee.com/mastria" target="_blank"><img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: 41px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;" ></a>
