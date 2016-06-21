# ACF Tweet Field

An ACF custom field that gets a single Tweet Data from the twitter API from the tweet ID.

-----------------------

### Description

An ACF custom field that gets a single Tweet Data from the twitter API from the tweet ID.

### Compatibility

This ACF field type is compatible with:
* ACF 5

### Installation

1. Copy the `acf-tweet` folder into your `wp-content/plugins` folder
2. Activate the Tweet plugin via the plugins admin page
3. Create a new field via ACF and select the Tweet type
4. Please refer to the description for more info regarding the field type settings

## Docker setup for Plugin Dev

This plugin also comes with a `docker-compose.yml` file which allows you to run a 
clean instance of Wordpress with ACF Pro 5 and `acf-field-tweet` as plugins for
development purposes. Because this plugin requires ACF PRO you need to download 
the plugin on your own and link to it in the docker-compose file.

1. Edit `docker-compose.yml` and modify the Volumes path to your own 
`advanced-custom-fields-pro` path.
2. run docker-compose up in this plugin folder, and follow the instruction to
create a new Wordpress Install.
3. Go in the Plugins admin panel and activate both *ACF PRO* and *ACF Field Tweet*

### Changelog
#### 1.0.1
* Added Docker dev environment setup.

#### 1.0.0
* Initial Release.
