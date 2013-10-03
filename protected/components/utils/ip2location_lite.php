<?php
/**
 * Class ip2location_lite
 */
class ip2location_lite
{
	protected $errors = array();
	protected $service = 'api.ipinfodb.com';
	protected $version = 'v3';
	protected $apiKey = '698b07c5c2ea72b8404ea42ae02f1b53043b3bb90931db31524cc91f9911f648';

	/**
	 *
	 */
	public function __construct()
	{
	}


	public function __destruct()
	{
	}

	/**
	 * @param $key
	 */
	public function setKey($key)
	{
		if (!empty($key)) {
			$this->apiKey = $key;
		}
	}

	/**
	 * @return string
	 */
	public function getError()
	{
		return implode("\n", $this->errors);
	}

	/**
	 * @param $host
	 *
	 * @return mixed
	 */

	public function getCountry($host)
	{
		return $this->getResult($host, 'ip-country');
	}

	/**
	 * @param $host
	 *
	 * @return mixed
	 */
	public function getCity($host)
	{
		return $this->getResult($host, 'ip-city');
	}

	/**
	 * @param $host
	 * @param $name
	 *
	 * @return mixed
	 */
	private function getResult($host, $name)
	{
		$ip = @gethostbyname($host);

		if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
			$ctx = stream_context_create(array(
				'http' =>
				array(
					'timeout' => 3
				)
			));

			$xml = @file_get_contents('http://' . $this->service . '/' . $this->version . '/' . $name . '/?key=' . $this->apiKey . '&ip=' . $ip . '&format=xml', false, $ctx);


			if (get_magic_quotes_runtime()) {
				$xml = stripslashes($xml);
			}

			try {
				$response = @new SimpleXMLElement($xml);

				foreach ($response as $field => $value) {
					$result[(string)$field] = (string)$value;
				}

				return $result;
			} catch (Exception $e) {
				$this->errors[] = $e->getMessage();

				return;
			}
		}

		$this->errors[] = '"' . $host . '" is not a valid IP address or hostname.';

		return;
	}
}
