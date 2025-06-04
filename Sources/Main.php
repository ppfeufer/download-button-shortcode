<?php

namespace Ppfeufer\Plugin\DownloadButtonShortcode;

use Ppfeufer\Plugin\DownloadButtonShortcode\Libs\YahnisElsts\PluginUpdateChecker\v5p6\PucFactory;

/**
 * Main class for the Download Button Shortcode plugin
 *
 * @package Ppfeufer\Plugin\DownloadButtonShortcode
 */
class Main {
    /**
     * Main constructor.
     *
     * @access public
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the plugin
     *
     * @return void
     * @access public
     */
    public function init(): void {
        $this->doUpdateCheck();
        $this->initializeHooks();
    }

    /**
     * Check for updates
     *
     * @return void
     * @access public
     */
    public function doUpdateCheck(): void {
        PucFactory::buildUpdateChecker(
            metadataUrl: PLUGIN_GITHUB_URL,
            fullPath: PLUGIN_DIR_PATH . '/DownloadButtonShortcode.php',
            slug: PLUGIN_SLUG
        )->getVcsApi()->enableReleaseAssets();
    }

    /**
     * Initialize the hooks
     *
     * @return void
     * @access private
     */
    private function initializeHooks(): void {
        // Load the text domain.
        add_action(hook_name: 'init', callback: static function () {
            load_plugin_textdomain(
                domain: PLUGIN_SLUG,
                plugin_rel_path: PLUGIN_REL_PATH . '/l10n'
            );
        });

        if (!is_admin()) {
            add_action(hook_name: 'init', callback: [$this, 'enqueueCss']);

            /**
             * Add shortcode to WordPress
             */
            add_shortcode(tag: 'dl', callback: [$this, 'addShortcode']);
        }
    }


    /**
     * Enqueue CSS
     *
     * @return void
     * @access public
     */
    public function enqueueCss(): void {
        $cssFile = sprintf(
            '/%1$s/%2$s/Assets/css/downloadbutton%3$s.css',
            PLUGINDIR,
            PLUGIN_REL_PATH,
            WP_DEBUG ? '' : '.min'
        );

        wp_enqueue_style(
            handle: 'download-button-shortcode-css',
            src: $cssFile,
            deps: false
        );
    }

    /**
     * Add the shortcode to WordPress
     *
     * @param array $atts The attributes passed to the shortcode
     * @return false|string The shortcode
     * @access public
     */
    public function addShortcode(array $atts): false|string {
        $attributes = $this->getAttributes($atts);
        $url = $attributes['url'];
        $title = $attributes['title'];
        $desc = $attributes['desc'];
        $align = $attributes['align'] ?: 'center';
        $target = $attributes['target'] ? 'target="_' . $attributes['target'] . '"' : '';
        $type = $attributes['type'] ?: $this->getType($url);

        if (!$url) {
            return false;
        }

        $typeClass = 'class="download-item download-item-type-' . sanitize_title($type) . '"';

        return '<div class="download-button-shortcode button-download align' . $align . '">
                    <a ' . $typeClass . ' href="' . $url . '" ' . $target . '>
                        <span>
                            <span class="download-title">' . $title . '</span><br />
                            <em class="download-description">' . $desc . '</em>
                        </span>
                    </a>
                </div>';
    }

    /**
     * Get the attributes for the shortcode
     *
     * @param array $shortcodeAtts The attributes passed to the shortcode
     * @return array The attributes for the shortcode
     * @access private
     */
    private function getAttributes(array $shortcodeAtts): array {
        return shortcode_atts(
            pairs: [
                'type' => '',
                'url' => '',
                'title' => 'Download',
                'desc' => '',
                'align' => '',
                'target' => ''
            ],
            atts: $shortcodeAtts,
            shortcode: 'dl'
        );
    }

    /**
     * Get the type of the download
     *
     * @param string $url The URL of the download
     * @return string|null The type of the download
     * @access private
     */
    private function getType(string $url): ?string {
        $fileType = strrchr(haystack: $url, needle: '.');
        $types = [
            '.ez' => 'andrew-inset',
            '.hqx' => 'mac-binhex40',
            '.cpt' => 'mac-compactpro',
            '.doc' => 'ms-word document',
            '.bin' => 'octet-stream',
            '.dms' => 'octet-stream',
            '.lha' => 'octet-stream',
            '.lzh' => 'octet-stream',
            '.exe' => 'octet-stream',
            '.class' => 'octet-stream',
            '.so' => 'octet-stream',
            '.dll' => 'octet-stream',
            '.oda' => 'oda',
            '.pdf' => 'pdf',
            '.ai' => 'postscript',
            '.eps' => 'postscript',
            '.ps' => 'postscript',
            '.smi' => 'smil',
            '.smil' => 'smil',
            '.xls' => 'ms-excel',
            '.ppt' => 'ms-powerpoint',
            '.wbxml' => 'wap-wbxml',
            '.wmlc' => 'wap-wmlc',
            '.wmlsc' => 'wap-wmlscriptc',
            '.bcpio' => 'bcpio',
            '.vcd' => 'cdlink',
            '.pgn' => 'chess-pgn',
            '.cpio' => 'cpio',
            '.csh' => 'csh',
            '.dcr' => 'director',
            '.dir' => 'director',
            '.dxr' => 'director',
            '.dvi' => 'dvi',
            '.spl' => 'futuresplash',
            '.hdf' => 'hdf',
            '.js' => 'text javascript',
            '.skp' => 'koan',
            '.skd' => 'koan',
            '.skt' => 'koan',
            '.skm' => 'koan',
            '.latex' => 'latex',
            '.nc' => 'application x-netcdf',
            '.cdf' => 'application x-netcdf',
            '.sh' => 'sh',
            '.shar' => 'shar',
            '.swf' => 'shockwave-flash',
            '.sit' => 'stuffit',
            '.sv4cpio' => 'sv4cpio',
            '.sv4crc' => 'sv4crc',
            '.tcl' => 'tcl',
            '.tex' => 'tex',
            '.texinfo' => 'texinfo',
            '.texi' => 'texinfo',
            '.t' => 'troff',
            '.tr' => 'troff',
            '.roff' => 'troff',
            '.man' => 'troff-man',
            '.me' => 'troff-me',
            '.ms' => 'troff-ms',
            '.ustar' => 'ustar',
            '.src' => 'wais-source',
            '.xhtml' => 'xhtml-xml',
            '.xht' => 'xhtml-xml',
            '.7z' => 'archive sevenzip',
            '.zip' => 'archive zip',
            '.arj' => 'archive arj',
            '.rar' => 'archive rar',
            '.ace' => 'archive ace',
            '.tar' => 'archive tar',
            '.gtar' => 'archive gtar',
            '.gz' => 'archive gzip',
            '.bzip' => 'archive bzip',
            '.bzip2' => 'archive bzip',
            '.iso' => 'archive iso-image',
            '.au' => 'audio basic',
            '.snd' => 'audio basic',
            '.mid' => 'audio midi',
            '.midi' => 'audio midi',
            '.kar' => 'audio midi',
            '.mpga' => 'audio mpeg',
            '.mp2' => 'audio mpeg',
            '.mp3' => 'audio mpeg',
            '.aif' => 'audio aiff',
            '.aiff' => 'audio aiff',
            '.aifc' => 'audio aiff',
            '.m3u' => 'audio mpegurl',
            '.ram' => 'audio realaudio',
            '.rm' => 'audio realaudio',
            '.ra' => 'audio realaudio',
            '.rpm' => 'audio ealaudio-plugin',
            '.wav' => 'audio wav',
            '.pdb' => 'chemical pdb',
            '.xyz' => 'chemical xyz',
            '.bmp' => 'image bmp',
            '.gif' => 'image gif',
            '.ief' => 'image ief',
            '.jpeg' => 'image jpeg',
            '.jpg' => 'image jpeg',
            '.jpe' => 'image jpeg',
            '.png' => 'image png',
            '.tiff' => 'image tiff',
            '.tif' => 'image tiff',
            '.djvu' => 'image vnd-djvu',
            '.djv' => 'image vnd-djvu',
            '.wbmp' => 'image wap-wbmp',
            '.ras' => 'image cmu-raster',
            '.pnm' => 'image portable-anymap',
            '.pbm' => 'image portable-bitmap',
            '.pgm' => 'image portable-graymap',
            '.ppm' => 'image portable-pixmap',
            '.rgb' => 'image rgb',
            '.xbm' => 'image xbitmap',
            '.xpm' => 'image xpixmap',
            '.xwd' => 'image xwindowdump',
            '.igs' => 'model iges',
            '.iges' => 'model iges',
            '.msh' => 'model mesh',
            '.mesh' => 'model mesh',
            '.silo' => 'model mesh',
            '.wrl' => 'model vrml',
            '.vrml' => 'model vrml',
            '.css' => 'text css',
            '.htm' => 'text html',
            '.html' => 'text html',
            '.asc' => 'text plain',
            '.txt' => 'text plain',
            '.rtx' => 'text richtext',
            '.rtf' => 'text rtf',
            '.sgm' => 'text sgml',
            '.sgml' => 'text sgml',
            '.tsv' => 'text tab-separated-values',
            '.wml' => 'text vnd-wap-wml',
            '.wmls' => 'text vnd-wap-wmlscript',
            '.etx' => 'text setext',
            '.xsl' => 'text xml',
            '.xml' => 'text xml',
            '.mpeg' => 'video mpeg',
            '.mpg' => 'video mpeg',
            '.mpe' => 'video mpeg',
            '.qt' => 'video quicktime',
            '.mov' => 'video quicktime',
            '.mxu' => 'video vnd-mpegurl',
            '.avi' => 'video msvideo',
            '.movie' => 'video sgi-movie',
            '.asf' => 'video ms-asf',
            '.asx' => 'video ms-asf',
            '.wm' => 'video ms-wmv',
            '.wmv' => 'video ms-wmv',
            '.wvx' => 'video ms-wvx',
            '.ice' => 'conference cooltalk'
        ];

        return $types[$fileType] ?? null;
    }
}
