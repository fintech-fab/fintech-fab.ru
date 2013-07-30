<?php

class ModelConvertDatetimeBehavior extends CBehavior
{
    /**
     * @var array массив имен полей для преобразования
     */
    public $aConvertedDatetimeFields = array();

    /**
     * Дополняет дату-время до 23:59:59 этой даты (01.01.1970 12:21:36 -> 01.01.1970 23:59:59).
     * Принимает и возвращает дату в виде строки или в виде числа
     *
     * @param mixed $mDatetime
     * @return string|int
     */
    public function doUpdateDatetimeToDayEnd($mDatetime)
    {
		return SiteParams::getDayEndFromDatetime($mDatetime);
    }

    public function beforeSave($oEvent)
    {
        $oModel = $this->getOwner();

        foreach($this->aConvertedDatetimeFields as $sName) {
            $oModel->$sName = $this->doUpdateDatetimeToDayEnd($oModel->$sName);
        }
        return true;
    }
}