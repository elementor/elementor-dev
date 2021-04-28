# Elementor Beta (Developer Edition) Docs 

The module "developer-edition" creates 2 instances of the classes `Core_Version_Control` and Pro_Version_Control`, the main idea behind those classes is to interrupt the WordPress update machine and make sure the future updates of "Elementor" and "Elementor Pro" comes from the "dev" channel.

There are few differences between Elementor and Elementor Pro updates channel which will be described next.

### Elementor 

The class `Core_Version_Control` which responsible for Elementor updates removes all the additional filters of 'pre_set_site_transient_update_plugins' that controls the updates and register a new filter that sends an API request to the wordpress.org plugin info endpoint, get the results of whole Elementor versions and check which one of them is the last ***dev*** version of Elementor.

### Elementor Pro

For the Elementor pro updates, the class "Pro_Version_Control" interrupt the HTTP request that sends to Elementor servers (to check if there is a new version to update) and adds the "dev" param to inform the Elementor servers that the current installation is subscribed to the ***dev*** channel.

### Conclution

The main difference between the 2 approaches is that in the core version of Elementor the logic of which is the right version for the update is calculated in the "dev" plugin. in the pro version of Elementor, the remote server has the responsibility to deliver the right version.

Elementor Beta (Developer Edition) has responsibility for some UI changes that make the plugin look bit different from the stable version of Elementor.
