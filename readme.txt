This is the BlankSlate Theme as customized by Colin McParland (Tiny Bird Web Solutions). It's intended to curb the use of bulky Wordpress plugins and practices.

Things I added/changed:

Added package.json file to be used with NPM. Includes gulp, gulp-ruby-sass and clean-css.
Added gulpfile.js to run scss compile and css minify tasks
Removed some markdown from header.php, footer.php, index.php
Added a js folder and enqueued all the scripts
Included Slick Slider to avoid using bulky Wordpress slider plugins
Included a copy of WooCommerce templates for customization if the site includes e-commerce
Installation

Install npm on your server.
Download and install a blank copy of Wordpress on your server.
Navigate to your Wordpress theme directory and install the theme
wget http://tinybird.ca/tinybird-blankslate.zip
Extract the included themes into your theme directory
tar xfz tinybird-blankslate.zip
mv tinybird-blankslate/* .
rm tinybird-blankslate.zip
rm -r tinybird-blankslate
Run npm install to install the NPM dependencies
And that's pretty much it!

Useage

Once you have everything installed, you get use gulp to run your CSS tasks.

Running gulp will initiate the SCSS compiler task, which watches the style.scss file.

Running gulp cssmin will minify the generated style.css file.

Credits

Huge shout out to TidyThemes for creating the BlankSlate theme, which is awesome and extremely useful for building out non-bloated, customized Wordpress sites. Theme is used under license.