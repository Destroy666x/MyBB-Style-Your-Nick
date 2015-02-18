<?php

/*
Nazwa: Stylizuj nazwę użytkownika
Autor: Destroy666
Wersja: 1.0
Wymagania: Plugin Library, PostgreSQL 9.1
Informacje: Plugin dla skryptu MyBB, zakodowany dla wersji 1.8.x (może także działać w 1.6.x/1.4.x - ale już nie bez zmian w hookach).
Pozwala zmieniać styl nazwy użytkownika w panelu (bazując na uprawnieniach grupy).
1 edycja pliku źródłowego, 1 nowa tabela bazy danych, 14 nowych kolumn bazy danych, 8 nowych szablonów, 1 zmiana w szablonie, 10 nowych ustawień
Licencja: GNU GPL v3, 29 June 2007. Więcej informacji w pliku LICENSE.md.
Support: officjalne forum MyBB - http://community.mybb.com/mods.php?action=profile&uid=58253 (nie odpowiadam na PM, tylko na posty)
Zgłaszanie błędów: mój github - https://github.com/Destroy666x

© 2015 - date("Y")
*/

$l['style_your_nick_online'] = '<a href="usercp.php?action=style_your_nick">Stylizuje nazwę użytkownika</a>';