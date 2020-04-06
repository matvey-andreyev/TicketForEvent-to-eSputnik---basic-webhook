<?

// функции для обработки данных перед отправкой



/* Ищет в данных билетов ($_POST['tickets']) билет, у которого email такой же, как у заказчика
 * Возвращает массив fields_values того билета
 * Должны быть настроены экспортные имена полей
 * @param $data = $_POST (все данные вебхука)
 */
function getBuyerFields($data){
	$result = [];
	
	if( isset($data['buyer']) && isset($data['buyer']['email']) ){
		$buyerEmail = $data['buyer']['email'];
	}
	
	if( $buyerEmail && isset($data['tickets']) && is_array($data['tickets']) ){
		$tickets = $data['tickets'];
		foreach( $tickets as $ticket ){
			if( is_array($ticket['fields_values']) && isset($ticket['fields_values']['email']) ){
				if($buyerEmail === $ticket['fields_values']['email']){
					$result = prepareFieldsForESputnik($ticket['fields_values']);
				}
			}
		}
	}
	
	return $result;
}




/* Приводит массив fields_values
 * к структуре, которая нужна в fields запроса в esputnik
 * использует соответствия идентификаторам еСпутника из $field_dictionary
 * @param $field_values данные полей билета
 */
function prepareFieldsForESputnik($field_values){
	
	global $field_dictionary;
	$result = [];
	
	// Фильтрация емейлов, домены которых еСпутник не принимает/
	if( preg_match('/((@qq.com)|(@163.com)|(@126.com))$/', $field_values['email']) === 1 ){
		return [];
	}
	
	foreach($field_values as $key => $val){
		if( isset($field_dictionary[$key]) ){
			$result[]= [
				'id' => $field_dictionary[$key],
				'value' => $val
			];
		}
	}
	
	return $result;
}

?>
