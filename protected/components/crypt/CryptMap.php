<?php
require_once(dirname(__FILE__) . '/CryptArray.php');

/**
 * Класс для карты шифрованных событий системы
 *
 * Пример наследуемого класса и пример использования в конце файла
 *
 * @subpackage idsCryptedUrl
 * @author Zagirov Rustam <rustam@zagirov.name>
 */
abstract class CryptMap {
	
	/**
	 * Массив с картой событий системы
	 * Не использовать зарезервированные ключи params и mapName
	 * Пример:
	 * <code>
	 * array(
	 * 	'test' => array(
	 * 		'route' => 'some/page',
	 * 		'time' => 259200,//60*60*24*3
	 * 	),
	  * );
	 * </code>
	 * @var array
	 */
	static $aMap;

	/**
	 * Массив ключей, который соответсвует элементам, передаваемым в шифрованной ссылке
	 * @var array
	 */
	static $aKeys = array(
		'id',
		'action',
		'time',
		1,
		2,
		3,
		4,
		5
	);

	/**
	 * Возвращаем значение из static::$aMap по ключу
	 * @param string $sKey
	 */
	static public function getMapByKey($sKey){
		
		$oReturn =  (isset(static::$aMap[$sKey]))
			? static::$aMap[$sKey]
			: null
		;
		
		return $oReturn;
		
	}

	/**
	 * Формируем шифрованный url
	 * @param string $sMapKey Значение из static::aMap
	 * @param integer $id
	 * @param boolean $bUseNewCrypt Использовать новое криптование?
	 * @return string ключ
	 */
	protected static function _getCryptedCode( $sMapKey, $id ){
		
		$aData = array(
			'id'		=> $id,
			'action'	=> $sMapKey,
			'time'		=> time(),
		);

		return CryptArray::encrypt(array_values($aData));
		
	}
	
	/**
	* получить шифровку по произвольному набору данных
	* 
	* @param string $sMapKey
	* @param array $aParams
	*/
	protected static function _getCryptedComplexCode( $sMapKey, $aParams ){
		
		$iId = ( isset( $aParams['id'] ) )
			? $aParams['id']
			: 0;
			
		if( isset( $aParams['id'] ) ){
			unset( $aParams['id'] );
		}
		
		$aData = array(
			'id'		=> $iId,
			'action'	=> $sMapKey,
			'time'		=> time(), // метка времени
		);
		
		$aData += $aParams;
		
		$aParams['action'] = $sMapKey;

		return CryptArray::encrypt( array_values( $aData ) );
		
	}


	/**
	 * Расшифровка строки
	 * @param string $sKey Шифрованная строка
	 * @return mixed $mReturn (NULL если расшифровать не получилось)
	 */
	protected static function _getDecryptedParam( $sKey ){
		
		$aSource = CryptArray::decrypt($sKey);
		$aResult = array();

		// Заполняем массив
		foreach (static::$aKeys as $idKey=>$sKeyName){
			
			$aResult[$sKeyName] = isset ($aSource[$idKey])? $aSource[$idKey]: NULL;
			
			/**
			* если это параметр из произвольных (с числовым ключом)
			* и его нет в расшифровке $aSource
			* то удаляем его из результата
			*/
			if( is_int($sKeyName) && strlen( $aResult[$sKeyName]  ) == 0 ){
				unset( $aResult[$sKeyName] );
			}
			
		}

		$mReturn  = NULL;
		
		if (is_array($aResult) && isset($aResult['action']) ){
			
			$aMap = static::getMapByKey($aResult['action']);
			
			if ($aMap){
				
				// Сохраняем имя мэпинга
				$aMap['mapName'] = $aResult['action'];
				
				// Проверяем время жизни ссылки. Если $aMap['time'] == 0, то не проверяем
				if ( ( ( time() - $aResult['time'] ) <= $aMap['time'] ) || ( intval($aMap['time'] ) == 0 ) ){
					unset ( $aResult['action'] );
					$aMap['params']	= $aResult;
					$mReturn		= $aMap;
				}
			}
		}
		
		return $mReturn;
		
	}


	/**
	 * Запрет создания и клонирования объекта
	 */
	private function __construct() { /* ... */ }
	private function __clone() { /* ... */ }
	
}
