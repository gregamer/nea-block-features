# neAGENCIJA Gutenberg Features

WordPress vtičnik za dodatne možnosti blokov in prikaz shranjenih vzorcev.

Nastavitve so v adminu pod menijem **neAGENCIJA**. Funkcije s kljukico so privzeto vklopljene.

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
