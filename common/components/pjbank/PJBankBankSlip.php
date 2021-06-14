<?php

namespace common\components\pjbank;

use common\entities\Admin;
use common\entities\Credential;
use common\exceptions\FeedbackException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use yii\base\Model;
use yii\helpers\Json;

/**
 * Class PJBankCredential
 * @package common\components\pjbank
 */
class PJBankBankSlip extends Model
{
    const STATUS_PENDING = 'pendente';
    const STATUS_SENDED = 'enviado';
    const STATUS_REGISTERED = 'confirmado';
    const STATUS_REJECTED = 'rejeitado';
    const STATUS_DROPPED = 'baixado';

    const SCENARIO_REGISTER = 'scenarioRegister';
    const SCENARIO_CANCEL = 'scenarioCancel';
    const SCENARIO_CONSULT = 'scenarioConsult';

    const REGISTER_SANDBOX = 'https://sandbox.pjbank.com.br/recebimentos/{token}/transacoes';
    const REGISTER_PRODUCTION = 'https://api.pjbank.com.br/recebimentos/{token}/transacoes';

    const CANCEL_SANDBOX = 'https://sandbox.pjbank.com.br/recebimentos/{token}/transacoes/{number}';
    const CANCEL_PRODUCTION = 'https://api.pjbank.com.br/recebimentos/{token}/transacoes/{number}';

    const CONSULT_SANDBOX = 'https://sandbox.pjbank.com.br/recebimentos/{token}/transacoes/{number}';
    const CONSULT_PRODUCTION = 'https://api.pjbank.com.br/recebimentos/{token}/transacoes/{number}';

    /** @var integer Utilizado pelo $pedido_numero para identificação do boleto no pjbank. */
    public $number;

    /** @var int Valor do boleto */
    public $value = 0;

    /** @var null Data de vencimento */
    public $dueDate = null;

    /** @var int Valor da multa, quando fixada será um valor em reais. */
    public $fineValue = 0;
    /** @var int Indica se a multa é um valor em reais. */
    public $fineFixed = 0;

    /** @var int Valor de juros, quando fixado será um valor em reais. */
    public $interestValue = 0;
    /** @var int Indica se o juros é um valor em reais. */
    public $interestFixed = 0;

    /** @var int Valor de desconto por antecipação. */
    public $discountValue = 0;
    /** @var int Indica a quantidade de dias para o desconto por antecipação. */
    public $discountDays = 0;

    /*
     * Dados do pagador
     */
    public $payerName;
    public $payerDocument;
    public $payerStreet;
    public $payerNumber;
    public $payerComplement;
    public $payerDistrict;
    public $payerCity;
    public $payerState;
    public $payerZipCode;

    /** @var string Instruções adicionais. */
    public $instructions;

    /** @var string Token da credencial */
    public $credentialToken;

    /** @var Credential|null */
    private $_credentialModel;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'value', 'dueDate',
                    'payerName', 'payerDocument', 'payerStreet', 'payerNumber', 'payerDistrict', 'payerState', 'payerZipCode',
                    'number', 'credentialToken'
                ],
                'required', 'on' => self::SCENARIO_REGISTER],
            [['number', 'credentialToken'], 'required', 'on' => self::SCENARIO_CANCEL],
            [['number', 'credentialToken'], 'required', 'on' => self::SCENARIO_CONSULT],

            [['fineFixed', 'interestFixed', 'discountDays'], 'default', 'value' => 0],
            [['fineValue', 'interestValue', 'discountValue'], 'number'],
            [['dueDate'], 'date', 'format' => 'php:Y-m-d'],

            [['fineFixed', 'interestFixed'], 'boolean', 'trueValue' => 1, 'falseValue' => 0, 'strict' => false],

            [['payerDocument', 'payerZipCode'], 'filter', 'filter' => function ($value) {
                return (string) preg_replace( '/[^0-9]/', '', $value);
            }],

            [['payerName'], 'string', 'min' => 3, 'max' => 64],
            [['payerDocument'], 'string', 'min' => 11, 'max' => 14],
            [['payerStreet'], 'string', 'min' => 3, 'max' => 128],
            [['payerNumber'], 'string', 'min' => 1, 'max' => 10],
            [['payerComplement'], 'string', 'max' => 80],
            [['payerDistrict'], 'string', 'min' => 3, 'max' => 64],
            [['payerCity'], 'string', 'min' => 3, 'max' => 80],
            [['payerState'], 'string', 'min' => 2, 'max' => 2],
            [['payerZipCode'], 'string', 'min' => 8, 'max' => 10],
            [['instructions'], 'string', 'max' => 255],

            [['credentialToken'], 'exist', 'skipOnError' => true, 'targetClass' => Credential::className(), 'targetAttribute' => ['credentialToken' => 'token']],
            [['credentialToken'], 'validateCredential'],
        ];
    }

    /**
     * Validar a conta.
     *
     * @return bool
     */
    public function validateCredential()
    {
        $this->_credentialModel = Credential::find()->where(['token' => $this->credentialToken, 'isActive' => Credential::ACTIVE_TRUE])->one();

        if (empty($this->_credentialModel)) {
            $this->addError('_credentialModel', 'Não foi possível encontrar a credencial fornecida.');
            return false;
        }

        return true;
    }

    /**
     * @return array|bool
     * @throws FeedbackException
     * @throws \GuzzleHttp\GuzzleException
     */
    public function create()
    {
        $this->scenario = self::SCENARIO_REGISTER;
        if ($this->validate() === false) {
            throw new FeedbackException('Não foi possível validar as informações para geração do boleto.');
        }

        try {
            $requestUrl = YII_ENV_PROD ? self::REGISTER_PRODUCTION : self::REGISTER_SANDBOX;
            $requestUrl = str_replace('{token}', $this->_credentialModel->credential, $requestUrl);

            $dueDate = \DateTime::createFromFormat('Y-m-d', $this->dueDate);
            $customMessage = $this->_credentialModel->admin->customBankSlipText ?? '';

            $requestData = Json::encode([
                'pedido_numero' => $this->number,
                'valor' => $this->value,
                'vencimento' => $dueDate->format('m/d/Y'),
                'juros' => $this->interestValue,
                'juros_fixo' => $this->interestFixed,
                'multa' => $this->fineValue,
                'multa_fixo' => $this->fineFixed,
                'desconto' => $this->discountValue,
                'diasdesconto1' => $this->discountDays,
                'nome_cliente' => $this->payerName,
                'cpf_cliente' => $this->payerDocument,
                'endereco_cliente' => $this->payerStreet,
                'numero_cliente' => $this->payerNumber,
                'complemento_cliente' => $this->payerComplement,
                'bairro_cliente' => $this->payerDistrict,
                'cidade_cliente' => $this->payerCity,
                'estado_cliente' => $this->payerState,
                'cep_cliente' => $this->payerZipCode,
                'texto' => $customMessage,
                'instrucao_adicional' => $this->instructions,
                'especie_documento' => 'DM',
            ]);

            $client = new Client();
            $response = $client->request('POST', $requestUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => $requestData,
            ]);

            $responseBody = Json::decode($response->getBody()->getContents());
            if ($response->getStatusCode() !== 201) {
                $pjMessage = isset($responseBody['msg']) ? $responseBody['msg'] : '';
                throw new FeedbackException("Não foi possível criar o boleto. <br/> {$pjMessage}");
            }

            return [
                'key' => $responseBody['id_unico'],
                'link' => $responseBody['linkBoleto'],
                'line' => $responseBody['linhaDigitavel'],
            ];
        } catch (FeedbackException $e) {
            throw $e;
        } catch (RequestException $e) {
            $errorBody = Json::decode($e->getResponse()->getBody()->getContents());
            if (isset($errorBody['msg'])) {
                throw new FeedbackException("Não foi possível completar a requisição. <br/> {$errorBody['msg']}");
            } else {
                throw new FeedbackException('Não foi possível completar a requisição.');
            }
        } catch (\Exception $e) {
            throw new FeedbackException('Não foi possível completar a requisição.');
        }
    }

    /**
     * @return array|bool
     * @throws FeedbackException
     * @throws \GuzzleHttp\GuzzleException
     */
    public function cancel()
    {
        $this->scenario = self::SCENARIO_CANCEL;
        if ($this->validate() === false) {
            throw new FeedbackException('Não foi possível validar as informações para cancelamento.');
        }

        try {
            $requestUrl = YII_ENV_PROD ? self::CANCEL_PRODUCTION : self::CANCEL_SANDBOX;
            $requestUrl = str_replace('{token}', $this->_credentialModel->credential, $requestUrl);
            $requestUrl = str_replace('{number}', $this->number, $requestUrl);

            $client = new Client();
            $response = $client->request('DELETE', $requestUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'X-CHAVE' => $this->_credentialModel->key,
                ],
            ]);

            $responseBody = Json::decode($response->getBody()->getContents());
            if ($response->getStatusCode() !== 200) {
                $pjMessage = isset($responseBody['msg']) ? $responseBody['msg'] : '';
                throw new FeedbackException("Não foi possível cancelar o boleto. <br/> {$pjMessage}");
            }

            return true;
        } catch (FeedbackException $e) {
            throw $e;
        } catch (RequestException $e) {
            $errorBody = Json::decode($e->getResponse()->getBody()->getContents());
            if (isset($errorBody['msg'])) {
                throw new FeedbackException("Não foi possível completar a requisição. <br/> {$errorBody['msg']}");
            } else {
                throw new FeedbackException('Não foi possível completar a requisição.');
            }
        } catch (\Exception $e) {
            throw new FeedbackException('Não foi possível completar a requisição.');
        }
    }

    /**
     * @return array|bool
     * @throws FeedbackException
     * @throws \GuzzleHttp\GuzzleException
     */
    public function consult()
    {
        $this->scenario = self::SCENARIO_CONSULT;
        if ($this->validate() === false) {
            throw new FeedbackException('Não foi possível validar as informações para consultar o boleto.');
        }

        try {
            $requestUrl = YII_ENV_PROD ? self::CONSULT_PRODUCTION : self::CONSULT_SANDBOX;
            $requestUrl = str_replace('{token}', $this->_credentialModel->credential, $requestUrl);
            $requestUrl = str_replace('{number}', $this->number, $requestUrl);

            $client = new Client();
            $response = $client->request('GET', $requestUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'X-CHAVE' => $this->_credentialModel->key,
                ],
            ]);

            $responseBody = Json::decode($response->getBody()->getContents());
            if ($response->getStatusCode() !== 200) {
                $pjMessage = isset($responseBody['msg']) ? $responseBody['msg'] : '';
                throw new FeedbackException("Não foi possível solicitar as informações do boleto. <br/> {$pjMessage}");
            }

            $responseBody = is_array($responseBody) ? array_shift($responseBody) : $responseBody;

//            return [
//                'status' => PJBankBankSlip::STATUS_REGISTERED, //$responseBody['registro_sistema_bancario'],
//                'feeValue' => 0.75, //$responseBody['valor_tarifa'],
//                'paidValue' => 145.75, //$responseBody['valor_pago'],
//                'creditedValue' => 145.00, //$responseBody['valor_liquido'],
//                'paidAt' => '04/29/2020', //$responseBody['data_pagamento'],
//                'creditedAt' => '04/30/2020', //$responseBody['data_credito'],
//            ];

            return [
                'status' => $responseBody['registro_sistema_bancario'],
                'feeValue' => $responseBody['valor_tarifa'],
                'paidValue' => $responseBody['valor_pago'],
                'creditedValue' => $responseBody['valor_liquido'],
                'paidAt' => $responseBody['data_pagamento'],
                'creditedAt' => $responseBody['data_credito'],
            ];
        } catch (FeedbackException $e) {
            throw $e;
        } catch (RequestException $e) {
            $errorBody = Json::decode($e->getResponse()->getBody()->getContents());
            if (isset($errorBody['msg'])) {
                throw new FeedbackException("Não foi possível completar a requisição. <br/> {$errorBody['msg']}");
            } else {
                throw new FeedbackException('Não foi possível completar a requisição.');
            }
        } catch (\Exception $e) {
            throw new FeedbackException($e->getMessage());
        }
    }
}
