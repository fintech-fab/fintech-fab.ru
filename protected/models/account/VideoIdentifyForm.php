<?php
/**
 * Class VideoIdentifyForm
 *
 */
class VideoIdentifyForm extends CFormModel
{
	public $client_code;
	public $service;
	public $signature;
	public $timestamp;
	public $redirect_back_url;
	public $video_url;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('client_code, service, signature, timestamp, video_url', 'required'),
		);

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		array('client_code' => 'Client Code');
		array('service' => 'Service');
		array('signature' => 'Signature');
		array('timestamp' => 'Timestamp');
		array('redirect_back_url' => 'Redirect Back Url');
		array('video_url' => 'Video URL');
	}
}
