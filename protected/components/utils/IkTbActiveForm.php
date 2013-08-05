<?php

Yii::import('bootstrap.widgets.TbActiveForm');

class IkTbActiveForm extends TbActiveForm {

	/**
	 * @param CModel|CActiveForm $oForm
	 * @param $sAttribute
	 * @param $aHtmlOptions
	 * @return string
	 */
	public function fieldMaskedRow( CModel $oForm, $sAttribute, $aHtmlOptions = array() ){

		if(empty($aHtmlOptions['mask'])){
			$aHtmlOptions['mask'] = '+7 999 999 99 99';
		}

		$sReturn  = '';

		if( empty( $aHtmlOptions['hideLabel'] ) ){
			$sReturn .= $this->label(
				$oForm,
				$sAttribute,
				array(
					'required' => $oForm->isAttributeRequired( $sAttribute )
				)
			);
		}

		$aDefaultOptions = array(
			'size' => 16,
			'maxlength' => 16
		);

		if( !empty( $aHtmlOptions ) ){
			$aDefaultOptions = array_merge( $aHtmlOptions, $aDefaultOptions );
		}

		$sReturn .= $this->getController()->widget(
			'CMaskedTextField',
			array(
				'model'			=> $oForm,
				'attribute'		=> $sAttribute,
				'mask'			=> $aHtmlOptions['mask'],
				'htmlOptions'	=> $aDefaultOptions
			),
			true
		);

		$sReturn .= $this->error($oForm, $sAttribute);

		return $sReturn;

	}

	/**
	 * @param CModel|CActiveForm $oForm
	 * @param $sAttribute
	 * @param $aHtmlOptions
	 * @return string
	 */
	public function dateMaskedRow( CModel $oForm, $sAttribute, $aHtmlOptions = array() ){

		if(empty($aHtmlOptions['mask'])){
			$aHtmlOptions['mask'] = '99.99.9999';
		}

		$sReturn  = '';

		if( empty( $aHtmlOptions['hideLabel'] ) ){
			$sReturn .= $this->label(
				$oForm,
				$sAttribute,
				array(
					'required' => $oForm->isAttributeRequired( $sAttribute )
				)
			);
		}

		$aDefaultOptions = array(
			'size' => 10,
			'maxlength' => 10
		);

		if( !empty( $aHtmlOptions ) ){
			$aDefaultOptions = array_merge( $aHtmlOptions, $aDefaultOptions );
		}

		$sReturn .= $this->getController()->widget(
			'CMaskedTextField',
			array(
				'model'			=> $oForm,
				'attribute'		=> $sAttribute,
				'mask'			=> $aHtmlOptions['mask'],
				'htmlOptions'	=> $aDefaultOptions
			),
			true
		);

		$sReturn .= $this->error($oForm, $sAttribute);

		return $sReturn;

	}

	/**
	 * @param CModel|CActiveForm $oForm
	 * @param $sAttribute
	 * @param $aHtmlOptions
	 * @return string
	 */
	public function phoneMaskedRow( CModel $oForm, $sAttribute, $aHtmlOptions = array() ){

		if(empty($aHtmlOptions['mask'])){
			$aHtmlOptions['mask'] = '+7 999 999 99 99';
		}

		$sReturn  = '';

		if( empty( $aHtmlOptions['hideLabel'] ) ){
			$sReturn .= $this->label(
				$oForm,
				$sAttribute,
				array(
					'required' => $oForm->isAttributeRequired( $sAttribute )
				)
			);
		}

		$aDefaultOptions = array(
			'size' => 10,
			'maxlength' => 10
		);

		if( !empty( $aHtmlOptions ) ){
			$aDefaultOptions = array_merge( $aHtmlOptions, $aDefaultOptions );
		}

		$sReturn .= $this->getController()->widget(
			'CMaskedTextField',
			array(
				'model'			=> $oForm,
				'attribute'		=> $sAttribute,
				'mask'			=> $aHtmlOptions['mask'],
				'htmlOptions'	=> $aDefaultOptions
			),
			true
		);

		$sReturn .= $this->error($oForm, $sAttribute);

		return $sReturn;

	}

	public function dateFieldRow( CModel $oForm, $sAttribute, $aHtmlOptions = array() )
	{
		$sReturn = '';

		if( empty( $aHtmlOptions['hideLabel'] ) ){
			$sReturn .= $this->label(
				$oForm,
				$sAttribute,
				array(
					'required' => $oForm->isAttributeRequired( $sAttribute )
				)
			);
		}

		$aDefaultOptions = array(
		);

		if( !empty( $aHtmlOptions ) ){
			$aDefaultOptions = array_merge( $aHtmlOptions, $aDefaultOptions );
		}

		return
			$sReturn .
			$this->dateField($oForm, $sAttribute, $aDefaultOptions ) .
			$this->error($oForm, $sAttribute )
		;

	}


	public function fieldCombineDaysHours( CModel $oForm, $sAttribute, $sLabel, $aHtmlOptions = array() ){

		$sReturn = '';
		if( empty( $aHtmlOptions['hideLabel'] ) ){
			$sReturn .= $this->label(
				$oForm,
				$sAttribute,
				array(
					'class' => 'span2',
					'label' => $sLabel,
					'required' => $oForm->isAttributeRequired( $sAttribute .'_days' ),
				)
			);
		}

		$aDays = array();
		$i = 0;
		while( $i <= 180 ){
			$aDays[$i] = $i . ' д.';
			$i++;
		}

		$aHours = array();
		$i = 0;
		while( $i <= 23 ){
			$aHours[$i] = $i . ' ч.';
			$i++;
		}

		$sReturn .= '<span class="span2">';
			$sReturn .= $this->dropDownList( $oForm, $sAttribute . '_days', $aDays, array( 'class' => 'span1' ) );
			$sReturn .= '&nbsp;';
			$sReturn .= $this->dropDownList( $oForm, $sAttribute . '_hours', $aHours, array( 'class' => 'span1' ) );
		$sReturn .= '</span>';
		$sReturn .= $this->error( $oForm, $sAttribute . '_days' );

		return $sReturn;

	}

}
