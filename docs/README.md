# Elementor Beta (Developer Edition) Docs 

The module "developer-edition" creates 2 instances of the classes "Core_Version_Control" and "Pro_Version_Control", the main idea behind those classes is to interrupt the WordPress update machine and make sure that the future updates of "Elementor" and "Elementor Pro" comes from the "dev" channel.

There are few difference between "Elementor" and "Elementor Pro" updates channel which will be described next.

### "Elementor" core 

The core updates use the "pre_set_site_transient_update_plugins" filter 

