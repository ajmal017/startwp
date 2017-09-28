# Copyright 2017 Google Inc. All Rights Reserved.
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#     http://www.apache.org/licenses/LICENSE-2.0
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
FROM wordpress:4.8-php7.0-apache

# Gotta fix HTTPS >.>
RUN echo "<? if (\$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') { \$_SERVER['HTTPS'] = 'on'; }?>" >> /tmp/file && \
    cat /usr/src/wordpress/wp-config-sample.php >> /tmp/file && \
    echo " define('WP_DEBUG', true);" >> /tmp/file && \
    cp /tmp/file /usr/src/wordpress/wp-config-sample.php
COPY dist/theme /var/www/html/wp-content/themes/bitcoin
RUN mkdir /var/www/html/wp-content/themes/bitcoin_dev
COPY dist/pwp-lazy-image-plugin /var/www/html/wp-content/plugins/pwp-lazy-image
VOLUME /var/www/html/wp-content/themes/bitcoin_dev
VOLUME /var/www/html/wp-content/plugins/pwp-lazy-image-plugin
