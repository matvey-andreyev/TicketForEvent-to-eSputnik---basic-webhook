<?
/* Add a new contact to ESputnik
 * Укажите адрес этого скрипта на странице «Серверное оповещение» в TicketForEvent
 * https://admin.ticketforevent.com/ru/manage/event/109542/webhook/
 *
 * Документация добавления контакта:
 * https://esputnik.com/api/methods.html#/v1/contact-POST
 * 
 * @author: ma@ticketforevent.com
 */





// Данные от вебхука TicketForEvent приходят в POST-запросе
$data = $_POST;


// Параметры доступа, словарик с идентификаторами полей.
require_once "definitions.php";	

// враппер eSputnik API
require_once "ESputnikClass.php";

// вспомогательные функции	
require_once "functions.php";	



try{
	$eSputnik = new ESputnikClass($user, $pass);

	if ($eSputnik !== false) {

		// Этически TicketForEvent может добавлять в еСпутник только данные покупателя.
		// Если кто-то купил 5 билетов на чужие емейлы, мы не можем их добавлять,
		// так как они не принимали никаких условий.
		// Поэтому ниже — отправка только данных заказчика.
		// Если заказчик покупает билет себе, данные из анкеты попадут в поле fields (дополнительные поля eSputnik).
		// Если заказчик покупал не себе, в еСпутник попадут только данные заказчика без дополнительных полей.
		$buyerData = array(
			'firstName'        	=> $data['buyer']['firstname'],
			'lastName'         	=> $data['buyer']['lastname'],
			
			// Каналы. https://esputnik.com/api/ns0_channelType.html
			'channels'			=> array(
				array(
					'type'	=> 'email',
					'value'	=> $data['buyer']['email']
				), 
				array(
					'type'	=> 'sms',
					'value'	=> $data['buyer']['phone']
				)
			),
			
			// Анкетные поля.
			// Структура массива: https://esputnik.com/api/el_ns0_contact.html
			'fields'			=> getBuyerFields($data),
			
			// Название «сегмента», которым помечается добавляемый контакт
			// Сегмент можно делать произвольным, без всяких числовых идентификаторов — eSputnik просто создаст его, если еще не было
			'groups' 			=> array(
				[
					'name' => 'ticketforevent_buyers' // покупатели с TicketForEvent
				],
				[
					'name' => 'ticketforevent_buyers_' . $data['buyer']['customer_type'] // физлицо : individual | юрлицо : legal_entity
				]
			),
			
			// язык, на котором был сделан заказ
			'languageCode' => $data['language']
		);
		
		// Добавление контакта в eSputnik
		$result = $eSputnik->addContact($buyerData);
		
		// Обработка ответа от АПИ
		if ($result->id) {
			
			// В случае успешной отработки данного скрипта, TicketForEvent ждёт строчку «ok»
			// Если ее не передать, TicketForEvent будет пытаться отправить эти же данные 10 раз
			// в течение нескольких дней
			echo "ok";
			
		} else {
			// Если от еСпутника не получен ид нового контакта, выводим дамп. Часть дампа получит TFE для последующего исправления проблемы.
			echo "noid<br/>";
			echo '<h4>data:</h4>' . print_r($data, true) . '<hr />';
			echo '<h4>result:</h4>' . print_r($result, true) . '<hr />';
			echo '<h4>http_status:</h4>' . print_r($eSputnik->http_status, true) . '<hr />';
			echo '<h4>balance:</h4>' . print_r($eSputnik->getBalance(), true) . '<hr />';
			echo '<h4>account info:</h4>' . print_r($eSputnik->getAccountInfo(), true) . '<hr />';
		}
	} else {
		// Если не подключился класс в враппером АПИ, выводим дамп. Часть дампа получит TFE для последующего исправления проблемы.
		echo "noapi<br/>";
		echo '<h4>data:</h4>' . print_r($data, true) . '<hr />';
		echo '<h4>result:</h4>' . print_r($result, true) . '<hr />';
		echo '<h4>http_status:</h4>' . print_r($eSputnik->http_status, true) . '<hr />';
		echo '<h4>balance:</h4>' . print_r($eSputnik->getBalance(), true) . '<hr />';
		echo '<h4>account info:</h4>' . print_r($eSputnik->getAccountInfo(), true) . '<hr />';
	}				
	
} catch(Exception $e){
	// Общая ошибка, выводим дамп. Часть дампа получит TFE для последующего исправления проблемы.
	echo "try exception:" . $e->getMessage();
	echo '<h4>data:</h4>' . print_r($data, true)\;
}

exit;

?>