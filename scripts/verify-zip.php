<?php
/**
 * Fail when the release zip is missing a required file or contains dev paths.
 */

declare(strict_types=1);

$zip      = new ZipArchive();
$zip_path = dirname( __DIR__ ) . '/dist/nea-block-features.zip';

if ( $zip->open( $zip_path ) !== true ) {
	fwrite( STDERR, "ZIP ni odprt: {$zip_path}\n" );
	exit( 1 );
}

$needles = array(
	'nea-block-features/nea-block-features.php',
	'nea-block-features/uninstall.php',
	'nea-block-features/includes/_media.php',
	'nea-block-features/options/_main.php',
);
$forbidden = array( 'node_modules/', 'phpunit/', 'tests/' );
$found     = array();
$bad       = array();

for ( $i = 0; $i < $zip->numFiles; $i++ ) {
	$name = $zip->getNameIndex( $i );

	foreach ( $needles as $needle ) {
		if ( $name === $needle ) {
			$found[ $needle ] = true;
		}
	}

	foreach ( $forbidden as $item ) {
		if ( str_contains( $name, $item ) ) {
			$bad[] = $name;
		}
	}
}

echo 'files ', $zip->numFiles, PHP_EOL;

$missing = array();

foreach ( $needles as $needle ) {
	$present = isset( $found[ $needle ] );
	echo ( $present ? 'ok ' : 'MISSING ' ), $needle, PHP_EOL;

	if ( ! $present ) {
		$missing[] = $needle;
	}
}

echo 'forbidden ', count( $bad ), PHP_EOL;
$zip->close();

if ( $missing !== array() || $bad !== array() ) {
	exit( 1 );
}
