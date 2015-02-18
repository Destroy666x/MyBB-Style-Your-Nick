<?php

/*
Nazwa: Stylizuj nazwę użytkownika
Autor: Destroy666
Wersja: 1.1
Wymagania: Plugin Library, PostgreSQL 9.1
Informacje: Plugin dla skryptu MyBB, zakodowany dla wersji 1.8.x (może także działać w 1.6.x/1.4.x - ale już nie bez zmian w hookach).
Pozwala zmieniać styl nazwy użytkownika w panelu (bazując na uprawnieniach grupy).
1 edycja pliku źródłowego, 1 nowa tabela bazy danych, 14 nowych kolumn bazy danych, 8 nowych szablonów, 1 zmiana w szablonie, 11 nowych ustawień
Licencja: GNU GPL v3, 29 June 2007. Więcej informacji w pliku LICENSE.md.
Support: officjalne forum MyBB - http://community.mybb.com/mods.php?action=profile&uid=58253 (nie odpowiadam na PM, tylko na posty)
Zgłaszanie błędów: mój github - https://github.com/Destroy666x

© 2015 - date("Y")
*/

$l['style_your_nick'] = 'Stylizuj nazwę użytkownika';
$l['style_your_nick_options'] = 'Opcje stylizacji';
$l['style_your_nick_no_options'] = 'Aktualnie Twoja grupa nie ma dostępu do żadnej opcji stylizacji.';
$l['style_your_nick_save'] = 'Zapisz';
$l['style_your_nick_clear'] = 'Wyczyść';
$l['style_your_nick_success'] = 'Stylizacja została z powodzeniem zmieniona. Zostaniesz teraz przekierowany na stronę edycji stylizacji.';
$l['style_your_nick_clear_success'] = 'Stylizacja została z powodzeniem wyczyszczona. Zostaniesz teraz przekierowany na stronę edycji stylizacji.';

$l['style_your_nick_desc_color'] = '<strong>Kolor czcionki</strong><br />
Podaj prawidłowy kolor CSS. Wspierane formaty: ustalone nazwy (red, green, itd.), HEX #123DEF, RGB(255,255,255).';
$l['style_your_nick_desc_background'] = '<strong>Kolor tła</strong><br />
Podaj prawidłowy kolor CSS. Wspierane formaty: ustalone nazwy (red, green, itd.), HEX #123DEF, RGB(255,255,255).';
$l['style_your_nick_desc_backgroundimg'] = '<strong>Obrazek w tle</strong><br />
Podaj prawidłowy link do obrazka.';
$l['style_your_nick_desc_backgroundrepeat'] = 'Tło ma się powtarzać w poziomie i pionie?';
$l['style_your_nick_desc_size'] = '<strong>Rozmiar czcionki</strong><br />
Podaj prawidłowy rozmiar. Wspierane formaty: 3px, 1.7cm, 14mm, 0.2in';
$l['style_your_nick_desc_italic'] = 'Zmień styl czcionki na kursywę?';
$l['style_your_nick_desc_bold'] = 'Pogrub czcionkę?';
$l['style_your_nick_desc_underline'] = 'Podkreśl nazwę użytkownika?';
$l['style_your_nick_desc_overline'] = 'Nadkreśl nazwę użytkownika?';
$l['style_your_nick_desc_strike'] = 'Przekreśl nazwę użytkownika?';
$l['style_your_nick_desc_capital'] = 'Pisz każde słowo z nazwy użytkownika dużą literą?';
$l['style_your_nick_desc_shadowx'] = '<strong>X cienia</strong><br />
Podaj prawidłową pozycje. Jest ona wymagana dla działania cienia. Wspierane formaty: 3px, 1.7cm, 14mm, 0.2in';
$l['style_your_nick_desc_shadowy'] = '<strong>Y cienia</strong><br />
Podaj prawidłową pozycje. Jest ona wymagana dla działania cienia. Wspierane formaty: 3px, 1.7cm, 14mm, 0.2in';
$l['style_your_nick_desc_shadowlength'] = '<strong>Długość cienia/radius</strong><br />
Podaj prawidłowy rozmiar. Wspierane formaty: 3px, 1.7cm, 14mm, 0.2in';
$l['style_your_nick_desc_shadowcolor'] = '<strong>Kolor cienia</strong><br />
Podaj prawidłowy kolor CSS. Wspierane formaty: ustalone nazwy (red, green, itd.), HEX #123DEF, RGB(255,255,255).';

$l['style_your_nick_error_disallowed'] = 'Próbujesz zmienić opcję, która jest niedostępna dla Twojej grupy.';
$l['style_your_nick_error_not_filled'] = 'Nie wypełniłeś żadnego pola. Przynajmniej jedno musi zostać zmienione.';
$l['style_your_nick_error_trans'] = ' Przezroczyste kolory takie jak RGBA są niedozwolone.';
$l['style_your_nick_error_disallowed_colors'] = ' Następujące kolory nie mogą być użyte: {1}';
$l['style_your_nick_error_max_px'] = ' Maksimum: {1}px.';
$l['style_your_nick_error_min_px'] = ' Minimum: {1}px.';
$l['style_your_nick_error_color'] = 'Nieprawidłowy kolor czcionki. Upewnij się, że wpisujesz poprawny format.{1}{2}';
$l['style_your_nick_error_background'] = 'Nieprawidłowy kolor tła. Upewnij się, że wpisujesz poprawny format.{1}{2}';
$l['style_your_nick_error_shadowcolor'] = 'Nieprawidłowy kolor cienia. Upewnij się, że wpisujesz poprawny format.{1}{2}';
$l['style_your_nick_error_shadowxy'] = 'Oba X i Y cienia muszą zostać podane.';
$l['style_your_nick_error_size'] = 'Nieprawidłowy rozmiar czcionki. Upewnij się, że wpisujesz poprawny format.{1}{2}';
$l['style_your_nick_error_shadowx'] = 'Nieprawidłowa pozycja X cienia. Upewnij się, że wpisujesz poprawny format.{1}{2}';
$l['style_your_nick_error_shadowy'] = 'Nieprawidłowy pozycja Y cienia. Upewnij się, że wpisujesz poprawny format.{1}{2}';
$l['style_your_nick_error_shadowlength'] = 'Nieprawidłowa długość cienia. Upewnij się, że wpisujesz poprawny format.{1}{2}';
$l['style_your_nick_error_backgroundimgrepeat'] = 'Nie wybrałeś obrazka tła, który ma być powtarzany.';
$l['style_your_nick_error_backgroundimg_wrong'] = 'Link do obrazka tła, który wskazałeś, nie prowadzi do prawidłowego obrazka.';
$l['style_your_nick_error_backgroundimg_size'] = 'Obrazek tła jest za duży. Maksymalne dozwolone wymiary to {1}x{2}, a obrazek ma {3}x{4}.';