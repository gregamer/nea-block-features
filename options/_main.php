<div class="wrap">
	<h2><?php echo $this->plugin_name;?></h2>
    <br />
	<form method="post" action="options.php">
	<?php settings_fields( 'nea_block_features_options' ); ?>
	<?php do_settings_sections( 'nea_block_features_options' ); ?>
	<div class="nfm-grid">

        <div class="section-title">Vidnost blokov</div>
        <div class="description">V urejevalniku dobi vsak blok ploščo Visibility Options. Kljukice dodajo razrede <code>block-hidden</code>, <code>hide-mobile</code>, <code>hide-tablet</code> in <code>hide-desktop</code>. V urejevalniku so taki bloki zatemnjeni, <code>block-hidden</code> je skrit. Na spletnem mestu te razrede skrije CSS teme.</div>
        <div class="title">Omogoči vidnost blokov:</div>
        <div class="value">
            <input type="hidden" name="nea_block_features_enable_block_visibility" value="0" />
            <input type="checkbox" name="nea_block_features_enable_block_visibility" id="nea_block_features_enable_block_visibility" value="1" <?php checked( '1', get_option( 'nea_block_features_enable_block_visibility', '1' ) ); ?> />
        </div>

        <div class="section-title">Obrat stolpcev na mobilnem</div>
        <div class="description">Pri blokih Columns in Media &amp; Text se v stranski vrstici pojavi kljukica Mobile reverse. Pod izbranim breakpointom se vrstni red vsebin obrne.</div>
        <div class="title">Omogoči mobile reverse:</div>
        <div class="value">
            <input type="hidden" name="nea_block_features_enable_mobile_reverse" value="0" />
            <input type="checkbox" name="nea_block_features_enable_mobile_reverse" id="nea_block_features_enable_mobile_reverse" value="1" <?php checked( '1', get_option( 'nea_block_features_enable_mobile_reverse', '1' ) ); ?> />
        </div>
        <div class="title">Mobile breakpoint (px):</div>
        <div class="value"><input type="number" min="1" step="1" name="nea_block_features_mobile_breakpoint" id="nea_block_features_mobile_breakpoint" value="<?php echo esc_attr( get_option( 'nea_block_features_mobile_breakpoint', '781' ) ); ?>" /></div>

        <div class="section-title">Povezava stolpca</div>
        <div class="description">Pri bloku Column se v stranski vrstici pojavi razdelek Povezava stolpca. Če je nastavljen URL, klik na prosto površino stolpca na spletnem mestu odpre povezavo. Povezave in gumbi znotraj stolpca ostanejo samostojni. V urejevalniku je stolpec še naprej urejljiv.</div>
        <div class="title">Omogoči povezavo stolpca:</div>
        <div class="value">
            <input type="hidden" name="nea_block_features_enable_column_link" value="0" />
            <input type="checkbox" name="nea_block_features_enable_column_link" id="nea_block_features_enable_column_link" value="1" <?php checked( '1', get_option( 'nea_block_features_enable_column_link', '1' ) ); ?> />
        </div>

        <div class="section-title">Shortcode za vzorec</div>
        <div class="description">Shortcode <code>[show_pattern id="123"]</code> ali <code>[show_pattern slug="ime-vzorca"]</code> izpiše shranjen sinhroniziran vzorec. Slug je pot iz naslova vzorca. Če je aktiven WPML, se uporabi prevod.</div>
        <div class="title">Omogoči shortcode:</div>
        <div class="value">
            <input type="hidden" name="nea_block_features_enable_reusable_blocks" value="0" />
            <input type="checkbox" name="nea_block_features_enable_reusable_blocks" id="nea_block_features_enable_reusable_blocks" value="1" <?php checked( '1', get_option( 'nea_block_features_enable_reusable_blocks', '1' ) ); ?> />
        </div>

	</div>

	<?php submit_button();?>
	</form>
</div>


<style>
    .wrap textarea{width: 100%;}
    .nfm-grid
    {
        display: grid;
        grid-template-columns: 3fr 1fr;
        grid-gap: 1rem 2rem;
        max-width: 860px;
    }

    .nfm-grid .section-title{
        grid-column: 1 / -1;
        font-size: 1.2rem;
        font-weight: bold;
        margin-top: 0.5rem;
    }

    .nfm-grid .description{
        grid-column: 1 / -1;
        margin-top: -0.5rem;
    }
</style>
