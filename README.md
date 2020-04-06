# TicketForEvent to eSputnik | basic webhook
 Простой PHP-вебхук, отправляющий данные от TicketForEvent в eSputnik.
 
 Для работы нужно настроить в definitions.php параметры доступа и связь полей TicketForEvent с полями eSputnik.
 
 Разместив данный скрипт на вашем хостинге, укажите его адрес на странице «Серверное оповещение» в админке вашего мероприятия на TicketForEvent.

Описание файлов
-----------
*  **index.php** точка входа. Укажите адрес этого скрипта в админке вашего мероприятия на TicketForEvent
*  **ESputnikClass.php** частичный враппер eSputnik API
*  **definitions.php** параметры доступа, связи полей
*  **functions.php** вспомогательные функции
