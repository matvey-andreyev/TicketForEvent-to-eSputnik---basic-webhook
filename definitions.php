<?
// Данные для работы скрипта


/* Параметры доступа к API eSputnik
 */
$user = 'example@example.com';
$pass = 'password';



/* Соответствия экспортных имен TicketForEvent
 * идентификаторам полей в eSputnik
 
 * Ключи:
 *  	https://admin.ticketforevent.com/ru/manage/event/109542/form/
 *		редактирование поля
 *		Флажок «Показать экспертные настройки (не рекомендуется)»
 *		Имя поля (для экспорта)
 *
 * Значения:
 *		Числовые идентификаторы полей eSputnik.
 * 		Поля заводятся в настройках АККАУНТА. Там же доступны и их идентификаторы.
 *		Можно завести отдельный список полей, например, «TicketForEvent fields»
 */

$field_dictionary = [
	'fname'		=> 0,
	'lname'		=> 1,
	'email'		=> 2,
	'company'	=> 3,
	'position'	=> 4,
	'phone' 	=> 5,
];
 
 
?>
