<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
?>
<div class="vc_column-offset" data-column-offset="true">
    <input name="<?php echo esc_attr( $settings['param_name'] ) ?>"
           class="wpb_vc_param_value <?php echo esc_attr( $settings['param_name'] ) ?> <?php echo esc_attr( $settings['type'] ) ?>_field_value" type="hidden" value="<?php echo esc_attr( $value ) ?>"/>

    <table class="vc_table vc_column-offset-table">
        <tr>
            <th>
                <?php esc_html_e( 'Device', 'bitstarter' ) ?>
            </th>
            <?php foreach ($param->responsive_params as $key => $value):?>
                <th>
                    <?php echo (isset($value['heading'])?$value['heading']:ucfirst($key)); ?>
                </th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ( $sizes as $size_key => $size_name ) :  ?>
            <tr class="vc_size-<?php echo $size_key ?>">
                <td class="vc_screen-size vc_screen-size-<?php echo esc_attr($size_key) ?>">
                    <span class="vc_icon" title="<?php echo esc_attr($size_name) ?>"></span>
                </td>
                <?php echo  $param->getSizeParams( $size_key ); ?>
            </tr>
        <?php endforeach ?>
    </table>
</div>
