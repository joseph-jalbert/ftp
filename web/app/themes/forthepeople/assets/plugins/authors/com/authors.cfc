
<!---CMS Authors Plugin Components--->
<cfcomponent displayname="Authors Plugin Components" name="authors" output="false">

	<!--- call init() automatically when the CFC is instantiated --->
  	<cfset init(application.adminUtils)>

	<cffunction name="init" access="public" output="false" returntype="authors">

		<cfargument name="adminUtils" type="component" required="no" default="#application.adminUtils#">

		<!--- Putting the variable in the variables scope makes it available to the init() method as well as all other methods in the CFC--->
		<cfset variables.adminUtils = arguments.adminUtils>

		<cfreturn this />
	</cffunction>

	<cffunction name="resetTags" displayname="Reset Tags" description="I am a required function that can be called to clear all links to a tag that is being deleted" access="remote" output="false" returntype="void">

		<cfargument name="tagID" type="numeric" required="yes" hint="I am a tag that is about to be deleted">

		<cfquery>
		UPDATE plugin_authors
		SET authorAuthorTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE authorAuthorTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

	</cffunction>

	<cffunction name="validateUserForAuthors" displayname="Validate User for Authors" description="Validates a user for accessing the authors plugin" access="private" output="false" returntype="struct">

		<cfargument name="userID" type="numeric" required="yes" hint="ID of user being checked">
		<cfargument name="checkDelete" type="numeric" required="no" default="0" hint="If set to 1, I check whether the user has delete permissions as well">

		<cfset local.status = structNew()>
		<cfset local.qry_getUserbyID = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = 'An error occurred while validating this user.'>

		<!---make sure that the userID matches the userID in the session--->
		<cfif not isdefined('session.userid') or session.userid NEQ arguments.userID>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---checks to make sure everything here is valid--->
		<cfset local.qry_getUserbyID = variables.adminUtils.getUserByID(userID = arguments.userID)>

		<!---user is not valid--->
		<cfif local.qry_getUserbyID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---user does not have permission to create--->
		<cfif local.qry_getUserbyID.permissions_contentcreate EQ 0>
			<cfset local.status["message"] = 'The user does not have sufficient privileges to access authors. The "content create" privilege must be added by a system administrator.'>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfif arguments.checkDelete EQ 1>

			<cfif local.qry_getUserbyID.permissions_contentdelete EQ 0>
				<cfset local.status["message"] = 'The user does not have sufficient privileges to access authors. The "content delete" privilege must be added by a system administrator.'>
				<cfreturn local.status>
				<cfabort>
			</cfif>

		</cfif>

		<!---user is valid, logged in, and has permission--->
		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = 'User validated successfully.'>

		<cfreturn local.status>
	</cffunction>

	<cffunction name="returnValidAuthorTagID" displayname="Return Valid Author Tag ID" description="Makes sure ID for the related author is a valid author tag" access="private" output="false" returntype="numeric">

		<cfargument name="tagID" type="numeric" required="yes" hint="ID of the Author tag being linked">

		<cfset local.qry_getAuthorTagByID = ''>

		<cfquery name="local.qry_getAuthorTagByID">
		SELECT tagID
		FROM tags
		WHERE tagType = <cfqueryparam cfsqltype="cf_sql_integer" value="8">
		AND tagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

		<cfif local.qry_getAuthorTagByID.recordcount EQ 0>
			<cfset arguments.tagID = 0>
		</cfif>

		<cfreturn arguments.tagID>
	</cffunction>


	<cffunction name="getAuthorByID" displayname="Get Author By ID" description="Gets all the information for an author, by ID" access="private" output="false" returntype="query">

		<cfargument name="authorID" type="numeric" required="yes" hint="ID of author">

		<cfset local.qry_getAuthorByID = ''>

		<cfquery name="local.qry_getAuthorByID">
		SELECT TOP 1 authorID, authorAuthorTagID, authorFirstName, authorLastName, authorGooglePlus,
		authorTwitter, authorEmail, authorShortBio, authorPhotoPath, authorStatus
		FROM plugin_authors
		WHERE authorID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.authorID#">
		</cfquery>

		<cfreturn local.qry_getAuthorByID>
	</cffunction>


	<!---Add Author--->
	<cffunction name="authorAdd" displayname="Add Author" description="Validates then adds a new author" access="remote" output="false" returntype="struct">

		<!---arguments--->
		<cfargument name="authorAuthorTagID" type="numeric" required="yes" hint="I am the ID of the tag this author is linked to">
		<cfargument name="authorFirstName" type="string" required="yes" hint="I am the author's first name">
		<cfargument name="authorLastName" type="string" required="yes" hint="I am the author's last name">
		<cfargument name="authorEmail" type="string" required="no" default="" hint="I am the author's email address">
		<cfargument name="authorGooglePlus" type="string" required="no" default="" hint="I am author's Google+ account">
		<cfargument name="authorTwitter" type="string" required="no" default="" hint="I am author's Twitter account">
		<cfargument name="authorShortBio" type="string" required="no" default="" hint="I am a short bio about the author">
		<cfargument name="authorPhotoPath" type="string" required="no" default="" hint="I am path to the author's photo">
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user adding the author">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getTopAuthor = ''>
		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["authorID"] = 0>
       
		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForAuthors(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
		</cfif>

		<!---Trim and enforce length on other text fields--->
		<cfset arguments.authorFirstName = left(trim(arguments.authorFirstName),50)>

		<cfif NOT len(arguments.authorFirstName)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a First Name for the author.')>
			<cfreturn local.status>
		</cfif>

		<cfset arguments.authorLastName = left(trim(arguments.authorLastName),50)>

		<cfif NOT len(arguments.authorLastName)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a Last Name for the author.')>
			<cfreturn local.status>
		</cfif>

		<cfset arguments.authorEmail = left(trim(arguments.authorEmail),254)>


		<cfset arguments.authorGooglePlus = left(trim(arguments.authorGooglePlus),250)>

		<cfif len(arguments.authorGooglePlus)>
			<!---google+ profile URL check--->
			<cfif variables.adminUtils.urlExists(u = arguments.authorGooglePlus) NEQ 'True'>
				 <cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = "We could not locate your Google+ profile. Please make sure that you have entered your profile URL correctly.")>
				<cfreturn local.status>
			</cfif>
		</cfif>

		<cfset arguments.authorTwitter = left(trim(arguments.authorTwitter),20)>

		<cfif len(arguments.authorTwitter)>
			<!---twitter URL check--->
			<cfset local.fullTwitterURL = 'http://twitter.com/#arguments.authorTwitter#'>

			<cfif variables.adminUtils.urlExists(u = local.fullTwitterURL) NEQ 'True'>
				 <cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = "We could not locate your Twitter profile. Please make sure that you have entered your username correctly.")>
				<cfreturn local.status>
			</cfif>
		</cfif>
		
		<cfset arguments.authorShortBio = variables.adminUtils.tagStripper(str = arguments.authorShortBio,action = "strip",tagList = "br,strong,a")>
		<cfset arguments.authorShortBio = left(trim(arguments.authorShortBio),500)>

		<cfset arguments.authorPhotoPath = left(trim(arguments.authorPhotoPath),500)>
		
		<cfif LEN(arguments.authorPhotoPath)>
			<cfif NOT FileExists(arguments.authorPhotoPath)>
				<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = "We could not locate your Author Photo. Please make sure that you have entered the Photo Path correctly.")>
				<cfreturn local.status>
			</cfif>

			<cfif NOT isImageFile(arguments.authorPhotoPath)>
				<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = "The path to your photo must be to a valid image file.")>
				<cfreturn local.status>
			</cfif>	

		</cfif>
	
		<cfset arguments.authorAuthorTagID = returnValidAuthorTagID(tagID = arguments.authorAuthorTagID)>
		

		<cfquery>
		INSERT INTO plugin_authors(
		authorAuthorTagID,
		authorFirstName,
		authorLastName,
		authorEmail,
		authorGooglePlus,
		authorTwitter,
		authorShortBio,
		authorPhotoPath)
		VALUES(
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.authorAuthorTagID#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorFirstName#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorLastName#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorEmail#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorGooglePlus#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorTwitter#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorShortBio#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorPhotoPath#">)
		</cfquery>

		<cfquery name="local.qry_getTopAuthor">
		SELECT TOP 1 authorID
		FROM plugin_authors
		ORDER BY authorID DESC
		</cfquery>

		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The author was added successfully. You will now be redirected.')>
		<cfset local.status["authorID"] = local.qry_getTopAuthor.authorID>

		<cfreturn local.status>
	</cffunction>
	<!---END add author--->


	<!---Update Author--->
	<cffunction name="authorSet" displayname="Update Author" description="Validates then updates an author" access="remote" output="false" returntype="struct">

		<!---arguments--->
		<cfargument name="authorID" type="numeric" required="yes">
		<cfargument name="authorAuthorTagID" type="numeric" required="yes" hint="I am the ID of the tag this author is linked to">
		<cfargument name="authorFirstName" type="string" required="yes" hint="I am the author's first name">
		<cfargument name="authorLastName" type="string" required="yes" hint="I am the author's last name">
		<cfargument name="authorEmail" type="string" required="no" default="" hint="I am the author's email address">
		<cfargument name="authorGooglePlus" type="string" required="no" default="" hint="I am author's Google+ account">
		<cfargument name="authorTwitter" type="string" required="no" default="" hint="I am author's Twitter account">
		<cfargument name="authorShortBio" type="string" required="no" default="" hint="I am a short bio about the author">
		<cfargument name="authorPhotoPath" type="string" required="no" default="" hint="I am path to the author's photo">
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user adding the author">



		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.qry_getAuthorByID = ''>
		<cfset local.validateUserStruct = structNew()>
	
		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["authorID"] = 0>

		<cfset local.qry_getAuthorByID = getAuthorByID(authorID = arguments.authorID)>

		<cfif local.qry_getAuthorByID.recordcount EQ 0>
			<cfreturn local.status>
		</cfif>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForAuthors(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
		</cfif>

		<!---Trim and enforce length on other text fields--->
	<cfset arguments.authorFirstName = left(trim(arguments.authorFirstName),50)>

		<cfif NOT len(arguments.authorFirstName)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a First Name for the author.')>
			<cfreturn local.status>
		</cfif>

		<cfset arguments.authorLastName = left(trim(arguments.authorLastName),50)>

		<cfif NOT len(arguments.authorLastName)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a Last Name for the author.')>
			<cfreturn local.status>
		</cfif>

		<cfset arguments.authorEmail = left(trim(arguments.authorEmail),254)>


		<cfset arguments.authorGooglePlus = left(trim(arguments.authorGooglePlus),250)>

		<cfif len(arguments.authorGooglePlus)>
			<!---google+ profile URL check--->
			<cfif variables.adminUtils.urlExists(u = arguments.authorGooglePlus) NEQ 'True'>
				 <cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = "We could not locate your Google+ profile. Please make sure that you have entered your profile URL correctly.")>
				<cfreturn local.status>
			</cfif>
		</cfif>

		<cfset arguments.authorTwitter = left(trim(arguments.authorTwitter),20)>

		<cfif len(arguments.authorTwitter)>
			<!---twitter URL check--->
			<cfset local.fullTwitterURL = 'http://twitter.com/#arguments.authorTwitter#'>

			<cfif variables.adminUtils.urlExists(u = local.fullTwitterURL) NEQ 'True'>
				 <cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = "We could not locate your Twitter profile. Please make sure that you have entered your username correctly.")>
				<cfreturn local.status>
			</cfif>
		</cfif>
		
		<cfset arguments.authorShortBio = variables.adminUtils.tagStripper(str = arguments.authorShortBio,action = "strip",tagList = "br,strong,a")>
		<cfset arguments.authorShortBio = left(trim(arguments.authorShortBio),500)>

		<cfset arguments.authorPhotoPath = left(trim(arguments.authorPhotoPath),500)>
		
		<cfif LEN(arguments.authorPhotoPath)>
			<cfif NOT FileExists(arguments.authorPhotoPath)>
				<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = "We could not locate your Author Photo. Please make sure that you have entered the Photo Path correctly.")>
				<cfreturn local.status>
			</cfif>

			<cfif NOT isImageFile(arguments.authorPhotoPath)>
				<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = "The path to your photo must be to a valid image file.")>
				<cfreturn local.status>
			</cfif>	

		</cfif>
	
		<cfset arguments.authorAuthorTagID = returnValidAuthorTagID(tagID = arguments.authorAuthorTagID)>
	
		<cfquery>
		UPDATE plugin_authors
		SET authorAuthorTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.authorAuthorTagID#">,
		authorFirstName = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorFirstName#">,
		authorLastName = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorLastName#">,
		authorEmail = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorEmail#">,
		authorGooglePlus = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorGooglePlus#">,
		authorTwitter = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorTwitter#">,
		authorShortBio = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorShortBio#">,
		authorPhotoPath = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.authorPhotoPath#">
		WHERE authorID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.authorID#">
		</cfquery>

		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The author was updated successfully.')>
		<cfset local.status["authorID"] = arguments.authorID>

		<cfreturn local.status>
	</cffunction>
	<!---END update author--->

	<!---DELETE author (disable)--->
	<cffunction name="authorDelete" displayname="Delete Author" description="Delete's an author" access="remote" output="false" returntype="struct">

		<cfargument name="authorID" type="numeric" required="yes" hint="ID of author being removed">
		<cfargument name="userID" type="numeric" required="yes" hint="ID of the user deleting the author">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getAuthorByID = ''>
      

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForAuthors(userID = arguments.userID, checkDelete = 1)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
		</cfif>

		<!---validate author--->
		<cfset local.qry_getAuthorByID = getAuthorByID(authorID = arguments.authorID)>

		<cfif local.qry_getAuthorByID.recordcount EQ 0>
			<cfreturn local.status>
		</cfif>

		<cfquery>
		UPDATE plugin_authors
		SET authorStatus = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE authorID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.authorID#">
		</cfquery>


		<cfset local.status["success"] = 1>
        <cfset local.status["message"] =  variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The author has been deleted successfully.')>

		<cfreturn local.status>
	</cffunction>
	<!---END DELETE--->

   
</cfcomponent>
