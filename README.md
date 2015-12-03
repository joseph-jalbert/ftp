# For The People

## Pre-requisites (you may have some of these installed already)

* Vagrant at [vagrantup.com](http://vagrantup.com)
* Vagrant Host Manager plugin, install directions at [its project page](https://github.com/smdahlen/vagrant-hostmanager).
* Virtualbox at [virtualbox.com](http://virtualbox.com)
* Composer instructions for downloading at [getcomposer.org](http://getcomposer.org)
* Node and NPM	using [homebrew](http://brew.sh/) (preferred) or from [nodejs.org](https://nodejs.org/en/download/)
* Bower instructions for downloading at [bower.io](http://bower.io)
* Grunt CLI instructions at [its project page](https://github.com/gruntjs/grunt-cli)
* [terminus](https://github.com/pantheon-systems/cli) which is Pantheon's CLI

## Setting up to develop locally

*  Clone this repository
*  Copy `.env.example` to `.env` and set variables accordingly
*  Run vagrant up, this may ask you for your password and take a lot of time. This is download a linux distribution, installing it along with a bunch of dependencies.
*  Run `composer install` && `npm install` && `bower install` && `grunt` (Note: if some of these task runners or dependency managers fail or appear to fail it may be because they are not being used by this project)
*  Navigate to the sites domain + .dev) e.g. forthepeople.dev, run through the installation process, you can use dummy information here, just remember the user name and password.
*  Log into the admin (e.g. http://forthepeople.dev/wp/wp-admin, note the */wp/* in between the domain and the /wp-admin)
*  In the plugins section activate all plugins with `WP DB Migrate Pro` in their title.
*  Obtain the serial and enter where prompted.
*  Obtain the migration string from production and migrate the database from production, including the media. Save the migration profile so you can pull from production in the future to sync.
*  Once you have migrated, the login will now be whatever your login is for production. Your dummy login that you previously created is no longer valid.

## Deployments

We use [CircleCI](http//circleci.com) for our deployments. Pushing to development will kick off the build for our staging site, master will kick off the build for our production site.

Our methodology for deploying is to store exactly what we push out to our hosts (at the time of this writing WPEngine and Pantheon) into their own separate interim repos. We do this for multiple reasons, including our local development is slightly different from what the host expects, so an exact replica of what we push up to the hosts is great to have for troubleshooting and for rolling back in case of a bad deploy.

The deployments, currently made to Pantheon and WPEngine. The deployment process done using Grunt. For demonstration purposes, the and occurs as follows:

Note, we are pushing up interim repos as follows, which is based on the host provider's deployment processes.

Some quick notes:

* Pantheon sources the entire core
* Pantheon has a single repository for all deployments. After pushing to this repository (which deploys to what they call "Dev"). You can move to their other two environments ("Test" and "Live") using their Terminus CLI
* WPEngine sources only our `wp-content` directory
* WPEngine has separate repositories for Staging and Production



### Grab our sources

* Pull in our source repo (branch is dependent on if we are deploying to production or development)
* Pull in our repo hosted on Pantheon
* Pull in our repo hosted on WPEngine
* Pull in our interim repos for our hosts (currently,  Pantheon and WPEngine)
* Pull in WordPress' latest core code from [wordpress.org](http://wordpress.org)

###Build our project
* This is currently empty, but should include some form of composer for backend dependency management, bower for frontend dependency management, grunt for processing, etc.

### Process our repos

##### Pantheon
*  Untar the WordPress core and remove the wp-content directory (since that contains nothing that we will need, we will be populating its content elsewhere)
*  Copy the wordpress contents into a blank directory
*  Sync the pantheon directory into the previous directory
*  Sync our source project's `wp-content` directory into the previous directory
*  Delete host's interim repository directory and sync the processed interim's prject into that directory
*  Add the new files to the interim repo, commit, tag (YYYYMMDDHHSS timestamp) and push to the interim repo

##### WPEngine
*  Copy the wordpress contents into a blank directory
*  Sync our source project's `wp-content` directory into the previous directory
*  Delete host's interim repository directory and sync the processed interim's prject into that directory
*  Add the new files to the interim repo, commit, tag (YYYYMMDDHHSS timestamp) and push to the interim repo


###Deploy to Hosts

##### Pantheon

* Delete the contents of the wp-content directory (except for `mu-plugins`)repo hosted on the target host.  We do not remove the `mu-plugins` directory because Pantheon requires plugins in that directory and they get lost unless we version them.
* Move the host's interim repository into the repo hosted on the target host
* Tag the repo (YYYYMMDDHHSS timestamp)
* Push the repo up to Host (with force flag on, though this should not be necessary)
* Update Pantheon environments using the CLI, see the notes above.

##### WPEngine

* Delete the contents of the repo hosted on the target host
* Move the host's interim repository into the repo hosted on the target host
* Using LFTP clear out the entire wp-contents directory from the host's server (using SFTP).
* Push the repo up to the host, environment is determined by CLI arguments in the grunt task (with force flag on, though this should not be necessary) -- Fortunately as part of WPEngine's deployment process automatically adds any plugins that we are not versioning.


## Rollbacks

Rollbacks will happen infrequently (hopefully!) - and are kicked off locally.

You must have [terminus](https://github.com/pantheon-systems/cli), Pantheon's CLI installed.

To rollback you need to get the commit hash from the two providers interim repos and execute:
```
export PANTHON_USER=(pantheon username)*
export PANTHEON_PASSWORD=(your pantheon password)
export PANTHEON_SITE_NAME=(pantheon site name)**
grunt rollback --sourceRepoBranch=(development|master) --wpEngineCommit=(INTERIM COMMIT HASH) --pantheonCommit=(INTERIM COMMIT HASH)
```

* Your Pantheon username is most likely your email address
** To get a list of Pantheon site names, first auth to terminus (`terminus auth login`) and then run `terminus site deploy` and you will see a listing of site names.


After you make a fix, deploy as you would normally.