<?php
/**
 * Build an installable plugin archive.
 */

declare(strict_types=1);

$root        = dirname( __DIR__ );
$destination = $root . '/dist/nea-block-features.zip';

if ( ! is_dir( dirname( $destination ) ) && ! mkdir( dirname( $destination ), 0755, true ) && ! is_dir( dirname( $destination ) ) ) {
	fwrite( STDERR, "Mapa dist ni ustvarjena.\n" );
	exit( 1 );
}

if ( is_file( $destination ) ) {
	unlink( $destination );
}

$zip = new ZipArchive();

if ( $zip->open( $destination, ZipArchive::CREATE ) !== true ) {
	fwrite( STDERR, "ZIP datoteke ni mogoče ustvariti.\n" );
	exit( 1 );
}

$exclude  = '#/(?:node_modules|tests|dist|\.github|\.cursor|\.git|\.phpunit\.cache)(?:/|$)#';
$iterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator( $root, FilesystemIterator::SKIP_DOTS ),
	RecursiveIteratorIterator::SELF_FIRST
);

foreach ( $iterator as $file ) {
	$path       = $file->getPathname();
	$relative   = substr( $path, strlen( $root ) + 1 );
	$normalized = str_replace( '\\', '/', $relative );

	if ( preg_match( $exclude, '/' . $normalized ) ) {
		continue;
	}

	if ( $file->isDir() ) {
		$zip->addEmptyDir( 'nea-block-features/' . $normalized );
		continue;
	}

	$zip->addFile( $path, 'nea-block-features/' . $normalized );
}

$zip->close();
echo $destination . PHP_EOL;
