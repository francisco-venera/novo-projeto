<?php

namespace common\components\pjbank;

use common\entities\Admin;
use common\exceptions\FeedbackException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use yii\base\Model;
use yii\helpers\Json;

/**
 * Class PJBankCredential
 * @package common\components\pjbank
 */
class PJBankCredential extends Model
{
    const RECEBIMENTOS_SANDBOX = 'https://sandbox.pjbank.com.br/recebimentos';
    const RECEBIMENTOS_PRODUCTION = 'https://api.pjbank.com.br/recebimentos';

    /** @var string Razão social da empresa */
    public $name;

    /** @var string Conta (XXXXX) para transferência, limite 5 caracteres. */
    public $transferAccount;
    public $transferAccountDigit;

    /** @var integer Agência com dígito (XXXX) para transferência, limite 4 caracteres. */
    public $transferAgency;
    public $transferAgencyDigit;

    /** @var integer Banco (XXX) para transferência. */
    public $transferBank;

    /** @var string|integer Documento (somente números) da empresa. */
    public $document;

    /** @var integer Código de área do telefone da empresa. */
    public $areaCode;
    /** @var string|integer Telefone da empresa. */
    public $phone;

    /** @var string E-mail da empresa. */
    public $email;

    /**
     * @var string Código do parceiro (apcontrole) - Identifica que somos parceiros do pjbank.
     */
    public $agency = "0677";

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'transferAccount', 'transferAgency', 'transferBank', 'document', 'areaCode', 'phone', 'email'], 'required'],
            [['transferAccountDigit', 'transferAgencyDigit'], 'default', 'value' => null],
            [['phone', 'document', 'areaCode', 'transferBank', 'transferAccount', 'transferAccountDigit', 'transferAgency', 'transferAgencyDigit'], 'filter', 'filter' => function ($value) {
                return (string) preg_replace( '/[^0-9]/', '', $value);
            }],
            [['name', 'email'], 'string', 'min' => 3, 'max' => 80],
            [['transferAccount'], 'validateAccount'],
            [['transferAgency'], 'validateAgency'],
            [['transferBank'], 'string', 'max' => 3],
            [['document'], 'string', 'min' => 11, 'max' => 14],
            [['areaCode'], 'string', 'min' => 2, 'max' => 2],
            [['phone'], 'string', 'min' => 8, 'max' => 10],
            [['email'], 'email'],
        ];
    }

    /**
     * Validar a conta.
     *
     * @return bool
     */
    public function validateAccount()
    {
        $account = strlen($this->transferAccount);
        $accountDigit = strlen($this->transferAccountDigit);

        if ($accountDigit > 2) {
            $this->addError('transferAccountDigit', 'O dígito da conta não pode ultrapassar 2 caracteres.');
            return false;
        }

        if ($account > 7) {
            $this->addError('transferAccount', 'A conta não pode ultrapassar 7 caracteres.');
            return false;
        }

        return true;
    }

    /**
     * Validar agência.
     *
     * @return bool
     */
    public function validateAgency()
    {
        $agency = strlen($this->transferAgency);
        $agencyDigit = strlen($this->transferAgencyDigit);

        if ($agencyDigit > 1) {
            $this->addError('transferAccountDigit', 'O dígito da agência não pode ultrapassar 1 caracter.');
            return false;
        }

        if ($agencyDigit === 1 && $agency > 4) {
            $this->addError('transferAccount', 'A agência não pode ultrapassar 4 caracteres.');
            return false;
        }

        if (empty($agencyDigit) && $agency > 5) {
            $this->addError('transferAccount', 'A agência não pode ultrapassar 5 caracteres.');
            return false;
        }

        return true;
    }

    /**
     * @throws FeedbackException
     * @throws \Exception
     * @throws \GuzzleHttp\GuzzleException
     */
    public function create()
    {
        if ($this->validate() === false) {
            return false;
        }

        $bank = str_pad($this->transferBank, 3, 0, STR_PAD_LEFT);

        try {
            $bankSlipFeeValue = Admin::findOne(['document' => $this->document])->bankSlipFeeValue ?? Admin::DEFAULT_BANK_SLIP_FEE_VALUE;

            $requestUrl = YII_ENV_PROD ? self::RECEBIMENTOS_PRODUCTION : self::RECEBIMENTOS_SANDBOX;
            $requestData = Json::encode([
                'nome_empresa' => $this->name,
                'conta_repasse' => "{$this->transferAccount}-{$this->transferAccountDigit}",
                'agencia_repasse' => $this->transferAgencyDigit ? "{$this->transferAgency}{$this->transferAgencyDigit}" : $this->transferAgency,
                'banco_repasse' => $bank,
                'cnpj' => $this->document,
                'ddd' => $this->areaCode,
                'telefone' => $this->phone,
                'email' => $this->email,
                'agencia' => YII_ENV_PROD ? $this->agency : '',
                'valor_taxa_boleto' => $bankSlipFeeValue
            ]);

            $client = new Client();
            $response = $client->request('POST', $requestUrl, [
                'auth' => ['user', 'pass'],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => $requestData,
            ]);

            $responseBody = Json::decode($response->getBody()->getContents());
            if ($response->getStatusCode() !== 201) {
                $pjMessage = isset($responseBody['msg']) ? $responseBody['msg'] : '';
                throw new FeedbackException("Não foi possível criar a credencial. <br/> {$pjMessage}");
            }

            return [
                'credential' => $responseBody['credencial'],
                'key' => $responseBody['chave'],
                'virtualAccount' => $responseBody['conta_virtual'],
                'virtualAgency' => $responseBody['agencia_virtual'],
                'bankSlipFeeValue' => $bankSlipFeeValue
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
}
