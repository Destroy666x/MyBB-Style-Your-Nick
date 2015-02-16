<?php

/*
Nazwa: Stylizuj nazwę użytkownika
Autor: Destroy666
Wersja: 1.1
Wymagania: Plugin Library, PostgreSQL 9.1
Informacje: Plugin dla skryptu MyBB, zakodowany dla wersji 1.8.x (może także działać w 1.6.x/1.4.x).
Pozwala zmieniać styl nazwy użytkownika w panelu (bazując na uprawnieniach grupy).
1 edycja pliku źródłowego, 1 nowa tabela bazy danych, 14 nowych kolumn bazy danych, 8 nowych szablonów, 1 zmiana w szablonie, 11 nowych ustawień
Licencja: GNU GPL v3, 29 June 2007. Więcej informacji w pliku LICENSE.md.
Support: officjalne forum MyBB - http://community.mybb.com/mods.php?action=profile&uid=58253 (nie odpowiadam na PM, tylko na posty)
Zgłaszanie błędów: mój github - https://github.com/Destroy666x

© 2015 - date("Y")
*/

$l['style_your_nick'] = 'Stylizuj nazwę użytkownika';
$l['style_your_nick_info'] = 'Pozwala zmieniać styl nazwy użytkownika w panelu (bazując na uprawnieniach grupy).';
$l['pluginlibrary_missing'] = '<strong>Uwaga:</strong> Modyfikacja wymaga biblioteki Plugin Library do dodawania/usuwania szablonów. Można ją pobrać <a href="https://github.com/frostschutz/MyBB-PluginLibrary/archive/master.zip">tutaj</a>.';
$l['core_changes_error'] = 'Wymagana zmiana w pliku źródłowym nie mogła zostać przeprowadzona.';

$l['style_your_nick_settings'] = 'Ustawienia dla pluginu "Animowane dodatkowe grupy".';
$l['style_your_nick_transparent'] = 'Zezwól na przezroczyste kolory?';
$l['style_your_nick_transparent_desc'] = 'Ustaw na tak aby użytkownicy mogli korzystać z przezroczystych kolorów (transparent i RGBA).';
$l['style_your_nick_disallowed_colors'] = 'Zabronione kolory';
$l['style_your_nick_disallowed_colors_desc'] = 'Podaj nazwy/kody kolorów, które nie mogą być użyte, każdy w nowej linii, bez spacji.';
$l['style_your_nick_max_font'] = 'Maksymalny rozmiar czcionki';
$l['style_your_nick_max_font_desc'] = 'Podaj liczbę reprezentującą maksymalny rozmiar czcionki (w pikselach).';
$l['style_your_nick_min_font'] = 'Minimalny rozmiar czcionki';
$l['style_your_nick_min_font_desc'] = 'Podaj liczbę reprezentującą minimalny rozmiar czcionki (w pikselach).';
$l['style_your_nick_max_shadowx'] = 'Maksymalna pozycja X cienia';
$l['style_your_nick_max_shadowx_desc'] = 'Podaj liczbę reprezentującą maksymalną pozycję horyzontalna cienia (w pikselach).';
$l['style_your_nick_min_shadowx'] = 'Minimalna pozycja X cienia';
$l['style_your_nick_min_shadowx_desc'] = 'Podaj liczbę reprezentującą minimalną pozycję horyzontalną cienia (w pikselach).';
$l['style_your_nick_max_shadowy'] = 'Maksymalna pozycja Y cienia';
$l['style_your_nick_max_shadowy_desc'] = 'Podaj liczbę reprezentującą maksymalną pozycję wertykalną cienia (w pikselach).';
$l['style_your_nick_min_shadowy'] = 'Minimalna pozycja Y cienia';
$l['style_your_nick_min_shadowy_desc'] = 'Podaj liczbę reprezentującą minimalną pozycję wertykalną cienia (w pikselach).';
$l['style_your_nick_max_shadowlength'] = 'Maksymalna długość cienia';
$l['style_your_nick_max_shadowlength_desc'] = 'Podaj liczbę reprezentującą maksymalną długość cienia (w pikselach).';
$l['style_your_nick_min_shadowlength'] = 'Minimalna długość cienia';
$l['style_your_nick_min_shadowlength_desc'] = 'Podaj liczbę reprezentującą minimalną długość cienia (w pikselach).';
$l['style_your_nick_max_backgroundimg'] = 'Maksymalne wymiary obrazka tła';
$l['style_your_nick_max_backgroundimg_desc'] = 'Podaj maksymalną długość i wysokość obrazka tła, oddziel je znakiem "x" (w pikselach).';

$l['style_your_nick_general'] = 'Generalne';
$l['style_your_nick_can_style'] = 'Może stylizować nazwę użytkownika?<br />
<small><strong>Uwaga:</strong> zaznaczenie tej opcji jest wymagane by reszta działała.</small>';
$l['style_your_nick_use_default'] = "Użyj domyślnego stylu grupy jeśli użytkownik nie skorzysta ze własnej stylizacji?
<small><strong>Uwaga:</strong> odznaczenie tej opcji nie jest zalecane dla dużych forów, gdyż zostanie użyte wolniejsze zapytanie do bazy.</small>";
$l['style_your_nick_styling'] = 'Stylizacja';
$l['style_your_nick_can_color'] = 'Może zmieniać kolor czcionki?';
$l['style_your_nick_can_background'] = 'Może zmieniać kolor tła?';
$l['style_your_nick_can_background_img'] = 'Może zmieniać obrazek tła?';
$l['style_your_nick_can_background_repeat'] = 'Może włączyć powtarzanie się obrazka tła?';
$l['style_your_nick_can_size'] = 'Może zmieniać rozmiar czcionki?';
$l['style_your_nick_can_italic'] = 'Może zmieniać styl czcionki na kursywę?';
$l['style_your_nick_can_bold'] = 'Może pogrubiać czionkę?';
$l['style_your_nick_can_underline'] = 'Może podkreślać nazwę użytkownika?';
$l['style_your_nick_can_overline'] = 'Może nadkreślać nazwę użytkownika?';
$l['style_your_nick_can_strike'] = 'Może przekreślać nazwę użytkownika?';
$l['style_your_nick_can_capital'] = 'Może zmieniać pierwszą literę każdego słowa nazwy użytkownika na dużą literę?';
$l['style_your_nick_can_shadow'] = 'Może dodawać cień do nazwy użytkownika?';