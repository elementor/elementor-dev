# Elementor Beta (Developer Edition) Docs 

The module "developer-edition" creates 2 instances of the classes `Core_Version_Control` and `Pro_Version_Control`, the main idea behind those classes is to interrupt the WordPress update mechanism and make sure that the future updates of "Elementor" and "Elementor Pro" comes from the "dev" channel.

There are a few differences between Elementor and Elementor Pro updates channel which will be described next.

### Elementor 

The class `Core_Version_Control`, which responsible for Elementor updates, removes all the additional filters of `pre_set_site_transient_update_plugins` that controls the updates and registers a new filter that sends an API request to the wordpress.org plugin info endpoint, it gets all Elementor versions and checks which one of them is the last ***dev*** version of Elementor.

### Elementor Pro

For the Elementor pro updates, the class `Pro_Version_Control` interrupts the HTTP request that was being send to Elementor servers (to check if there is a new version to update) and adds the `dev` param to inform the Elementor server that the current installation subscribed to the ***dev*** channel.

### Conclusion

The main difference between the 2 approaches is that in the Core version of Elementor the logic of which is the right version for the update determined in the plugin itself, while in the Pro version of Elementor, the remote server has the responsibility to deliver the right version.

Elementor Beta (Developer Edition) also adds for some UI changes which makes the plugin look a bit different from the stable version of Elementor.
