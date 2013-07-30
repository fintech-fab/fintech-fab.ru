<?php

/**
 * Class SerializeOptions
 *
 * @method CActiveRecord getOwner()
 */
class SerializeOptions extends CBehavior{
	
	public $sFieldNick = 'options';
	
	public $aSerializeFields = array();
	
	
	public function getOptions(){
		
		$aOptions = array();
		foreach( $this->aSerializeFields as $name ){
			$aOptions[$name] = $this->getOwner()->$name;
		}

		return $aOptions;
		
	}
	
	
	public function beforeSave( $oEvent ) {
		
		$oModel = $this->getOwner();
		$aOptions = array();
		
		foreach( $this->aSerializeFields as $sField ) {
			$aOptions[ $sField ] = $oModel->$sField;
		}
		
		$aOptions = array_filter( $aOptions, array( $this, '_isNotEmptyValue' ) );
		$sOptions = '';
		
		if( !empty( $aOptions ) ){
			$sOptions = serialize( $aOptions );
		}
		
		$sFieldNick = $this->sFieldNick;
		$oModel->setAttribute( $sFieldNick, $sOptions );
		$oModel->$sFieldNick = $sOptions;

		return true;
		
	}
	
	public function afterFind( $oEvent ) {
		
		$sFieldNick = $this->sFieldNick;
		$oModel = $this->getOwner();

		$aOptions = $oModel->$sFieldNick;
		if( !is_array( $aOptions ) ){
			$aOptions = unserialize( $oModel->$sFieldNick );
		}
		
		if( !empty( $aOptions ) ) {
			foreach( $this->aSerializeFields as $sField ) {
				if( isset( $aOptions[ $sField ] ) ) {
					$oModel->$sField = $aOptions[ $sField ];
				}
			}
		}
		
		$oModel->$sFieldNick = null;
		return $this;
		
	}
	
	public function setOptions( $a ){

		if( !$a ){
			$a = array();
		}

		foreach( $a as $key => $value ){
			if ( in_array( $key, $this->aSerializeFields ) ){
				$this->getOwner()->$key = $value;
			}
		}
		
	}
	
	private function _isNotEmptyValue( $mValue ) {
		return !empty( $mValue );
	}
	
}	

?>
