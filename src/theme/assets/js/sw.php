<?php
 require('../../../../../wp-load.php');

  header('Service-Worker-Allowed: /');
  header('Content-Type: application/javascript');
?>
var IondigitalSW = {templateUrl: "<?php echo get_template_directory_uri(); ?>"};
<?php
  readfile('_sw.min.js');
?>
