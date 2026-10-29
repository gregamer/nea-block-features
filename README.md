# neAGENCIJA dodatne funkcionalnosti Gutenberg blokov

WordPress vtičnik za dodatne možnosti blokov in prikaz shranjenih vzorcev.

Nastavitve so v adminu pod menijem **neAGENCIJA**. Funkcije s kljukico so privzeto vklopljene. Prag za pomanjšanje slik je vedno vklopljen.

## Vidnost blokov

V urejevalniku dobi vsak blok ploščo Visibility Options. Kljukice dodajo razrede `block-hidden`, `hide-mobile`, `hide-tablet` in `hide-desktop`.

V urejevalniku so taki bloki zatemnjeni, `block-hidden` je skrit. Na spletnem mestu te razrede skrije CSS teme.

## Obrat stolpcev na mobilnem

Pri blokih Columns in Media & Text se v stranski vrstici pojavi kljukica Mobile reverse. Pod izbranim breakpointom (privzeto 781 px) se vrstni red vsebin obrne.

## Povezava stolpca

Pri bloku Column (ne pri Columns) je v stranski vrstici razdelek Povezava stolpca. Vanj se vnese URL, po želji odpiranje v novem zavihku in `nofollow`.

Na spletnem mestu klik na prosto površino stolpca odpre ta URL. Povezave in gumbi znotraj stolpca odprejo svoj cilj. V urejevalniku stolpec ostane urejljiv in povezave ne odpira.

## Shortcode za vzorec

Izpiše shranjen sinhroniziran vzorec (`wp_block`):

```
[show_pattern id="123"]
[show_pattern slug="ime-vzorca"]
```

Slug je pot iz naslova vzorca. Če je aktiven WPML, se uporabi prevod.

## Prag za pomanjšanje slik

Ob nalaganju WordPress pomanjša sliko, če je širina ali višina večja od praga, in pomanjšano kopijo uporabi kot polno velikost. Privzeti WordPressov prag je 2560 px. Ta vtičnik ga dvigne na 6000 px.

To ne spremeni omejitve velikosti nalaganja (`upload_max_filesize`).
