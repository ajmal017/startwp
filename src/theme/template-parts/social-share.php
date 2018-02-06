
<?php  

 $svg_path = 'assets/svg/';

?>
<div class="entry-share">
    <ul class="entry-share__links">
        <li>
            <a href="#" data-share="facebook">
                <i class="bitstarter__icon bitstarter__icon--opacity5"><?php file_get_contents(locate_template( $svg_path . 'fb-icon.php', true, false )); ?></i>
            </a>
        </li>
        <li>
            <a href="#" data-share="twitter">
                <i class="bitstarter__icon bitstarter__icon--opacity5"><?php file_get_contents(locate_template( $svg_path . 'tw-icon.php', true, false )); ?></i>
            </a>
        </li>
        <li>
            <a href="#" data-share="google">
                <i class="bitstarter__icon bitstarter__icon--opacity5"><?php file_get_contents(locate_template( $svg_path . 'goog-icon.php', true, false )); ?></i>
            </a>
        </li>
        <li>
            <a href="#" data-share="pinterest">
                <i class="bitstarter__icon bitstarter__icon--opacity5"><?php file_get_contents(locate_template( $svg_path . 'pntr-icon.php', true, false )); ?></i>
            </a>
        </li>
    </ul>
</div>