# For The People

## Pre-requisites (you may have some of these installed already)

* Virtualbox at [virtualbox.org](http://virtualbox.org)
* Vagrant at [vagrantup.com](http://vagrantup.com)
* Composer instructions for downloading at [getcomposer.org](http://getcomposer.org)
* Node and NPM	using [homebrew](http://brew.sh/) (preferred) or from [nodejs.org](https://nodejs.org/en/download/)
* Bower instructions for downloading at [bower.io](http://bower.io)
* Grunt CLI instructions at [its project page](https://github.com/gruntjs/grunt-cli)
* Laravel Homestead instructions at https://github.com/laravel/homestead

Optional:

* [terminus](https://github.com/pantheon-systems/cli) which is Pantheon's CLI

## Setting up to develop locally


*  Add the following homestead alias to ~/.bash_profile, note, if necessary change `~/Homestead` to the location you have installed Homestead, also you will need to run `source ~/.bash_profile` after updating your bash profile.
 ```
 function homestead() {
     ( cd ~/Homestead && vagrant $* )
 }
 ```
 
*  Clone this repository
*  Open the `Homestead.yaml` file that Homestead creates (mine is located at `~/.homestead/Homestead.yaml`)
*  Add a new entry to the sites key pointing www.forthepeople.dev to the location of your repo + /web, for example mine is  
 
 ```
 sites:
     - map: www.forthepeople.dev
       to: /home/vagrant/Sites/forthepeople.dev/web
```

*  Optionally, add in a database (this is if you want to do local development) 
*  Add a new site (and optionally a db) 
*  Copy `.env.example` to `.env` and set the file's contents to the current .env file (saved in LastPass), unless you want to do local development 
*  Run `composer install` && `npm install` && `bower install` && `grunt` (Note: if some of these task runners or dependency managers fail or appear to fail it may be because they are not being used by this project)
*  Run `homestead up`
*  Run `homestead provision`
*  Navigate to www.forthepeople.dev/wp-admin  you should be able to log in using the credentials on the staging db


## Deployments

Our process has been updated, the below may be out of date.

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

## Updating Wordpress

* Updating Wordpress is a manual process, executed from command line.
* There is a required API Token which is available in the CircleCI dashboard

To update the WordPress version to latest, run this curl command, specifying the branch (development or master) and the API key.
```
curl \
  --header "Content-Type: application/json" \
  --data '{"build_parameters": {"UPDATE_WORDPRESS": "true"}}' \
  --request POST \
  https://circleci.com/api/v1/project/Morgan-and-Morgan/forthepeople.com/tree/BRANCH?circle-token=APIKEY
```
Replace BRANCH with the target branch and APIKEY with the API Token.  It's that simple.


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