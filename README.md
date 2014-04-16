NickelTracker
==============

A very basic expense tracker and envelope budgeter built as a Symfony2 app

## Installation
### 1. Preparations ###
While this script takes care of most of the installation procedure, you still need to type some commands manually to get it started.

Install git and prepare the installation directory:

	sudo apt-get install -y git
	sudo mkdir /var/www
	sudo chmod 777 -R /var/www
	cd /var/www

Now pull the most recent version of the installation script and go into the cloned folder:

	git clone https://github.com/FuentesWorks/NickelTracker
	cd NickelTracker


### 2. Configure your VirtualHost ###
Modify the file `vhost/NickelTracker` by changing the following lines to your domain, subdomain or public IP address:

```
nano vhost/NickelTracker
```

And edit these two lines:

```
ServerName      www.mydomain.com
ServerAdmin     me@mydomain.com
```

Save the file by pressing `Ctrl+X` and then `Y`.

### 3. Execute the script ###
To run the script, you must first set it as executable:

	chmod +x install.sh

Finally, enter:

	./install.sh

### 4. Configuring Symfony ###
The installation should only ask for your root password once to download packages (via `apt-get`) and
perform some slight changes to your system configuration (enable Apache2's VirtualHosts).

Once it begins installing Symfony, it'll stop to ask you the values to save into your `parameters.yml` file
for Symfony. You should have your database connection parameters ready at hand, the application was developed
and tested only with MySQL, but it should work with any other DB engines that are supported by the PDO driver.

The installation will ask you for your `nickeltracker_username` and `nickeltracker_password` which will be
your system credentials for NickelTracker.

### 5. Cleaning up ###
It is recommended to run the update script right after the `install.sh` script finishes, as the update process will
clear some cache files that might have been created while installing.

To update, run:
```
./update.sh
```
