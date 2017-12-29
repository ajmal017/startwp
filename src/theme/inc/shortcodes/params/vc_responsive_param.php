<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * @property mixed data
 */
class ForIT_Responsive_Param {
    /**
     * @var array
     */
    protected $settings = array();
    /**
     * @var string
     */
    protected $value = '';
    /**
     * @var array
     */
    protected $size_types = array(
        'lg' => 'Large',
        'md' => 'Medium',
        'sm' => 'Small',
        'xs' => 'Extra small',
    );
    /**
     * @var array
     */
    protected $responsive_param_list = array();

    /**
     * @param $settings
     * @param $value
     */
    public function __construct( $settings, $value ) {
        $this->settings = $settings;
        $this->value = $value;

        $this->responsive_params = (isset($this->settings['params']) && is_array($this->settings['params'])?$this->settings['params']:array());
    }

    /**
     * @return string
     */
    public function render() {
        ob_start();
        forit_include_template( FORIT_INCLUDES . '/shortcodes/templates/vc_responsive_param.tpl.php', array(
            'settings' => $this->settings,
            'value' => $this->value,
            'data' => $this->valueData(),
            'sizes' => $this->size_types,
            'param' => $this,
        ) );

        return ob_get_clean();
    }

    /**
     * @return array|mixed
     */
    public function valueData() {
        if ( ! isset( $this->data ) ) {
            $this->data = json_decode($this->value);
        }

        return $this->data;
    }

    /**
     * @param $size
     *
     * @return string
     */
    public function getSizeParams( $size_key ) {

        $empty_label = 'xs' === $size_key ? '' : esc_html__( 'Inherit from smaller', 'forit' );
        $output = '';

        foreach ($this->responsive_params as $param_key => $param) {
            $param['param_name'] = 'responsive_item_' . $param_key;

            if($size_key != 'xs' && $param['type'] == 'dropdown')
                array_unshift( $param['value'], array('forit_inherit' => $empty_label));

            $param_value = (isset($this->data->{$param_key}) && isset($this->data->{$param_key}->{$size_key}))?$this->data->{$param_key}->{$size_key}:'';


            $output .= '<td class="vc_screen-size vc_screen-size-'.$size_key.'" data-size="'.$size_key.'" data-name="">';
                if($param['type'] == 'dropdown')
                    $output .= vc_dropdown_form_field($param, $param_value);
                if($param['type'] == 'size') {
                    $output .= '<input placeholder="'.$empty_label.'" name="' . $param['param_name']
                    . '" class="wpb_vc_param_value wpb-textinput '
                    . $param['param_name'] . ' ' . $param['type']
                    . '" type="text" value="' . htmlspecialchars( $param_value ) . '" data-field-type="size"/>';
                }

            if($param['type'] == 'number') {
                $output .= '<input placeholder="'.$empty_label.'" name="' . $param['param_name']
                    . '" class="wpb_vc_param_value wpb-textinput '
                    . $param['param_name'] . ' ' . $param['type']
                    . '" type="number" value="' . htmlspecialchars( $param_value ) . '"/>';
            }

            $output .= '</td>';
        }

        return $output;
    }
}

/**
 * @param $settings
 * @param $value
 *
 * @return string
 */
function forit_responsive_param_form_field( $settings, $value ) {
    $responsive_param = new ForIT_Responsive_Param( $settings, $value );

    return $responsive_param->render();
}

/**
 * @param $responsive_param
 * @param $width
 *
 * @return mixed|string
 */

WpbakeryShortcodeParams::addField( 'forit_responsive_param', 'forit_responsive_param_form_field', FORIT_SCRIPTS . '/admin/vc_params/forit_responsive_param.js' );

