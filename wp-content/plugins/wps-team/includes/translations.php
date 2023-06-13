<?php

namespace WPSpeedo_Team;

if ( ! defined('ABSPATH') ) exit;

class Translations {

    public $strings = [];

    public function __construct() {
		$this->load();
	}

    public function load() {
        $this->strings = [
            'desktop' => __('Desktop', 'wpspeedo-team'),
            'tablet' => __('Tablet', 'wpspeedo-team'),
            'small-tablet' => __('Small Tablet', 'wpspeedo-team'),
            'mobile' => __('Mobile', 'wpspeedo-team'),
            'something-wrong' => __('Something went wrong!', 'wpspeedo-team'),
            'custom-order' => __('Custom Order', 'wpspeedo-team'),
            'members' => __( 'Members', 'wpspeedo-team' ),
            'member' => __( 'Member', 'wpspeedo-team' ),
            'groups' => __( 'Groups', 'wpspeedo-team' ),
            'group' => __( 'Group', 'wpspeedo-team' ),
            'locations' => __( 'Locations', 'wpspeedo-team' ),
            'location' => __( 'Location', 'wpspeedo-team' ),
            'languages' => __( 'Languages', 'wpspeedo-team' ),
            'language' => __( 'Language', 'wpspeedo-team' ),
            'specialties' => __( 'Specialties', 'wpspeedo-team' ),
            'specialty' => __( 'Specialty', 'wpspeedo-team' ),
            'genders' => __( 'Genders', 'wpspeedo-team' ),
            'gender' => __( 'Gender', 'wpspeedo-team' ),
    
            'location-single-name' => __( 'Location Single Name', 'wpspeedo-team' ),
            'location-plural-name' => __( 'Location Plural Name', 'wpspeedo-team' ),
            'language-single-name' => __( 'Language Single Name', 'wpspeedo-team' ),
            'language-plural-name' => __( 'Language Plural Name', 'wpspeedo-team' ),
            'specialty-single-name' => __( 'Specialty Single Name', 'wpspeedo-team' ),
            'specialty-plural-name' => __( 'Specialty Plural Name', 'wpspeedo-team' ),
            'gender-single-name' => __( 'Gender Single Name', 'wpspeedo-team' ),
            'gender-plural-name' => __( 'Gender Plural Name', 'wpspeedo-team' ),
    
            'link-1-label' => __( 'Resume Link Label', 'wpspeedo-team' ),
            'link-2-label' => __( 'Hire Link Label', 'wpspeedo-team' ),
    
            'link-1-btn-text' => __( 'Resume Button Text', 'wpspeedo-team' ),
            'link-2-btn-text' => __( 'Hire Button Text', 'wpspeedo-team' ),

            'mobile-meta-label' => __( 'Mobile: Text', 'wpspeedo-team' ),
            'phone-meta-label' => __( 'Phone: Text', 'wpspeedo-team' ),
            'email-meta-label' => __( 'Email: Text', 'wpspeedo-team' ),
            'website-meta-label' => __( 'Website: Text', 'wpspeedo-team' ),
            'experience-meta-label' => __( 'Experience: Text', 'wpspeedo-team' ),
            'company-meta-label' => __( 'Company: Text', 'wpspeedo-team' ),
            'group-meta-label' => __( 'Group: Text', 'wpspeedo-team' ),
            'location-meta-label' => __( 'Location: Text', 'wpspeedo-team' ),
            'language-meta-label' => __( 'Language: Text', 'wpspeedo-team' ),
            'specialty-meta-label' => __( 'Specialty: Text', 'wpspeedo-team' ),
            'gender-meta-label' => __( 'Gender: Text', 'wpspeedo-team' ),
    
            'autoplay' => __( 'Autoplay', 'wpspeedo-team' ),
            'autoplay-delay' => __( 'Autoplay Delay', 'wpspeedo-team' ),
    
            'show-group-filter' => __( 'Show Group Filter', 'wpspeedo-team' ),
            'show-location-filter' => __( 'Show Location Filter', 'wpspeedo-team' ),
            'show-language-filter' => __( 'Show Language Filter', 'wpspeedo-team' ),
            'show-specialty-filter' => __( 'Show Specialty Filter', 'wpspeedo-team' ),
            'show-gender-filter' => __( 'Show Gender Filter', 'wpspeedo-team' ),
    
            'enable-multi-select' => __( 'Enable Multi Select', 'wpspeedo-team' ),
            'filter-animation-speed' => __( 'Filter Animation Speed', 'wpspeedo-team' ),
    
            'filter-inner-space' => __( 'Filter Inner Space', 'wpspeedo-team' ),
            'filters-bottom-space' => __( 'Filters Bottom Space', 'wpspeedo-team' ),
            'show-hide-filter-all' => __( 'Show/Hide Filter All', 'wpspeedo-team' ),
            'initial-group-filter' => __( 'Initial Group Filter', 'wpspeedo-team' ),
            'group-filter-all-text' => __( 'Group Filter All Text', 'wpspeedo-team' ),
            'location-filter-all-text' => __( 'Location Filter All Text', 'wpspeedo-team' ),
            'language-filter-all-text' => __( 'Language Filter All Text', 'wpspeedo-team' ),
            'specialty-filter-all-text' => __( 'Specialty Filter All Text', 'wpspeedo-team' ),
            'gender-filter-all-text' => __( 'Gender Filter All Text', 'wpspeedo-team' ),
    
            'include-by-location' => __( 'Include by Location', 'wpspeedo-team' ),
            'include-by-language' => __( 'Include by Language', 'wpspeedo-team' ),
            'include-by-specialty' => __( 'Include by Specialty', 'wpspeedo-team' ),
            'include-by-gender' => __( 'Include by Gender', 'wpspeedo-team' ),
    
            'exclude-by-location' => __( 'Exclude by Location', 'wpspeedo-team' ),
            'exclude-by-language' => __( 'Exclude by Language', 'wpspeedo-team' ),
            'exclude-by-specialty' => __( 'Exclude by Specialty', 'wpspeedo-team' ),
            'exclude-by-gender' => __( 'Exclude by Gender', 'wpspeedo-team' ),
    
            'padding' => __( 'Padding', 'wpspeedo-team' ),
            'border-radius' => __( 'Border Radius', 'wpspeedo-team' ),
            'title-spacing' => __( 'Title Spacing', 'wpspeedo-team' ),
            'designation-spacing' => __( 'Designation Spacing', 'wpspeedo-team' ),
            'desc-spacing' => __( 'Desc Spacing', 'wpspeedo-team' ),
            'devider-spacing' => __( 'Devider Spacing', 'wpspeedo-team' ),
            'social-icons-spacing' => __( 'Social Icons Spacing', 'wpspeedo-team' ),
            'meta-info-spacing' => __( 'Meta Info Spacing', 'wpspeedo-team' ),
    
            'resume-button-style' => __('Resume Button Style', 'wpspeedo-team'),
            'hire-button-style' => __('Hire Button Style', 'wpspeedo-team'),
    
            'filter-styling' => __('Filters Styling', 'wpspeedo-team'),
            'social-links-styling' => __('Social Links Styling', 'wpspeedo-team'),
    
            'typo-name' => __( 'Typo: Name', 'wpspeedo-team' ),
            'typo-designation' => __( 'Typo: Designation', 'wpspeedo-team' ),
            'typo-content' => __( 'Typo: Content', 'wpspeedo-team' ),
            'typo-meta' => __( 'Typo: Meta', 'wpspeedo-team' ),
    
            'container-background-color' => __( 'Background Color', 'wpspeedo-team' ),
            'container-custom-class' => __( 'Custom Class', 'wpspeedo-team' ),
            'container-padding' => __( 'Padding', 'wpspeedo-team' ),
            'container-z-index' => __( 'Z Index', 'wpspeedo-team' ),
            'container-border-radius' => __( 'Border Radius', 'wpspeedo-team' ),
    
            'this-is-pro' => __('This is Pro feature', 'wpspeedo-team'),
            'purchasing-premium' => __('If you love our work please support us by purchasing our Premium plugin.', 'wpspeedo-team'),
            'upgrade-to-pro' => __('Upgrade to Pro', 'wpspeedo-team'),
            'documentation' => __('Documentation', 'wpspeedo-team'),
            'save-settings' => __('Save Settings', 'wpspeedo-team'),
            'back' => __('Back', 'wpspeedo-team'),
            'save' => __('Save', 'wpspeedo-team'),
            'shortcode-copied' => __('Shortcode Copied', 'wpspeedo-team'),
            'copy-shortcode' => __('Copy Shortcode', 'wpspeedo-team'),
            'php-code-copied' => __('PHP Code Copied', 'wpspeedo-team'),
            'copy-php-code' => __('Copy PHP Code', 'wpspeedo-team'),
            'search-settings' => __('Search Settings', 'wpspeedo-team'),
            'shortcodes' => __('Shortcodes', 'wpspeedo-team'),
            'search-shortcode' => __('Search Shortcode', 'wpspeedo-team'),
            'type' => __('Type', 'wpspeedo-team'),
            'name' => __('Name', 'wpspeedo-team'),
            'shortcode' => __('Shortcode', 'wpspeedo-team'),
            'php-code' => __('PHP Code', 'wpspeedo-team'),
            'copied' => __('Copied', 'wpspeedo-team'),
            'copy' => __('Copy', 'wpspeedo-team'),
            'rename' => __('Rename', 'wpspeedo-team'),
            'edit' => __('Edit', 'wpspeedo-team'),
            'clone' => __('Clone', 'wpspeedo-team'),
            'no-shortcode-found' => __('No shortcode found.', 'wpspeedo-team'),
            'create-new-shortcode' => __('Create New Shortcode', 'wpspeedo-team'),
            'shortcode-name' => __('Shortcode Name', 'wpspeedo-team'),
            'create-shortcode' => __('Create Shortcode', 'wpspeedo-team'),
            'rename-shortcode' => __('Rename Shortcode', 'wpspeedo-team'),
            'add-shortcode-name' => __('Please add a shortcode name', 'wpspeedo-team'),
            'are-you-sure' => __('Are you sure?', 'wpspeedo-team'),
            'want-to-delete-shortcode' => __('Do you want to delete (%s) shortcode?', 'wpspeedo-team'),
            'cancel' => __('Cancel', 'wpspeedo-team'),
            'delete' => __('Delete', 'wpspeedo-team'),
            'got-questions' => __('Got questions?', 'wpspeedo-team'),
            'got-questions-desc' => __('Please submit a ticket if you face any issue or if you have any question.', 'wpspeedo-team'),
            'submit-ticket' => __('Submit Your Ticket', 'wpspeedo-team'),
            'shortcodes' => __('Shortcodes', 'wpspeedo-team'),
            'settings' => __('Settings', 'wpspeedo-team'),
            'order' => __('Order', 'wpspeedo-team'),
            'go-premium' => __('Go Premium', 'wpspeedo-team'),
            'demo-import' => __('Demo import', 'wpspeedo-team'),
            'purge-cache' => __('Purge Cache', 'wpspeedo-team'),
            'tools' => __('Tools', 'wpspeedo-team'),
        ];
    }

    public function get( $key = '' ) {
		if ( empty($key) ) return $this->strings;
		if ( array_key_exists( $key, $this->strings ) ) return $this->strings[ $key ];
        return '';
    }

}