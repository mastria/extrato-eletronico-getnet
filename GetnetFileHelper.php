<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Class GetnetFileHelper para ler o arquivo do EXTRATO ELETRÔNICO gerado pela Getnet e
 * separar as informações de cada linha em um array com os valores em suas respectivas chaves.
 *
 * @package App\Helpers
 * @author  Guilherme Mastria <guilherme@mastria.dev.br>
 * @version Versão do Manual Getnet V.10.0 | V1.2023
 * @see https://site.getnet.com.br/downloads/
 */
class GetnetFileHelper
{
    public static $data = [];

    /**
     * Conversor - Indicadores de tipo de pagamento
     *
     * @param String $tipo
     * @return String
     */
    public static function indicadorTipoPagamento(String $tipo): String
    {
        $tipos = [
            'PF' => 'Previsão de Pagamento Futuro',
            'PG' => 'Pagamento Normal',
            'AC' => 'Antecipação de Crédito',
            'RA' => 'Rejeição de Antecipação',
            'PR' => 'Pagamento da Antecipação Rejeitada',
            'PD' => 'Pagamento Pendente',
            'CI' => 'Cobrança Interna',
            'CS' => 'Cessão de crédito',
        ];

        return (isset($tipos[$tipo])) ? $tipos[$tipo] : 'Indefinido';
    }

    /**
     * Conversor - Indicadores de tipo de conta para pagamento ou Conta Corrente
     *
     * @param String $tipo
     * @return String
     */
    public static function tipoContaPagamento(String $tipo): String
    {
        $tipos = [
            'CC' => 'Conta Corrente',
            'PP' => 'Conta Poupança',
            'PG' => 'Conta Pagamento',
            'CD' => 'Conta Depósito',
            'CS' => 'Conta Super',
        ];

        return (isset($tipos[$tipo])) ? $tipos[$tipo] : 'Indefinido';
    }

    /**
     * Conversor - Indicadores de tipo de operação
     *
     * @param String $tipo
     * @return String
     */
    public static function tipoOperacao(String $tipo): String
    {
        $tipos = [
            'CS' => 'Cessão',
            'GV' => 'Gravame',
            'CF' => 'Cessão fumaça',
            'PG' => 'Pagamento (pagamentos não negociados)',
        ];

        return (isset($tipos[$tipo])) ? $tipos[$tipo] : 'Indefinido';
    }

    /**
     * Conversor - Data
     *
     * @param String $data no formato ddmmaaaa
     * @return String|null no formato aaaa-mm-dd ou null caso a data seja inválida
     * @example formataData('01012023') retorna '2023-01-01'
     */
    public static function formataData(String $data): String|null
    {
        if (trim($data) == '' || intval($data) == 0 || strlen($data) != 8) {
            return null;
        }

        return Carbon::createFromFormat('dmY', $data)->format('Y-m-d');
    }

    /**
     * Conversor - Hora
     * 
     * @param String $hora no formato hhmmss
     * @return String|null no formato hh:mm:ss ou null caso a hora seja inválida
     * @example formataHora('120000') retorna '12:00:00'
     */
    public static function formataHora(String $hora): String|null
    {
        if (trim($hora) == '' || intval($hora) == 0 || strlen($hora) != 6) {
            return null;
        }

        return Carbon::createFromFormat('His', $hora)->format('H:i:s');
    }

    /**
     * REGISTRO TIPO 0 - Header
     * 
     * Apresenta informação referente ao conteúdo do arquivo
     *
     * @param String $string
     * @return array
     */
    public static function header(String $string): array
    {
        return [
            'TipoRegistro' => intval(substr($string, 0, 1)),
            'DataCriacaoArquivo' => self::formataData(substr($string, 1, 8)),
            'HoraCriacaoArquivo' => self::formataHora(substr($string, 9, 6)),
            'DataReferenciaMovimento' => self::formataData(substr($string, 15, 8)),
            'VersaoArquivo' => substr($string, 23, 8),
            'CodigoEstabelecimento' => trim(substr($string, 31, 15)),
            'CNPJAdquirente' => intval(substr($string, 46, 14)),
            'NomeAdquirente' => trim(substr($string, 60, 20)),
            'Sequencia' => intval(substr($string, 80, 9)),
            'CodigoAdquirente' => substr($string, 89, 2),
            'VersaoLayout' => trim(substr($string, 91, 25))
        ];
    }

    /**
     * REGISTRO TIPO 1 - Resumo de Vendas
     * 
     * Este registro demonstra de forma resumida os lançamentos que são detalhados
     * no TIPO 2, 3, 4 e 5 incluindo o status de pagamento
     *
     * @param String $string
     * @return array
     */
    public static function resumoVendas(String $string): array
    {
        return [
            'TipoRegistro' => intval(substr($string, 0, 1)),
            'CodigoEstabelecimento' => trim(substr($string, 1, 15)),
            'CodigoProduto' => substr($string, 16, 2),
            'FormaCaptura' => substr($string, 18, 3),
            'NumeroRV' => intval(substr($string, 21, 9)),
            'DataRV' => self::formataData(substr($string, 30, 8)),
            'DataPagamentoRV' => self::formataData(substr($string, 38, 8)),
            'Banco' => intval(substr($string, 46, 3)),
            'Agencia' => intval(substr($string, 49, 6)),
            'ContaCorrente' => substr($string, 55, 11),
            'NumeroCVAceitos' => intval(substr($string, 66, 9)),
            'NumeroCVRejeitados' => intval(substr($string, 75, 9)),
            'ValorBruto' => floatval(substr($string, 84, 12)) / 100,
            'ValorLiquido' => floatval(substr($string, 96, 12)) / 100,
            'ValorTarifa' => floatval(substr($string, 108, 12)) / 100,
            'ValorTaxaDesconto' => floatval(substr($string, 120, 12)) / 100,
            'ValorRejeitado' => floatval(substr($string, 132, 12)) / 100,
            'ValorCredito' => floatval(substr($string, 144, 12)) / 100,
            'ValorEncargos' => floatval(substr($string, 156, 12)) / 100,
            'IndicadorTipoPagamento' => self::indicadorTipoPagamento(substr($string, 168, 2)),
            'NumeroParcelaRV' => intval(substr($string, 170, 2)),
            'QuantidadesParcelasRV' => intval(substr($string, 172, 2)),
            'CodigoEstabelecimentoComercialCentralizadorPagamentos' => trim(substr($string, 174, 15)),
            'NumeroOperacaoAntecipacao' => intval(substr($string, 189, 15)),
            'DataVencimentoOriginalRVAntecipado' => self::formataData(substr($string, 204, 8)),
            'CustoOperacao' => floatval(substr($string, 212, 12)) / 100,
            'ValorLiquidoRVAntecipado' => floatval(substr($string, 224, 12)) / 100,
            'NumeroControleOperacaoCobranca' => intval(substr($string, 236, 18)),
            'ValorLiquidoCobranca' => floatval(substr($string, 254, 12)) / 100,
            'IdCompensacao' => intval(substr($string, 266, 15)),
            'Moeda' => (substr($string, 281, 3) == '986') ? 'BRL' : 'USD',
            'IdentificadorBaixaCobrancaServicoExterna' => (trim(substr($string, 284, 1)) == '') ? null : substr($string, 284, 1),
            'SinalTransacao' => substr($string, 285, 1),
            'Metadado1' => trim(substr($string, 286, 2)),
            'ContaPagamento' => intval(substr($string, 288, 20)),
        ];
    }

    /**
     * REGISTRO TIPO 2 - Comprovante de Vendas
     * 
     * Contém as informações dos Comprovantes de Venda das transações, é usado para
     * detalhar as vendas do registro do TIPO 1
     *
     * @param String $string
     * @return array
     */
    public static function comprovanteVendas(String $string): array
    {
        return [
            'TipoRegistro' => intval(substr($string, 0, 1)),
            'CodigoEstabelecimento' => trim(substr($string, 1, 15)),
            'NumeroRv' => intval(substr($string, 16, 9)),
            'NSUAdquirente' => intval(substr($string, 25, 12)),
            'DataTransacao' => self::formataData(substr($string, 37, 8)),
            'HoraTransacao' => self::formataHora(substr($string, 45, 6)),
            'NumeroCartao' => trim(substr($string, 51, 19)),
            'ValorTransacao' => floatval(substr($string, 70, 12)) / 100,
            'ValorSaque' => floatval(substr($string, 82, 12)) / 100,
            'ValorTaxaEmbarque' => floatval(substr($string, 94, 12)) / 100,
            'NumeroTotalParcelas' => intval(substr($string, 106, 2)),
            'NumeroParcela' => intval(substr($string, 108, 2)),
            'ValorParcela' => floatval(substr($string, 110, 12)) / 100,
            'DataPagamento' => self::formataData(substr($string, 122, 8)),
            'CodigoAutorizacao' => substr($string, 130, 10),
            'FormaCaptura' => substr($string, 140, 3),
            'StatusTransacao' => substr($string, 143, 1),
            'CodigoEstabelecimentoComercialCentralizadorPagamentos' => trim(substr($string, 144, 15)),
            'CodigoTerminal' => substr($string, 159, 8),
            'Moeda' => (substr($string, 167, 3) == '986') ? 'BRL' : 'USD',
            'OrigemEmissorCartao' => substr($string, 170, 1),
            'SinalTransacao' => substr($string, 171, 1),
            'CarteiraDigital' => trim(substr($string, 172, 3)),
            'ValorComissaoVenda' => floatval(substr($string, 175, 12)) / 100,
            'IdentificadorTipoProximoConteudo' => substr($string, 187, 2),
        ];
    }

    /**
     * REGISTRO TIPO 3 - Ajustes Financeiros
     * 
     * Contém as informações dos ajustes financeiros, sendo a crédito ou a débito,
     * chargebacks, cancelamentos e serviços
     *
     * @param String $string
     * @return array
     */
    public function ajustesFinanceiros(String $string): array
    {
        return [
            'TipoRegistro' => intval(substr($string, 0, 1)),
            'CodigoEstabelecimento' => substr($string, 1, 15),
            'NumeroRVAjustado' => substr($string, 16, 9),
            'DataRV' => self::formataData(substr($string, 25, 8)),
            'DataPagamentoRV' => self::formataData(substr($string, 33, 8)),
            'IdentificadorAjuste' => substr($string, 41, 20),
            'Brancos' => substr($string, 61, 1),
            'SinalTransacao' => substr($string, 62, 1),
            'ValorAjuste' => floatval(substr($string, 63, 12)) / 100,
            'MotivoAjuste' => substr($string, 75, 2),
            'DataCarta' => self::formataData(substr($string, 77, 8)),
            'NumeroCartao' => substr($string, 85, 19),
            'NumeroRVOriginal' => intval(substr($string, 104, 9)),
            'NumeroCV' => intval(substr($string, 113, 12)),
            'DataTransacaoOriginal' => self::formataData(substr($string, 125, 8)),
            'IndicadorTipoPagamento' => self::indicadorTipoPagamento(substr($string, 133, 2)),
            'NumeroTerminal' => substr($string, 135, 8),
            'DataPagamentoOriginal' => self::formataData(substr($string, 143, 8)),
            'Moeda' => (substr($string, 151, 3) == '986') ? 'BRL' : 'USD',
            'ValorComissaoVenda' => floatval(substr($string, 154, 12)) / 100,
            'IdentificadorTipoProximoConteudo' => substr($string, 166, 2),
            'ConteudoDinamico' => trim(substr($string, 168, 118)),
        ];
    }

    /**
     * REGISTRO TIPO 4 - Antecipação de Recebíveis
     * 
     * Contém as informações das Operações de Antecipação, utilizado para apresentar
     * as operações de antecipação de recebíveis de forma consolidada realizada com
     * a adquirente Getnet
     *
     * @param String $string
     * @return array
     */
    public static function antecipacaoRecebiveis(String $string): array
    {
        return [
            'TipoRegistro' => intval(substr($string, 0, 1)),
            'CodigoEstabelecimento' => substr($string, 1, 15),
            'DataOperacao' => self::formataData(substr($string, 16, 8)),
            'DataCredito' => self::formataData(substr($string, 24, 8)),
            'NumeroOperacao' => intval(substr($string, 32, 15)),
            'ValorBrutoAntecipacao' => floatval(substr($string, 47, 12)) / 100,
            'ValorTaxaAntecipacao' => floatval(substr($string, 59, 12)) / 100,
            'ValorLiquidoAntecipacao' => floatval(substr($string, 71, 12)) / 100,
            'TaxaOperacaoMes' => substr($string, 83, 11),
            'CodigoEstabelecimentoComercialCentralizadorPagamentos' => trim(substr($string, 94, 15)),
            'Banco' => intval(substr($string, 109, 3)),
            'Agencia' => intval(substr($string, 112, 6)),
            'ContaCorrente' => trim(substr($string, 118, 11)),
            'CanalAntecipacao' => substr($string, 129, 3),
            'IndicadorTipoPagamento' => substr($string, 132, 2),
            'Metadado1' => self::tipoContaPagamento(substr($string, 134, 2)),
            'ContaPagamento' => intval(substr($string, 136, 20)),
        ];
    }

    /**
     * REGISTRO TIPO 5 - Negociações de Cessão e Gravame
     * 
     * Contém as informações das Operações de Cessão e/ou Gravame, utilizado para
     * apresentar as operações de gravame ou cessão realizadas pelo Mercado de
     * transações da Getnet
     *
     * @param String $string
     * @return array
     */
    public static function negociacoesCessao(String $string): array
    {
        return [
            'TipoRegistro' => intval(substr($string, 0, 1)),
            'CodigoEstabelecimento' => trim(substr($string, 1, 15)),
            'DataOperacao' => self::formataData(substr($string, 16, 8)),
            'DataCredito' => self::formataData(substr($string, 24, 8)),
            'NumeroOperacao' => trim(substr($string, 32, 20)),
            'TipoOperacao' => self::tipoOperacao(substr($string, 52, 2)),
            'ValorBrutoTotalOperacao' => floatval(substr($string, 54, 12)) / 100,
            'ValorBrutoOperacao' => floatval(substr($string, 66, 12)) / 100,
            'ValorCustoOperacao' => floatval(substr($string, 78, 12)) / 100,
            'ValorLiquidoOperacao' => floatval(substr($string, 90, 12)) / 100,
            'TaxaOperacaoMes' => substr($string, 102, 11),
            'TipoContaEstabelecimento' => self::tipoContaPagamento(substr($string, 113, 2)),
            'Banco' => intval(substr($string, 115, 3)),
            'Agencia' => intval(substr($string, 118, 6)),
            'ContaCorrente' => trim(substr($string, 124, 20)),
            'CanalOperacao' => (trim(substr($string, 144, 3)) == '') ? null : substr($string, 144, 3),
            'TipoMovimento' => substr($string, 147, 1),
            'TipoInstituicaoParticipante' => (trim(substr($string, 148, 3)) == 'IF') ? 'Instituição Financeira' : 'Instituição Não Financeira',
            'IDInstituicaoParticipante' => intval(substr($string, 151, 18)),
            'TipoDocInstituicaoParticipante' => (substr($string, 169, 1) == '1') ? 'CNPJ' : 'CPF',
            'DocInstituicaoParticipante' => intval(substr($string, 170, 14)),
            'TipoContaInstituicaoParticipante' => self::tipoContaPagamento(substr($string, 184, 2)),
            'BancoInstituicaoParticipante' => intval(substr($string, 186, 3)),
            'AgenciaInstituicaoParticipante' => intval(substr($string, 189, 6)),
            'ContaInstituicaoParticipante' => trim(substr($string, 195, 20)),
            'CodigoEstabelecimentoComercialCentralizadorPagamentos' => trim(substr($string, 215, 15)),
        ];
    }

    /**
     * REGISTRO TIPO 6 - Unidades Recebíveis negociadas em Cessão
     * 
     * Contém as informações das Unidades de Recebíveis envolvidas na negociação de Cessão
     *
     * @param String $string
     * @return array
     */
    public static function unidadesRecebiveis(String $string): array
    {
        return [
            'TipoRegistro' => intval(substr($string, 0, 1)),
        ];
    }

    /**
     * REGISTRO TIPO 9 - Trailer
     * 
     * Demonstra o fim do arquivo, totalizando a quantidade de registros
     *
     * @param String $string
     * @return array
     */
    public static function trailer(String $string): array
    {
        return [
            'TipoRegistro' => intval(substr($string, 0, 1)),
            'QuantidadeRegistros' => intval(substr($string, 1, 9))
        ];
    }

    /**
     * Ler cada linha do arquivo e chamar o método correspondente de cada tipo de registro
     *
     * @param String $arquivo nome do arquivo a ser lido
     * @return array
     */
    public static function lerArquivo($arquivo): array
    {
        $linhas = explode("\n", Storage::disk('s3')->get("getnet/$arquivo"));

        self::$data = [];

        foreach ($linhas as $linha) {
            if (!isset($linha[0])) {
                continue;
            }

            switch ($linha[0]) {
                case '0':
                    array_push(self::$data, self::header($linha));
                    break;
                case '1':
                    array_push(self::$data, self::resumoVendas($linha));
                    break;
                case '2':
                    array_push(self::$data, self::comprovanteVendas($linha));
                    break;
                case '3':
                    array_push(self::$data, self::ajustesFinanceiros($linha));
                    break;
                case '4':
                    array_push(self::$data, self::antecipacaoRecebiveis($linha));
                    break;
                case '5':
                    array_push(self::$data, self::negociacoesCessao($linha));
                    break;
                case '6':
                    array_push(self::$data, self::unidadesRecebiveis($linha));
                    break;
                case '9':
                    array_push(self::$data, self::trailer($linha));
                default:
                    break;
            }
        }

        return self::$data;
    }
}
