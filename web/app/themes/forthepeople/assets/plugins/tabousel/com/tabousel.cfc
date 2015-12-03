<!---CMS Tab Carousel Plugin Components--->

<cfcomponent displayname="Tab Carousel Plugin Components" name="tabousel" output="false">

	<!--- call init() automatically when the CFC is instantiated --->
  	<cfset init(application.adminUtils)>

	<cffunction name="init" access="public" output="false" returntype="tabousel">

		<cfargument name="adminUtils" type="component" required="no" default="#application.adminUtils#">

		<!--- Putting the variable in the variables scope makes it available to the init() method as well as all other methods in the CFC--->
		<cfset variables.adminUtils = arguments.adminUtils>

		<cfreturn this />
	</cffunction>

	<cffunction name="resetTags" displayname="Reset Tags" description="I am a required function that can be called to clear all links to a tag that is being deleted" access="remote" output="false" returntype="void">

		<cfargument name="tagID" type="numeric" required="yes" hint="I am a tag that is about to be deleted">

	</cffunction>

	<cffunction name="validateUserForTabousel" displayname="Validate User for Tabousel" description="Validates a user for accessing the tabousel plugin" access="private" output="false" returntype="struct">

		<cfargument name="userID" type="numeric" required="yes" hint="ID of user being checked">
		<cfargument name="checkDelete" type="numeric" required="no" default="0" hint="If set to 1, I check whether the user has delete permissions as well">

		<cfset local.status = structNew()>
		<cfset local.qry_getUserbyID = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = 'An error occurred while validating this user.'>

		<!---make sure that the userID matches the userID in the session--->
		<cfif not isdefined('session.userid') or session.userid NEQ arguments.userID>
			<cfreturn local.status>
		</cfif>

		<!---checks to make sure everything here is valid--->
		<cfset local.qry_getUserbyID = variables.adminUtils.getUserByID(userID = arguments.userID)>

		<!---user is not valid--->
		<cfif local.qry_getUserbyID.recordcount EQ 0>
			<cfreturn local.status>
		</cfif>

		<!---user does not have permission to create--->
		<cfif local.qry_getUserbyID.permissions_contentcreate EQ 0>
			<cfset local.status["message"] = 'The user does not have sufficient privileges to access tab tiles. The "content create" privilege must be added by a system administrator.'>
			<cfreturn local.status>
		</cfif>

		<cfif arguments.checkDelete EQ 1>

			<cfif local.qry_getUserbyID.permissions_contentdelete EQ 0>
				<cfset local.status["message"] = 'The user does not have sufficient privileges to access tab tiles. The "content delete" privilege must be added by a system administrator.'>
				<cfreturn local.status>
			</cfif>

		</cfif>

		<!---user is valid, logged in, and has permission--->
		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = 'User validated successfully.'>

		<cfreturn local.status>
	</cffunction>

	<cffunction name="getTileByID" displayname="Get Tile By ID" description="Gets all the information for a tab tile, by ID" access="private" output="false" returntype="query">

		<cfargument name="tabouselTileID" type="numeric" required="yes" hint="ID of tile">

		<cfset local.qry_getTileByID = ''>

		<cfquery name="local.qry_getTileByID">
		SELECT TOP (1) tabouselTileID, tabouselTileTitle, tabouselTileImgURL, tabouselTileLinkURL, tabouselTileLinkText, tabouselTileText, tabouselTileUserID, tabouselTileDateAdded
		FROM plugin_tabouselTile
		WHERE tabouselTileID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselTileID#">
		</cfquery>

		<cfreturn local.qry_getTileByID>
	</cffunction>

	<cffunction name="getTabouselByID" displayname="Get Tabousel By ID" description="Gets all the information for a Tabousel, by ID" access="private" output="false" returntype="query">

		<cfargument name="tabouselID" type="numeric" required="yes" hint="ID of the tabousel">

		<cfset local.qry_getTabouselByID = ''>

		<cfquery name="local.qry_getTabouselByID">
		SELECT TOP (1) tabouselID, tabouselName, tabouselCategories, tabouselMoreLink, tabouselDateAdded
		FROM plugin_tabousel
		WHERE tabouselID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselID#">
		</cfquery>

		<cfreturn local.qry_getTabouselByID>
	</cffunction>

	<cffunction name="tabouselTileAdd" displayname="Add Tab Tile" description="Validates then adds a new tab tile" access="remote" output="false" returntype="struct">

		<!---arguments--->
		<cfargument name="tabouselTileTitle" type="string" required="yes" hint="Title of the tab tile">
		<cfargument name="tabouselTileImgURL" type="string" required="yes" hint="Path to image for tile">
		<cfargument name="tabouselTileLinkURL" type="string" required="yes" hint="Destination URL link">
		<cfargument name="tabouselTileLinkText" type="string" required="yes" hint="Destination Link text">
		<cfargument name="tabouselTileText" type="string" required="yes" hint="Description of the tile">
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user adding the tile">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.validateRequiredFieldStruct = structNew()>
		<cfset local.urlStruct = structNew()>
		<cfset local.validFileTypeArray = ['.jpg','.jpeg','.png','.gif','.local']>
		<cfset local.qry_getTopTile = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["tabouselTileID"] = 0>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForTabousel(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
		</cfif>

		<!--- trim and clean fields --->
		<cfset arguments.tabouselTileTitle = replacenocase(arguments.tabouselTileTitle,'Enter Tile Title Here','','all')>
		<cfset arguments.tabouselTileTitle = trim(left(arguments.tabouselTileTitle,150))>

		<cfset arguments.tabouselTileImgURL = trim(left(arguments.tabouselTileImgURL,500))>
		<cfset arguments.tabouselTileLinkURL = trim(left(arguments.tabouselTileLinkURL,500))>
		<cfset arguments.tabouselTileLinkText = trim(left(arguments.tabouselTileLinkText,50))>

		<cfset arguments.tabouselTileText = trim(left(arguments.tabouselTileText,8000))>
		<!---clean the body of all but br,a,strong tags--->
		<cfset arguments.tabouselTileText = variables.adminUtils.tagStripper(str = arguments.tabouselTileText,action = "strip",tagList = "br,a,strong")>
		<!--- validate title --->
		<cfset local.validateRequiredFieldStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.tabouselTileTitle, stringName = 'the tile title')>
		<cfif local.validateRequiredFieldStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateRequiredFieldStruct.message)>
			<cfreturn local.status>
		</cfif>
		<!--- validate body text --->
		<cfset local.validateRequiredFieldStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.tabouselTileText, stringName = 'the body text')>
		<cfif local.validateRequiredFieldStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateRequiredFieldStruct.message)>
			<cfreturn local.status>
		</cfif>
		<!--- IMG--->
		<cfset local.urlStruct = variables.adminUtils.advancedURLRegex(URL = arguments.tabouselTileImgURL)>
		<cfif NOT variables.adminUtils.isURL(arguments.tabouselTileImgURL) OR NOT len(local.urlStruct.protocol) OR NOT len(local.urlStruct.domain) OR NOT len(local.urlStruct.domainEnding) OR ArrayFindNoCase(local.validFileTypeArray, local.urlStruct.fileType) EQ 0>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please ensure that the image is a full URL path to a valid jpg, png, or gif.')>
			<cfreturn local.status>
		</cfif>
		<!--- Link URL--->
		<cfset local.urlStruct = variables.adminUtils.advancedURLRegex(URL = arguments.tabouselTileLinkURL)>
		<cfif NOT variables.adminUtils.isURL(arguments.tabouselTileLinkURL) OR NOT len(local.urlStruct.protocol) OR NOT len(local.urlStruct.domain) OR NOT len(local.urlStruct.domainEnding)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please ensure that the destination link is a valid, full URL')>
			<cfreturn local.status>
		</cfif>

		<cfquery>
		INSERT INTO plugin_tabouselTile(
		tabouselTileTitle,
		tabouselTileImgURL,
		tabouselTileLinkURL,
		tabouselTileLinkText,
		tabouselTileText,
		tabouselTileUserID)
		VALUES(
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselTileTitle#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselTileImgURL#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselTileLinkURL#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselTileLinkText#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselTileText#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">)
		</cfquery>

		<cfquery name="local.qry_getTopTile">
		SELECT TOP 1 tabouselTileID
		FROM plugin_tabouselTile
		ORDER BY tabouselTileID DESC
		</cfquery>

		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The tile was added successfully. You will now be redirected.')>
		<cfset local.status["tabouselTileID"] = local.qry_getTopTile.tabouselTileID>

		<cfreturn local.status>
	</cffunction>

	<cffunction name="tabTileSet" displayname="Update Tab Tile" description="Validates then updates a tile's information" access="remote" output="false" returntype="struct">

		<!---arguments--->
		<cfargument name="tabouselTileID" type="numeric" required="yes" hint="I am the ID of the tab tile being updated">
		<cfargument name="tabouselTileTitle" type="string" required="yes" hint="Title of the tab tile">
		<cfargument name="tabouselTileImgURL" type="string" required="yes" hint="Path to image for tile">
		<cfargument name="tabouselTileLinkURL" type="string" required="yes" hint="Destination URL link">
		<cfargument name="tabouselTileLinkText" type="string" required="yes" hint="Destination Link text">
		<cfargument name="tabouselTileText" type="string" required="yes" hint="Description of the tile">
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user updating the tile">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.qry_getTileByID = ''>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.validateRequiredFieldStruct = structNew()>
		<cfset local.urlStruct = structNew()>
		<cfset local.validFileTypeArray = ['.jpg','.jpeg','.png','.gif','.local']>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["extension"] = ''>
		<cfset local.status["tabouselTileID"] = 0>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForTabousel(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
		</cfif>

		<cfset local.qry_getTileByID = getTileByID(tabouselTileID = arguments.tabouselTileID)>

		<cfif local.qry_getTileByID.recordcount EQ 0>
			<cfreturn local.status>
		</cfif>

		<!--- trim and clean fields --->
		<cfset arguments.tabouselTileTitle = replacenocase(arguments.tabouselTileTitle,'Enter Tile Title Here','','all')>
		<cfset arguments.tabouselTileTitle = trim(left(arguments.tabouselTileTitle,150))>

		<cfset arguments.tabouselTileImgURL = trim(left(arguments.tabouselTileImgURL,500))>
		<cfset arguments.tabouselTileLinkURL = trim(left(arguments.tabouselTileLinkURL,500))>
		<cfset arguments.tabouselTileLinkText = trim(left(arguments.tabouselTileLinkText,50))>

		<cfset arguments.tabouselTileText = trim(left(arguments.tabouselTileText,8000))>
		<!---clean the body of all but br,a,strong tags--->
		<cfset arguments.tabouselTileText = variables.adminUtils.tagStripper(str = arguments.tabouselTileText,action = "strip",tagList = "br,a,strong")>
		<!--- validate title --->
		<cfset local.validateRequiredFieldStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.tabouselTileTitle, stringName = 'the tile title')>
		<cfif local.validateRequiredFieldStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateRequiredFieldStruct.message)>
			<cfreturn local.status>
		</cfif>
		<!--- validate body text --->
		<cfset local.validateRequiredFieldStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.tabouselTileText, stringName = 'the body text')>
		<cfif local.validateRequiredFieldStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateRequiredFieldStruct.message)>
			<cfreturn local.status>
		</cfif>
		<!--- IMG--->
		<cfset local.urlStruct = variables.adminUtils.advancedURLRegex(URL = arguments.tabouselTileImgURL)>
		<cfset local.status["extension"] = local.urlStruct.fileType>
		<cfif NOT variables.adminUtils.isURL(arguments.tabouselTileImgURL) OR NOT len(local.urlStruct.protocol) OR NOT len(local.urlStruct.domain) OR NOT len(local.urlStruct.domainEnding) OR ArrayFindNoCase(local.validFileTypeArray, local.urlStruct.fileType) EQ 0>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please ensure that the image is a full URL path to a valid jpg, png, or gif.')>
			<cfreturn local.status>
		</cfif>
		<!--- Link URL--->
		<cfset local.urlStruct = variables.adminUtils.advancedURLRegex(URL = arguments.tabouselTileLinkURL)>
		<cfif NOT variables.adminUtils.isURL(arguments.tabouselTileLinkURL) OR NOT len(local.urlStruct.protocol) OR NOT len(local.urlStruct.domain) OR NOT len(local.urlStruct.domainEnding)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please ensure that the destination link is a valid, full URL')>
			<cfreturn local.status>
		</cfif>

		<cfquery>
		UPDATE plugin_tabouselTile
		SET tabouselTileTitle = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselTileTitle#">,
		tabouselTileImgURL = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselTileImgURL#">,
		tabouselTileLinkURL = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselTileLinkURL#">,
		tabouselTileLinkText = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselTileLinkText#">,
		tabouselTileText = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselTileText#">,
		tabouselTileUserID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">
		WHERE tabouselTileID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselTileID#">
		</cfquery>

		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The tile was updated successfully.')>
		<cfset local.status["tabouselTileID"] = arguments.tabouselTileID>

		<cfreturn local.status>
	</cffunction>

	<cffunction name="tabouselTileDelete" displayname="Delete Tile" description="Deletes a tile and removes it from all carousels" access="remote" output="false" returntype="struct">

		<cfargument name="tabouselTileID" type="numeric" required="yes" hint="ID of tile being removed">
		<cfargument name="userID" type="numeric" required="yes" hint="ID of the user deleting the tile">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getTileByID = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForTabousel(userID = arguments.userID, checkDelete = 1)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
		</cfif>

		<cfset local.qry_getTileByID = getTileByID(tabouselTileID = arguments.tabouselTileID)>

		<cfif local.qry_getTileByID.recordcount EQ 0>
			<cfreturn local.status>
		</cfif>

		<cfquery>
		DELETE FROM plugin_tabouselTile
		WHERE tabouselTileID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselTileID#">
		</cfquery>

		<cfset local.status["success"] = 1>
        <cfset local.status["message"] =  variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The tab tile has been deleted successfully.')>

		<cfreturn local.status>
	</cffunction>

	<cffunction name="addNewTabousel" displayname="Add New Tab Carousel" hint="I validate then add new tab carousels" access="remote" output="false" returntype="struct">

		<cfargument name="userID" type="numeric" required="yes">
		<cfargument name="tabouselName" type="string" required="yes" hint="I am the name for the new tab carousel">
		<cfargument name="tabouselCategories" type="string" required="yes" hint="I am a comma separated list of categories">

		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_checkTabouselName = ''>
		<cfset local.qry_getTopTabousel = ''>
		<cfset local.category = ''>
		<cfset local.validCategoryList = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["tabouselID"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnModalStatusMessage()>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForTabousel(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnModalStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
		</cfif>

		<cfset arguments.tabouselName = trim(left(arguments.tabouselName,150))>
		<cfset arguments.tabouselCategories = trim(left(arguments.tabouselCategories,8000))>

		<!---name is blank--->
		<cfif arguments.tabouselName EQ ''>
			<cfset local.status["message"] = variables.adminUtils.returnModalStatusMessage(messageText = 'Please enter a name for the tab carousel.')>
			<cfreturn local.status>
		</cfif>

		<cfif arguments.tabouselCategories EQ ''>
			<cfset local.status["message"] = variables.adminUtils.returnModalStatusMessage(messageText = 'Please enter at least one category for this tab carousel.')>
			<cfreturn local.status>
		</cfif>

		<cfquery name="local.qry_checkTabouselName">
		SELECT TOP 1 tabouselID
		FROM plugin_tabousel
		WHERE tabouselName = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselName#">
		</cfquery>

		<!---the name is already used--->
		<cfif local.qry_checkTabouselName.recordcount EQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnModalStatusMessage(messageText = 'The name you entered is already used by another tab carousel.')>
			<cfreturn local.status>
		</cfif>

		<!---all variables okay, run insert--->
		<cfquery>
		INSERT INTO plugin_tabousel(tabouselName)
		VALUES(<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselName#">)
		</cfquery>

		<cfquery name="local.qry_getTopTabousel">
		SELECT TOP 1 tabouselID
		FROM plugin_tabousel
		ORDER BY tabouselID DESC
		</cfquery>

		<!--- handle categories --->
		<cfloop list="#arguments.tabouselCategories#" index="local.category">
			<cfset local.category = trim(htmleditformat(local.category))>
			<!---category valid--->
			<cfif NOT listContains(local.validCategoryList,local.category)>
				<cfset local.validCategoryList = listAppend(local.validCategoryList,local.category)>
			</cfif>
		</cfloop>

		<cfquery>
		UPDATE plugin_tabousel
		SET tabouselCategories = <cfqueryparam cfsqltype="cf_sql_varchar" value="#local.validCategoryList#">,
			tabouselMoreLink = <cfqueryparam cfsqltype="cf_sql_varchar" value="">
		WHERE tabouselID = <cfqueryparam cfsqltype="cf_sql_integer" value="#local.qry_getTopTabousel.tabouselID#">
		</cfquery>

		<cfset status["success"] = 1>
		<cfset status["tabouselID"] = local.qry_getTopTabousel.tabouselID>
		<cfset status["message"] = variables.adminUtils.returnModalStatusMessage(messageText = 'The new tab carousel has been created successfully. The page will now redirect you to the editor for the carousel.', messageTypeClass = 'success', messageTypeName = 'Success')>

		<cfreturn status>
	</cffunction>

	<cffunction name="tabouselDelete" displayname="Delete Tab Carousel" description="Delete's a tab carousel" access="remote" output="false" returntype="struct">

		<cfargument name="tabouselID" type="numeric" required="yes" hint="ID of tab carousel being removed">
		<cfargument name="userID" type="numeric" required="yes" hint="ID of the user deleting">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getTabouselByID = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForTabousel(userID = arguments.userID, checkDelete = 1)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
		</cfif>

		<cfset local.qry_getTabouselByID = getTabouselByID(tabouselID = arguments.tabouselID)>

		<cfif local.qry_getTabouselByID.recordcount EQ 0>
			<cfreturn local.status>
		</cfif>

		<cfquery>
		DELETE FROM plugin_tabousel
		WHERE tabouselID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselID#">
		</cfquery>

		<cfset local.status["success"] = 1>
        <cfset local.status["message"] =  variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The tab carousel has been deleted successfully.')>

		<cfreturn local.status>
	</cffunction>

	<cffunction name="modifyTabousel" displayname="Modify Existing Tab Carousel" hint="I validate then update tab carousels" access="remote" output="false" returntype="struct">

		<cfargument name="userID" type="numeric" required="yes">
		<cfargument name="tabouselID" type="numeric" required="yes" hint="I am the ID of the tabousel being updated">
		<cfargument name="tabouselName" type="string" required="yes" hint="I am the name for the tab carousel">
		<cfargument name="tabouselMoreLink" type="string" required="yes" hint="If provided, I am a URL for more information">
		<cfargument name="tabouselCategories" type="string" required="yes" hint="I am a comma separated list of categories">

		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getTabouselByID = ''>
		<cfset local.urlStruct = structNew()>
		<cfset local.qry_checkTabouselName = ''>
		<cfset local.category = ''>
		<cfset local.validCategoryList = ''>
		<cfset local.priorCategory = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["tabouselID"] = arguments.tabouselID>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForTabousel(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
		</cfif>

		<cfset local.qry_getTabouselByID = getTabouselByID(tabouselID = arguments.tabouselID)>

		<cfif local.qry_getTabouselByID.recordcount EQ 0>
			<cfreturn local.status>
		</cfif>

		<cfset arguments.tabouselName = trim(left(arguments.tabouselName,150))>
		<cfset arguments.tabouselMoreLink = trim(left(arguments.tabouselMoreLink,500))>
		<cfset arguments.tabouselCategories = trim(left(arguments.tabouselCategories,8000))>

		<!---name is blank--->
		<cfif arguments.tabouselName EQ ''>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a name for the tab carousel.')>
			<cfreturn local.status>
		</cfif>

		<cfif NOT len(arguments.tabouselCategories)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter at least one category for this tab carousel.')>
			<cfreturn local.status>
		</cfif>

		<cfif len(arguments.tabouselMoreLink)>
			<cfset local.urlStruct = variables.adminUtils.advancedURLRegex(URL = arguments.tabouselMoreLink)>
			<cfif NOT variables.adminUtils.isURL(arguments.tabouselMoreLink) OR NOT len(local.urlStruct.protocol) OR NOT len(local.urlStruct.domain) OR NOT len(local.urlStruct.domainEnding)>
				<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please ensure that the More Link is a valid, full URL')>
				<cfreturn local.status>
			</cfif>
		</cfif>

		<cfquery name="local.qry_checkTabouselName">
		SELECT TOP 1 tabouselID
		FROM plugin_tabousel
		WHERE tabouselName = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselName#">
		AND tabouselID <> <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselID#">
		</cfquery>

		<!---the name is already used--->
		<cfif local.qry_checkTabouselName.recordcount EQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'The name you entered is already used by another tab carousel.')>
			<cfreturn local.status>
		</cfif>

		<!--- handle categories --->
		<cfloop list="#arguments.tabouselCategories#" index="local.category">
			<cfset local.category = trim(htmleditformat(local.category))>
			<!---category valid--->
			<cfif NOT listContains(local.validCategoryList,local.category)>
				<cfset local.validCategoryList = listAppend(local.validCategoryList,local.category)>
			</cfif>
		</cfloop>

		<cfif NOT len(local.validCategoryList)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter at least one category for this tab carousel.')>
			<cfreturn local.status>
		</cfif>

		<cfquery>
		UPDATE plugin_tabousel
		SET tabouselName = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselName#">,
		tabouselMoreLink = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.tabouselMoreLink#">,
		tabouselCategories = <cfqueryparam cfsqltype="cf_sql_varchar" value="#local.validCategoryList#">
		WHERE tabouselID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselID#">
		</cfquery>

		<!--- remove any tiles that were linked to categories that were removed --->
		<cfloop index="local.priorCategory" list="#local.qry_getTabouselByID.tabouselCategories#">
			<cfif NOT listContains(local.validCategoryList,local.priorCategory)>
				<cfquery>
				DELETE FROM plugin_tabouselTileLink
				WHERE tabouselID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselID#">
				AND tabouselCategory = <cfqueryparam cfsqltype="cf_sql_varchar" value="#local.priorCategory#">
				</cfquery>
			</cfif>
		</cfloop>

		<cfset status["success"] = 1>
		<cfset status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'The tab carousel has been updated successfully. The page will now refresh to reflect these changes.', messageTypeClass = 'success', messageTypeName = 'Success')>

		<cfreturn status>
	</cffunction>

	<cffunction name="setTabouselTiles" displayname="Set the categories and tiles for a tab carousel" access="remote" output="false" returntype="struct">

		<cfargument name="userID" type="numeric" required="yes">
		<cfargument name="tabouselID" type="numeric" required="yes" hint="I am the ID of the tabouselID being updated">
		<cfargument name="carouselArray" type="string" required="yes" hint="I am the tab carousel, structured as a JSON array. I need to be deserialized because I am being passed in as a string.">

		<cfset local.status = structNew()>

		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getTabouselByID = ''>
		<cfset local.qry_getCurrentTilesByTabouselID = ''>
		<cfset local.topLevelStrKey = ''>
		<cfset local.nestedStrKey = ''>
		<cfset local.counter = 0>
		<cfset local.qry_getTileByID = ''>
		<cfset local.qry_checkCarouselForTileByID = ''>
		<cfset local.category = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["tabouselID"] = arguments.tabouselID>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForTabousel(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
		</cfif>

		<cfset local.qry_getTabouselByID = getTabouselByID(tabouselID = arguments.tabouselID)>

		<cfif local.qry_getTabouselByID.recordcount EQ 0>
			<cfreturn local.status>
		</cfif>

		<cfif IsJSON(arguments.carouselArray) EQ 'FALSE'>
			<cfreturn local.status>
		</cfif>

		<cfset arguments.carouselArray = DeserializeJSON(arguments.carouselArray)>

		<cfif NOT isArray(arguments.carouselArray) OR ArrayIsEmpty(arguments.carouselArray)>
			<cfreturn local.status>
		</cfif>

		<cftransaction>

		<!--- for possible roll back --->
		<cfquery name="local.qry_getCurrentTilesByTabouselID">
		SELECT tabouselID, tabouselTileID, tabouselCategory, tabouselOrder
		FROM  plugin_tabouselTileLink
		WHERE tabouselID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselID#">
		</cfquery>

		<cfquery>
		DELETE
		FROM plugin_tabouselTileLink
		WHERE tabouselID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselID#">
		</cfquery>

		<!---ok, we know we have a valid user, tab carousel, and array, so lets try to start our update and loop--->
		<cftry>

			<!---loop over the top level array (which holds the categories)--->
			<cfloop index="local.topLevelStrKey" array="#arguments.carouselArray#">

				<!---the struct here contains the category name--->
				<cfif NOT StructIsEmpty(local.topLevelStrKey)>

					<!---if the array in the children node is not empty, it contains the tiles for this category--->
					<cfif isArray(topLevelStrKey.children) and not ArrayIsEmpty(topLevelStrKey.children)>

						<cfloop index="nestedStrKey" array="#topLevelStrKey.children#">

							<!---validate tile--->
							<cfset local.qry_getTileByID = getTileByID(tabouselTileID = local.nestedStrKey.tileID)>

							<!---tile is valid--->
							<cfif local.qry_getTileByID.recordcount EQ 1>

								<!---make sure it is not already on the tab carousel--->
								<cfquery name="local.qry_checkCarouselForTileByID">
								SELECT TOP 1 tabouselTileID
								FROM plugin_tabouselTileLink
								WHERE tabouselTileID = <cfqueryparam cfsqltype="cf_sql_integer" value="#local.nestedStrKey.tileID#">
								AND tabouselID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselID#">
								</cfquery>

								<!---tile not already on tab carousel--->
								<cfif local.qry_checkCarouselForTileByID.recordcount EQ 0>

									<!---increment the counter--->
									<cfset local.counter = local.counter + 1>

									<!---insert link--->
									<cfquery>
									INSERT INTO plugin_tabouselTileLink(
									tabouselID,
									tabouselTileID,
									tabouselCategory,
									tabouselOrder)
									VALUES(
									<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tabouselID#">,
									<cfqueryparam cfsqltype="cf_sql_integer" value="#local.nestedStrKey.tileID#">,
									<cfqueryparam cfsqltype="cf_sql_varchar" value="#local.topLevelStrKey.tileTitle#">,
									<cfqueryparam cfsqltype="cf_sql_integer" value="#local.counter#">)
									</cfquery>

								</cfif>
								<!---tile not already on carousel--->

							</cfif>
							<!---valid tile--->

						</cfloop>

					</cfif>
					<!---there are children--->

				</cfif>
				<!---valid top level item struct--->

			</cfloop>

			<cfcatch type="any">

				<cfset status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'An error occurred while processing your update to this tab carousel. Please refresh the page and try again.')>

				<!---restore the carousel--->
				<cfoutput query="local.qry_getCurrentTilesByTabouselID">
					<cfquery>
					INSERT INTO plugin_tabouselTileLink(
					tabouselID,
					tabouselTileID,
					tabouselCategory,
					tabouselOrder)
					VALUES(
					<cfqueryparam cfsqltype="cf_sql_integer" value="#tabouselID#">,
					<cfqueryparam cfsqltype="cf_sql_integer" value="#tabouselTileID#">,
					<cfqueryparam cfsqltype="cf_sql_varchar" value="#tabouselCategory#">,
					<cfqueryparam cfsqltype="cf_sql_integer" value="#tabouselOrder#">)
					</cfquery>
				</cfoutput>

				<cfreturn status>
			</cfcatch>

		</cftry>

		</cftransaction>

		<cfset status["success"] = 1>
		<cfset status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Your update to this tab carousel has been processed successfully.', messageTypeClass = 'success', messageTypeName = 'Success')>

		<cfreturn status>
	</cffunction>



</cfcomponent>
