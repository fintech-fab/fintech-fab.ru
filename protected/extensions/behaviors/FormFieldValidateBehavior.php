<?php
/**
 * Created by JetBrains PhpStorm.
 * User: m.novikov && e.zamorskaya
 * Date: 15.04.13
 * Time: 16:25
 * To change this template use File | Settings | File Templates.
 *
 * @property CFormModel|ClientCreateForm $owner
 */

class FormFieldValidateBehavior extends CBehavior
{
    /**
     * проверка фио клиента
     * @param string $attribute
     * @param array $param
     */
    public function checkValidClientName($attribute, $param)
    {
        if ($this->owner->$attribute) {
            if (!preg_match('/^[-а-яё\s]+$/ui', $this->owner->$attribute)) {
                $this->owner->addError($attribute, $param['message']);
            } else {
                $this->formatName($this->owner->$attribute);
            }
        }
    }

    public function checkValidClientPhone($attribute, $param)
    {
		if ( $this->owner->$attribute ) {
			//очистка данных
			$this->owner->$attribute = ltrim( $this->owner->$attribute, '+ ' );
			$this->owner->$attribute = preg_replace('/[^\d]/', '', $this->owner->$attribute);

			// убираем лишний знак слева (8-ка или 7-ка)
			if(strlen($this->owner->$attribute) == 11){
				$this->owner->$attribute = substr($this->owner->$attribute,1,10);
			}

			if (strlen($this->owner->$attribute) !== SiteParams::C_PHONE_LENGTH) {
				$this->owner->addError($attribute, $param['message']);
			}
        }
    }

	public function findClientStatusIdByExtraAttribute( $attribute )
	{
		//	проверим alias на уникальность
		$oClient = Client::model()->scopeUniqueExtra( $attribute, $this->owner->$attribute )->find();
		return empty( $oClient )? false: $oClient;
	}

    public function checkValidClientNumericCode($attribute, $param)
    {
        //очистка данных
        $this->owner->$attribute = trim($this->owner->$attribute);
        $this->owner->$attribute = preg_replace('/\s+/', '', $this->owner->$attribute);
        if (strlen($this->owner->$attribute) < SiteParams::C_NUMERIC_CODE_MIN_LENGTH || !preg_match('/^\d+$/', $this->owner->$attribute)) {
            $this->owner->addError($attribute, $param['message']);
        }
    }

    /**
     * форматирование фамилий и имен
     * @param $strName
     */
    private function formatName(&$strName)
    {
        $strName = trim($strName);

        if (!preg_match('/[-\s]+/', $strName)) {
            $strName = $this->convertRegistrRussionWord($strName);
            return;
        }

        $contains_defis = false;
        $contains_white = false;

        // убираем обрамляющие дефис пробелы
        if (preg_match('/-/', $strName)) {
            $strName = preg_replace('/\s*-+\s*/', '-', $strName);
            $contains_defis = true;
        }

        // удаляем ненужные пробелы
        if (preg_match('/\s+/', $strName)) {
            $strName = preg_replace('/\s+/', ' ', $strName);
            $contains_white = true;
        }

        // фамилия содержит только пробел
        if (!$contains_defis) {
            $this->rebuildName($strName, ' ');
            return;
        }

        // фамилия содержит только дефис
        if (!$contains_white) {
            $this->rebuildName($strName, '-');
            return;
        }

        // если мы здесь - то слово содержит и дефис и пробел
        $this->rebuildName($strName, '-');
        $this->rebuildName($strName, ' ');
    }

    /**
     * вспомогательная функция для нормализации фамилий и имен
     * @param string $strName
     * @param string $delimiter
     */
    private function rebuildName(&$strName, $delimiter)
    {
        $aNames = explode($delimiter, $strName);

        foreach ($aNames as &$name) {
            $name = $this->convertRegistrRussionWord($name);
        }

        $strName = implode($delimiter, $aNames);
    }

    /**
     * изменение регистра для русских букв
     * @param $strWord
     * @return string
     */
    private function convertRegistrRussionWord($strWord)
    {
        $strWord = mb_strtolower($strWord, 'UTF-8');
        $strWord = mb_convert_case($strWord, MB_CASE_TITLE, "UTF-8");

        return $strWord;
    }


	/**
	 * добавляет к дате пустое значение чч:мм:сс
	 * если дата пустая, то в атрибуте будет гггг:мм:дд чч:мм:сс
	 * @param $attribute
	 * @example ContactForm::afterValidate
	 */
	public function addEmptyTime2Date( $attribute ){

		if( strlen( $this->owner->$attribute ) <= 11 ){
			$this->owner->$attribute = trim( $this->owner->$attribute );
			if( empty( $this->owner->$attribute ) ){
				$this->owner->$attribute = SiteParams::EMPTY_DATE;
			}
			$this->owner->$attribute = trim( $this->owner->$attribute ) . ' ' . SiteParams::EMPTY_TIME;
		}

	}

	/**
	 * убирает время из формата гггг:мм:дд чч:мм:сс
	 * остается только гггг:мм:дд
	 * @param $attribute
	 */
	public function initDateFromDatetime( $attribute )
	{
		$this->owner->$attribute = current(explode(' ', $this->owner->$attribute ));
	}



	/**
	 * Превращает дни и часы в секунды
	 *
	 * @param $attribute
	 */
	public function initTimeFromDaysHours($attribute)
	{
		$sDaysAttribute = $attribute . '_days';
		$sHoursAttribute = $attribute . '_hours';

		$sDays = $this->owner->$sDaysAttribute;
		$sHours = $this->owner->$sHoursAttribute;

		$iSeconds = ($sDays * 60 * 60 * 24) + ($sHours * 60 * 60);

		$this->owner->$attribute = $iSeconds;
	}


	/**
	 * превращает timestamp в дни и часы,
	 * сохраняя их в спец-полях *_days и *_hours
	 * @param $attribute
	 */
	public function initDaysHoursFromTime( $attribute ){

		$iTime = $this->getOwner()->$attribute;

		$iDays = floor( $iTime / 60 / 60 / 24 ) ;
		$iHours = $iTime - $iDays * 60 * 60 * 24 ;
		$iHours = $iHours / 60 / 60;

		$sAttrHours = $attribute .'_hours';
		$sAttrDays = $attribute .'_days';

		$this->getOwner()->$sAttrHours = (int)$iHours;
		$this->getOwner()->$sAttrDays = (int)$iDays;

	}

	/**
	 * Проверяет на то, что первая дата не позже второй
	 *
	 * @param $dateAttribute1
	 * @param $dateAttribute2
	 */
	public function checkConflict2Dates($dateAttribute1, $dateAttribute2)
	{
		$iDate1 = $this->owner->$dateAttribute1;
		$iDate2 = $this->owner->$dateAttribute2;

		$iDate1 = strtotime($iDate1);
		$iDate2 = strtotime($iDate2);

		$sDate1Label = $this->owner->getAttributeLabel($dateAttribute1);
		$sDate2Label = $this->owner->getAttributeLabel($dateAttribute2);

		if (!$iDate1) {
			$iDate1 = 0;
		}
		if (!$iDate2) {
			$iDate2 = 0;
		}


		if ($iDate1 > 0 && $iDate2 == 0) {
			return;
		} else if ($iDate1 > $iDate2) {
			$this->owner->addError($dateAttribute1, $sDate1Label . ' не может быть позже или в тот же день с ' . $sDate2Label);
		}
	}
	
}