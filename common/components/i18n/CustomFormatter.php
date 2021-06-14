<?php

namespace common\components\i18n;

use common\exceptions\FeedbackException;
use NumberFormatter;
use yii\base\InvalidArgumentException;
use yii\i18n\Formatter;

class CustomFormatter extends Formatter
{
    const MASK_CNPJ = '%s%s.%s%s%s.%s%s%s/%s%s%s%s-%s%s';
    const MASK_CPF = '%s%s%s.%s%s%s.%s%s%s-%s%s';
    const MASK_CEP = '%s%s%s%s%s-%s%s%s';

    const MASK_PHONE_11 = '(%s%s) %s%s%s%s%s-%s%s%s%s';
    const MASK_PHONE_10 = '(%s%s) %s%s%s%s-%s%s%s%s';

    public $defaultTimeZone = 'America/Sao_Paulo';

    public $numberFormatterSymbols = [
        NumberFormatter::CURRENCY_SYMBOL => 'R$ '
    ];

    /**
     * Formatar documentos (CNPJ e CPF).
     *
     * @param $value
     * @return string
     * @throws FeedbackException
     */
    public function asDocument($value)
    {
        if (empty($value)) {
            return $this->nullDisplay;
        }

        $count = strlen($value);
        if ($count !== 11 && $count !== 14) {
            throw new InvalidArgumentException("O valor <strong>{$value}</strong> não é um documento válido.");
        }

        $mask = $count === 11 ? self::MASK_CPF : self::MASK_CNPJ;
        try {
            return vsprintf($mask, str_split($value));
        } catch (\Exception $e) {

            throw new FeedbackException("Não foi possível formatar o valor <strong>{$value}</strong> com a máscara <strong>{$mask}</strong>.");
        }
    }

    /**
     * Formatar CEP.
     *
     * @param $value
     * @return string
     * @throws FeedbackException
     */
    public function asZipCode($value)
    {
        if (empty($value)) {
            return $this->nullDisplay;
        }

        if (strlen($value) !== 8) {
            throw new InvalidArgumentException("O valor <strong>{$value}</strong> não é um CEP válido.");
        }

        $mask = self::MASK_CEP;
        try {
            return vsprintf($mask, str_split($value));
        } catch (\Exception $e) {
            throw new FeedbackException("Não foi possível formatar o valor <strong>{$value}</strong> com a máscara <strong>{$mask}</strong>.");
        }
    }

    /**
     * @param $value
     * @return string
     * @throws FeedbackException
     */
    public function asPhone($value)
    {
        if (empty($value)) {
            return $this->nullDisplay;
        }

        $strlen = (int) strlen($value);
        if ($strlen !== 10 && $strlen !== 11) {
            throw new InvalidArgumentException("O valor <strong>{$value}</strong> não é um Telefone válido.");
        }

        $mask = $strlen === 10 ? self::MASK_PHONE_10 : self::MASK_PHONE_11;
        try {
            return vsprintf($mask, str_split($value));
        } catch (\Exception $e) {
            throw new FeedbackException("Não foi possível formatar o valor <strong>{$value}</strong> com a máscara <strong>{$mask}</strong>.");
        }
    }

    public static function valueToCardinal($value = 0, $showCurrency = true, $female = false)
    {
        $singular = null;
        $plural = null;

        if ($showCurrency) {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        } else {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

        if ($female) {
            if ($value == 1) {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            } else {
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            }

            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas", "quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }

        $z = 0;

        $value = number_format($value, 2, ".", ".");
        $integer = explode(".", $value);

        for ($i = 0; $i < count($integer); $i++) {
            for ($ii = mb_strlen($integer[$i]); $ii < 3; $ii++) {
                $integer[$i] = "0" . $integer[$i];
            }
        }

        $rt = null;
        $end = count($integer) - ($integer[count($integer) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($integer); $i++) {
            $value = $integer[$i];
            $rc = (($value > 100) && ($value < 200)) ? "cento" : $c[$value[0]];
            $rd = ($value[1] < 2) ? "" : $d[$value[1]];
            $ru = ($value > 0) ? (($value[1] == 1) ? $d10[$value[2]] : $u[$value[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($integer) - 1 - $i;
            $r .= $r ? " " . ($value > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($value == "000")
                $z++;
            elseif ($z > 0)
                $z--;

            if (($t == 1) && ($z > 0) && ($integer[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];

            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $end) && ($integer[0] > 0) && ($z < 1)) ? (($i < $end) ? ", " : " e ") : " ") . $r;
        }

        $rt = mb_substr($rt, 1);

        return ($rt ? trim($rt) : "zero");

    }
}