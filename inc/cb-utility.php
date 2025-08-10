<?php
/**
 * Utility functions and shortcodes.
 *
 * @package cb-statman2025
 */

/**
 * Parse and format phone number.
 *
 * @param string $phone The phone number to parse.
 * @return string The formatted phone number.
 */
function parse_phone( $phone ) {
    $phone = preg_replace( '/\s+/', '', $phone );
    $phone = preg_replace( '/\(0\)/', '', $phone );
    $phone = preg_replace( '/[\(\)\.]/', '', $phone );
    $phone = preg_replace( '/-/', '', $phone );
    $phone = preg_replace( '/^0/', '+44', $phone );
    return $phone;
}

/**
 * Split lines by adding extra line breaks.
 *
 * @param string $content The content to process.
 * @return string The processed content.
 */
function split_lines( $content ) {
    $content = preg_replace( '/<br \/>/', '<br>&nbsp;<br>', $content );
    return $content;
}

add_shortcode(
    'contact_address',
    function () {
		$output = get_field( 'contact_address', 'option' );
		return $output;
	}
);

add_shortcode(
    'contact_phone',
    function () {
		if ( get_field( 'contact_phone', 'option' ) ) {
			return '<a href="tel:' . parse_phone( get_field( 'contact_phone', 'option' ) ) . '">' . get_field( 'contact_phone', 'option' ) . '</a>';
		}
	}
);
add_shortcode(
    'contact_email',
    function () {
		if ( get_field( 'contact_email', 'option' ) ) {
			return '<a href="mailto:' . esc_attr( antispambot( get_field( 'contact_email', 'option' ) ) ) . '">' . get_field( 'contact_email', 'option' ) . '</a>';
		}
	}
);
add_shortcode(
    'contact_email_icon',
    function () {
		if ( get_field( 'contact_email', 'option' ) ) {
			return '<a href="mailto:' . esc_attr( antispambot( get_field( 'contact_email', 'option' ) ) ) . '"><i class="fas fa-envelope"></i></a>';
		}
	}
);
/**
 * Generates a social icon shortcode based on the provided type.
 *
 * @param array $atts Attributes passed to the shortcode.
 * @return string The HTML for the social icon or an empty string if the type is invalid.
 */
function social_icon_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'type' => '' ), $atts );
    if ( ! $atts['type'] ) {
        return '';
    }

    $social = get_field( 'socials', 'option' );
	$urls   = array(
        'facebook'    => $social['facebook_url'] ?? '',
        'instagram'   => $social['instagram_url'] ?? '',
        'x-twitter'   => $social['twitter_url'] ?? '',
        'pinterest'   => $social['pinterest_url'] ?? '',
        'youtube'     => $social['youtube_url'] ?? '',
        'linkedin-in' => $social['linkedin_url'] ?? '',
	);

    if ( ! isset( $urls[ $atts['type'] ] ) || empty( $urls[ $atts['type'] ] ) ) {
        return '';
    }

    $url  = esc_url( $urls[ $atts['type'] ] );
    $icon = esc_attr( $atts['type'] );

    return '<a href="' . $url . '" target="_blank" rel="nofollow noopener noreferrer"><i class="fa-brands fa-' . $icon . '"></i></a>';
}

// Register individual social icon shortcodes.
$social_types = array( 'facebook', 'instagram', 'twitter', 'pinterest', 'youtube', 'linkedin' );
foreach ( $social_types as $social_type ) {
    add_shortcode(
		'social_' . $social_type . '_icon',
		function () use ( $social_type ) {
            return social_icon_shortcode( array( 'type' => $social_type ) );
    	}
	);
}

// Generate a single shortcode to output all social icons.
add_shortcode(
	'social_icons',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'class' => '',
			),
			$atts,
			'social_icons'
		);

		$social = get_field( 'social', 'option' );
		if ( ! $social ) {
			return '';
		}

		$icons      = array();
		$social_map = array(
			'twitter'   => 'x-twitter',
			'facebook'  => 'facebook-f',
			'instagram' => 'instagram',
			'pinterest' => 'pinterest',
			'youtube'   => 'youtube',
			'linkedin'  => 'linkedin-in',
		);

		foreach ( $social_map as $key => $icon ) {
			if ( ! empty( $social[ $key . '_url' ] ) ) {
				$url     = esc_url( $social[ $key . '_url' ] );
				$icons[] = '<a href="' . $url . '" target="_blank" rel="nofollow noopener noreferrer"><i class="fa-brands fa-' . $icon . '"></i></a>';
			}
		}

    	$class = esc_attr( trim( $atts['class'] ) );

	    return ! empty( $icons ) ? '<div class="social-icons ' . $class . '">' . implode( ' ', $icons ) . '</div>' : '';
	}
);


/**
 * Grab the specified data like Thumbnail URL of a publicly embeddable video hosted on Vimeo.
 *
 * @param  str $video_id The ID of a Vimeo video.
 * @param  str $data      Video data to be fetched.
 * @return str            The specified data
 */
function get_vimeo_data_from_id( $video_id, $data ) {
    // Width can be 100, 200, 295, 640, 960 or 1280.
    $request = wp_remote_get( 'https://vimeo.com/api/oembed.json?url=https://vimeo.com/' . $video_id . '&width=960' );

    $response = wp_remote_retrieve_body( $request );

    $video_array = json_decode( $response, true );

    return $video_array[ $data ];
}

/**
 * Add custom Gutenberg admin styles.
 */
function cb_gutenberg_admin_styles() {
    echo '
        <style>
            /* Main column width */
            .wp-block {
                max-width: 1040px;
            }
 
            /* Width of "wide" blocks */
            .wp-block[data-align="wide"] {
                max-width: 1080px;
            }
 
            /* Width of "full-wide" blocks */
            .wp-block[data-align="full"] {
                max-width: none;
            }
            .block-editor-page #wpwrap {
                overflow-y: auto !important;
            }

            @media (min-width:992px) {
                .acf-block-component .acf-checkbox-list {
                    columns: 3;
                }
            }
            @media (min-width:1200px) {
                .acf-block-component .acf-checkbox-list {
                    columns: 4;
                }
            }
        </style>
    ';
}
add_action( 'admin_head', 'cb_gutenberg_admin_styles' );


// Disable full-screen editor view by default.
if ( is_admin() ) {
	/**
	 * Disable editor fullscreen by default.
	 */
	function cb_disable_editor_fullscreen_by_default() {
		$script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
		wp_add_inline_script( 'wp-blocks', $script );
	}
	add_action( 'enqueue_block_editor_assets', 'cb_disable_editor_fullscreen_by_default' );
}



// Change textarea rows to 4 instead of 10.
add_filter(
	'gform_field_content',
	function ( $field_content, $field ) {
		if ( 'textarea' === $field->type ) {
			return str_replace( "rows='10'", "rows='4'", $field_content );
		}
		return $field_content;
	},
	10,
	2
);

/**
 * Get the top ancestor ID of the current post.
 *
 * @return int The top ancestor post ID.
 */
function get_the_top_ancestor_id() {
    global $post;
    if ( $post->post_parent ) {
        $ancestors = array_reverse( get_post_ancestors( $post->ID ) );
        return $ancestors[0];
    } else {
        return $post->ID;
    }
}

/**
 * JSON encode a string with custom escaping.
 *
 * @param string $content The string to encode.
 * @return string The JSON encoded string.
 */
function cb_json_encode( $content ) {
    $escapers     = array( '\\', '/', '"', "\n", "\r", "\t", "\x08", "\x0c" );
    $replacements = array( '\\\\', '\\/', '\\"', "\\n", "\\r", "\\t", "\\f", "\\b" );
    $result       = str_replace( $escapers, $replacements, $content );
    $result       = wp_json_encode( $result );
    return $result;
}

/**
 * Convert time string to ISO 8601 duration format.
 *
 * @param string $time_string The time string to convert.
 * @return string The ISO 8601 duration format.
 */
function cb_time_to_8601( $time_string ) {
    $time   = explode( ':', $time_string );
    $output = 'PT' . $time[0] . 'H' . $time[1] . 'M' . $time[2] . 'S';
    return $output;
}

/**
 * Debug dump function for development.
 *
 * @param mixed $variable The variable to dump.
 */
function cbdump( $variable ) {
    echo '<pre>';
    print_r( $variable ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
    echo '</pre>';
}

/**
 * Slugify text for URL-safe strings.
 *
 * @param string $text The text to slugify.
 * @param string $divider The divider character to use.
 * @return string The slugified text.
 */
function cbslugify( $text, string $divider = '-' ) {
    // Replace non letter or digits by divider.
    $text = preg_replace( '~[^\pL\d]+~u', $divider, $text );

    // Transliterate.
    $text = iconv( 'utf-8', 'us-ascii//TRANSLIT', $text );

    // Remove unwanted characters.
    $text = preg_replace( '~[^-\w]+~', '', $text );

    // Trim.
    $text = trim( $text, $divider );

    // Remove duplicate divider.
    $text = preg_replace( '~-+~', $divider, $text );

    // Lowercase.
    $text = strtolower( $text );

    if ( empty( $text ) ) {
        return 'n-a';
    }

    return $text;
}

/**
 * Generate a random string of specified length.
 *
 * @param int    $length The length of the random string.
 * @param string $keyspace The characters to use for the random string.
 * @return string The random string.
 * @throws \RangeException If length is less than 1.
 */
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ( $length < 1 ) {
        throw new \RangeException( 'Length must be a positive integer' );
    }
    $pieces = array();
    $max    = mb_strlen( $keyspace, '8bit' ) - 1;
    for ( $i = 0; $i < $length; ++$i ) {
        $pieces[] = $keyspace[ random_int( 0, $max ) ];
    }
    return implode( '', $pieces );
}

/**
 * Generate social share buttons for a post.
 *
 * @param int $id The post ID.
 * @return string The HTML for social share buttons.
 */
function cb_social_share( $id ) {
    ob_start();
    $url = get_the_permalink( $id );

	?>
    <div class="text-larger text--yellow mb-5">
        <div class="h4 text-dark">Share</div>
        <a target='_blank' href='https://twitter.com/share?url=<?php echo esc_url( $url ); ?>' class="mr-2"><i class='fab fa-twitter'></i></a>
        <a target='_blank' href='http://www.linkedin.com/shareArticle?url=<?php echo esc_url( $url ); ?>' class="mr-2"><i class='fab fa-linkedin-in'></i></a>
        <a target='_blank' href='http://www.facebook.com/sharer.php?u=<?php echo esc_url( $url ); ?>'><i class='fab fa-facebook-f'></i></a>
    </div>
    <?php

    $out = ob_get_clean();
    return $out;
}

/**
 * Enable HSTS header for security.
 */
function enable_strict_transport_security_hsts_header() {
    header( 'Strict-Transport-Security: max-age=31536000' );
}
add_action( 'send_headers', 'enable_strict_transport_security_hsts_header' );

/**
 * Convert field content to an HTML list.
 *
 * @param string $field The field content to convert.
 * @return string The HTML list.
 */
function cb_list( $field ) {
    ob_start();
    $field   = strip_tags( $field, '<br />' );
    $bullets = preg_split( "/\r\n|\n|\r/", $field );
    foreach ( $bullets as $b ) {
        if ( '' === $b ) {
            continue;
        }
		?>
        <li><?php echo esc_html( $b ); ?></li>
		<?php
    }
    return ob_get_clean();
}

/**
 * Format bytes into human readable format.
 *
 * @param string $size The size in bytes.
 * @param int    $precision The decimal precision.
 * @return string The formatted bytes string.
 */
function format_bytes( $size, $precision = 2 ) {
    $base     = log( $size, 1024 );
    $suffixes = array( '', 'K', 'M', 'G', 'T' );

    return round( pow( 1024, $base - floor( $base ) ), $precision ) . ' ' . $suffixes[ floor( $base ) ];
}


/**
 * Get featured image with srcset.
 *
 * @param string $id The post ID.
 * @return string The img tag with srcset.
 */
function cb_featured_image( $id ) {
    $tag = get_the_post_thumbnail(
        $id,
        'full',
        array(
            'srcset' => wp_get_attachment_image_url( get_post_thumbnail_id(), 'medium' ) . ' 480w, ' .
                wp_get_attachment_image_url( get_post_thumbnail_id(), 'large' ) . ' 640w, ' .
                wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' ) . ' 960w',
        )
    );
    return $tag;
}

/**
 * Estimate reading time in minutes.
 *
 * @param string $content The content to analyze.
 * @param int    $words_per_minute Words per minute reading speed.
 * @param bool   $with_gutenberg Whether to parse Gutenberg blocks.
 * @param bool   $formatted Whether to return formatted output.
 * @return int|string The estimated reading time.
 */
function estimate_reading_time_in_minutes( $content = '', $words_per_minute = 300, $with_gutenberg = false, $formatted = false ) {
    // In case if content is build with Gutenberg parse blocks.
    if ( $with_gutenberg ) {
        $blocks       = parse_blocks( $content );
        $content_html = '';

        foreach ( $blocks as $block ) {
            $content_html .= render_block( $block );
        }

        $content = $content_html;
    }

    // Remove HTML tags from string.
    $content = wp_strip_all_tags( $content );

    // When content is empty return 0.
    if ( ! $content ) {
        return 0;
    }

    // Count words containing string.
    $words_count = str_word_count( $content );

    // Calculate time for read all words and round.
    $minutes = ceil( $words_count / $words_per_minute );

    if ( $formatted ) {
        $minutes = '<p class="reading">Estimated reading time ' . $minutes . ' ' . pluralise( $minutes, 'minute' ) . '</p>';
    }

    return $minutes;
}

/**
 * Pluralise a word based on quantity.
 *
 * @param int         $quantity The quantity to check.
 * @param string      $singular The singular form.
 * @param string|null $plural The plural form (optional).
 * @return string The pluralized word.
 */
function pluralise( $quantity, $singular, $plural = null ) {
    if ( 1 === $quantity || ! strlen( $singular ) ) {
		return $singular;
    }
    if ( null !== $plural ) {
		return $plural;
    }

    $last_letter = strtolower( $singular[ strlen( $singular ) - 1 ] );
    switch ( $last_letter ) {
        case 'y':
            return substr( $singular, 0, -1 ) . 'ies';
        case 's':
            return $singular . 'es';
        default:
            return $singular . 's';
    }
}

/**
 * Get all block names from post content.
 *
 * @param int $id The post ID.
 * @return array Array of block names.
 */
function get_all_block_names_from_content( $id ) {
    // Parse blocks from the content.
    $content     = get_post_field( 'post_content', $id );
    $blocks      = parse_blocks( $content );
    $block_names = array();

    // Recursively find all block names.
    foreach ( $blocks as $block ) {
        if ( isset( $block['blockName'] ) && ! empty( $block['blockName'] ) ) {
            $block_names[] = $block['blockName'];
        }
        if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) ) {
            $inner_block_names = get_all_block_names_from_content( serialize_blocks( $block['innerBlocks'] ) );
            $block_names       = array_merge( $block_names, $inner_block_names );
        }
    }

    // Remove duplicates and reindex.
    return array_values( array_unique( $block_names ) );
}

/**
 * Display pages in a hierarchical list.
 *
 * @param int $parent_id The parent page ID.
 * @return string The HTML for the hierarchical page list.
 */
function display_page_hierarchy( $parent_id = 0 ) {

    $posts_page_id = get_option( 'page_for_posts' );

    // Get the pages with the specified parent, sorted by title.
    $args = array(
        'post_type'   => 'page',
        'post_status' => 'publish',
        'parent'      => $parent_id,
        'sort_column' => 'post_title',  // Sort by post title for alphabetical order.
        'sort_order'  => 'ASC',          // Sort ascending (A-Z).
        'exclude'     => $posts_page_id,     // Exclude the posts page (blog index).
    );

    $pages = get_pages( $args );

    $output = '';

    if ( ! empty( $pages ) ) {
        $output .= '<ul>';
        foreach ( $pages as $page ) {
            // Check index status.
            $noindex = get_post_meta( $page->ID, '_yoast_wpseo_meta-robots-noindex', true );

            if ( '1' !== $noindex ) {
                $output .= '<li><a href="' . get_permalink( $page->ID ) . '">' . $page->post_title . '</a>';

                // Recursively display child pages, also sorted by title.
                $output .= display_page_hierarchy( $page->ID ); // Get nested child pages.

                $output .= '</li>';
            }
        }
        $output .= '</ul>';
    }

    return $output;
}

/**
 * Register the shortcode to display the hierarchical page list.
 *
 * @return string The buffered content.
 */
function register_page_list_shortcode() {
    // Start output buffering.
    ob_start();

    // Display the hierarchical list.
    echo wp_kses_post( display_page_hierarchy() );

    // Return the buffered content.
    return ob_get_clean();
}
add_shortcode( 'page_list', 'register_page_list_shortcode' );
