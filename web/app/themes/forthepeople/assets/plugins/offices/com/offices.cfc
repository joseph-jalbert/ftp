<!---CMS Offices Plugin Components--->

<cfcomponent displayname="Offices Plugin Components" name="offices" output="false">

	<!--- call init() automatically when the CFC is instantiated --->
  	<cfset init(application.adminUtils)>

	<cffunction name="init" access="public" output="false" returntype="offices">

		<cfargument name="adminUtils" type="component" required="no" default="#application.adminUtils#">

		<!--- Putting the variable in the variables scope makes it available to the init() method as well as all other methods in the CFC--->
		<cfset variables.adminUtils = arguments.adminUtils>

		<cfreturn this />
	</cffunction>

	<cffunction name="resetTags" displayname="Reset Tags" description="I am a required function that can be called to clear all links to a tag that is being deleted" access="remote" output="false" returntype="void">

		<cfargument name="tagID" type="numeric" required="yes" hint="I am a tag that is about to be deleted">

		<cfquery>
		UPDATE plugin_offices
		SET officeOfficeTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE officeOfficeTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

	</cffunction>

	<cffunction name="validateUserForOffices" displayname="Validate User for Offices" description="Validates a user for accessing the offices plugin" access="private" output="false" returntype="struct">

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
			<cfset local.status["message"] = 'The user does not have sufficient privileges to access office locations. The "content create" privilege must be added by a system administrator.'>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfif arguments.checkDelete EQ 1>

			<cfif local.qry_getUserbyID.permissions_contentdelete EQ 0>
				<cfset local.status["message"] = 'The user does not have sufficient privileges to access office locations. The "content delete" privilege must be added by a system administrator.'>
				<cfreturn local.status>
				<cfabort>
			</cfif>

		</cfif>

		<!---user is valid, logged in, and has permission--->
		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = 'User validated successfully.'>

		<cfreturn local.status>
	</cffunction>

	<cffunction name="returnValidStateAndAbb" displayname="Return Valid State And Abbreviation" description="Makes sure the state name and abbreviation are valid" access="private" output="false" returntype="struct">

		<cfargument name="stateAbb" type="string" required="yes" hint="Abbreviation of the state being added">

		<cfset local.stateStruct = structNew()>
		<cfset local.qry_getStateByAbb = ''>

		<cfset local.stateStruct["stateabb"] = 'UK'>
		<cfset local.stateStruct["state"] = 'Unknown'>

		<cfset arguments.stateAbb = trim(arguments.stateAbb)>

		<cfquery name="local.qry_getStateByAbb">
		SELECT TOP 1 state, stateabb
		FROM state
		WHERE stateabb = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.stateAbb#">
		</cfquery>

		<cfif local.qry_getStateByAbb.recordcount EQ 1>
			<cfset local.stateStruct["stateabb"] = qry_getStateByAbb.stateabb>
			<cfset local.stateStruct["state"] = qry_getStateByAbb.state>
		</cfif>

		<cfreturn local.stateStruct>
	</cffunction>

	<cffunction name="returnValidOfficeTagID" displayname="Return Valid Office Tag ID" description="Makes sure ID for the related office is a valid office location tag" access="private" output="false" returntype="numeric">

		<cfargument name="tagID" type="numeric" required="yes" hint="ID of the Office tag being linked">

		<cfset local.qry_getOfficeTagByID = ''>

		<cfquery name="local.qry_getOfficeTagByID">
		SELECT tagID
		FROM tags
		WHERE tagType = <cfqueryparam cfsqltype="cf_sql_integer" value="3">
		AND tagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

		<cfif local.qry_getOfficeTagByID.recordcount EQ 0>
			<cfset arguments.tagID = 0>
		</cfif>

		<cfreturn arguments.tagID>
	</cffunction>

	<cffunction name="validateLatLon" displayname="Validate Latitude and Longitude" description="Makes sure that the lat and lon are valid" access="private" output="false" returntype="boolean">

		<cfargument name="latitude" type="string" required="yes">
		<cfargument name="longitude" type="string" required="yes">

		<cfif not isnumeric(arguments.latitude) or not isnumeric(arguments.longitude)>
			<cfreturn false>
			<cfabort>
		</cfif>

		<cfif arguments.latitude GT 90 OR arguments.latitude LT -90 OR arguments.longitude GT 180 OR arguments.longitude LT -180>
			<cfreturn false>
			<cfabort>
		</cfif>

		<cfreturn true>
	</cffunction>


	<cffunction name="getOfficeByID" displayname="Get Office By ID" description="Gets all the information for an office, by ID" access="private" output="false" returntype="query">

		<cfargument name="officeID" type="numeric" required="yes" hint="ID of office">

		<cfset local.qry_getOfficeByID = ''>

		<cfquery name="local.qry_getOfficeByID">
		SELECT TOP 1 officeID, officeOfficeTagID, officeAddressStreet, officeAddressSuite, officeCity, officeStateAbb, officeState, officeZip, officePhone, officeDirectionURL, officeLat, officeLon, officeShortDesc, officeDesc, officeOrder, officeStatus, officeUserID, officeDateAdded
		FROM plugin_offices
		WHERE officeID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.officeID#">
		</cfquery>

		<cfreturn local.qry_getOfficeByID>
	</cffunction>


	<!---Add Office--->
	<cffunction name="officeAdd" displayname="Add Office Location" description="Validates then adds a new office" access="remote" output="false" returntype="struct">

		<!---arguments--->
		<cfargument name="officeOfficeTagID" type="numeric" required="yes" hint="I am the ID of the tag this office location is linked to">
		<cfargument name="officeCity" type="string" required="yes" hint="I am the city the office is located it">
		<cfargument name="officeAddressStreet" type="string" required="yes" hint="I am the office street address">
		<cfargument name="officeAddressSuite" type="string" required="yes" hint="I am the suite information for the address, if applicable">
		<cfargument name="officeStateAbb" type="string" required="yes" hint="I am the abbreviation of the state that the office is in">
		<cfargument name="officeZip" type="string" required="yes" hint="I am the 5 digit zip code of the office">
		<cfargument name="officePhone" type="string" required="yes" hint="I am the 10 digit phone number of the office">
		<cfargument name="officeDirectionURL" type="string" required="yes" hint="I am the link to the map and directions in Google maps">
		<cfargument name="officeLat" type="string" required="yes" hint="I am the latitude of the office">
		<cfargument name="officeLon" type="string" required="yes" hint="I am the longitude of the office">
		<cfargument name="officeShortDesc" type="string" required="yes" hint="I am a short description of the office">
		<cfargument name="officeDesc" type="string" required="yes" hint="I am the longer description of the office">
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user adding the office">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.validateRequiredFieldStruct = structNew()>
		<cfset local.urlStruct = structNew()>
		<cfset local.stateStruct = structNew()>
		<cfset local.qry_getTopOffice = ''>
		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["officeID"] = 0>
        <cfset local.officeOrder = ''>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForOffices(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---clean city--->
		<cfset arguments.officeCity = replacenocase(arguments.officeCity,'Enter the Office City Here','','all')>
		<cfset arguments.officeCity = left(trim(arguments.officeCity),50)>

		<!---validate city name--->
		<cfset local.validateRequiredFieldStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.officeCity, stringName = 'the office city', maxLength = 50)>
		<cfif local.validateRequiredFieldStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateRequiredFieldStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---Trim and enforce length on other text fields--->
		<cfset arguments.officeAddressStreet = left(trim(arguments.officeAddressStreet),150)>
		<cfset arguments.officeAddressSuite = left(trim(arguments.officeAddressSuite),50)>
		<cfset arguments.officeZip = left(trim(arguments.officeZip),5)>
		<!---Trim away leading 1 in phone numbers, No area codes start with 1--->
		<cfif left(trim(arguments.officePhone),1) EQ 1>
			<cfset arguments.officePhone = replace(arguments.officePhone,'1','')>
		</cfif>
		<cfset arguments.officePhone = left(rereplace(arguments.officePhone,'[^0-9]','','all'),10)>
		<cfset arguments.officeDirectionURL = left(trim(arguments.officeDirectionURL),500)>
		<cfset arguments.officeShortDesc = variables.adminUtils.tagStripper(str = arguments.officeShortDesc,action = "strip",tagList = "br,strong,a")>
		<cfset arguments.officeShortDesc = left(trim(arguments.officeShortDesc),250)>
		<cfset arguments.officeDesc = variables.adminUtils.tagStripper(str = arguments.officeDesc,action = "strip",tagList = "br,strong,a")>
		<cfset arguments.officeDesc = left(trim(arguments.officeDesc),1000)>

		<!---SIMPLE (not blank) validation--->
		<!---validate street address--->
		<cfset local.validateRequiredFieldStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.officeAddressStreet, stringName = 'the office street address')>
		<cfif local.validateRequiredFieldStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateRequiredFieldStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		<!---validate description--->
		<cfset local.validateRequiredFieldStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.officeDesc, stringName = 'the office description')>
		<cfif local.validateRequiredFieldStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateRequiredFieldStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		<!---COMPLEX VALIDATION AND CORRECT VALUE ENFORCEMENT (URL, Lat/Lon, zip code, state/stateabb)--->
		<!---map/direction URL--->
		<cfset urlStruct = variables.adminUtils.advancedURLRegex(URL = arguments.officeDirectionURL)>
		<cfif variables.adminUtils.isURL(arguments.officeDirectionURL) NEQ 'True' or urlStruct.protocol EQ '' or urlStruct.domain EQ '' or urlStruct.domainEnding EQ ''>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please ensure that the URL for the office map/directions is valid.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		<!---lat lon--->
		<cfif validateLatLon(latitude = arguments.officeLat, longitude = arguments.officeLon) NEQ 'True'>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please ensure that the latitude and longitude for the office are valid.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		<!---zip code--->
		<cfif not isvalid('zipcode',arguments.officeZip)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a valid 5 digit zip code for the office location zip code.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		<!---telephone--->
		<cfif arguments.officePhone NEQ '' and not isvalid('telephone',arguments.officePhone)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a valid 10 digit telephone for the office or leave the field blank')>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.officeOfficeTagID = returnValidOfficeTagID(tagID = arguments.officeOfficeTagID)>
		<!---get/set valid state information--->
		<cfset local.stateStruct = returnValidStateAndAbb(stateAbb = arguments.officeStateAbb)>


        <!---get/set OfficeOrder (pushed to bottom of stack)--->
        <cfquery name="getLastInOrder">
        SELECT TOP 1 officeOrder
        FROM plugin_offices
        ORDER BY officeOrder DESC
        </cfquery>

        <cfif getLastInOrder.recordcount EQ 1>
        	<cfset local.officeOrder = getLastInOrder.officeOrder + 1>
        <cfelse>
        	<cfset local.officeOrder = 1>
        </cfif>

		<cfquery>
		INSERT INTO plugin_offices(
		officeOfficeTagID,
		officeAddressStreet,
		officeAddressSuite,
		officeCity,
		officeStateAbb,
		officeState,
		officeZip,
		officePhone,
		officeDirectionURL,
		officeLat,
		officeLon,
		officeShortDesc,
		officeDesc,
        officeOrder,
		officeUserID)
		VALUES(
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.officeOfficeTagID#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeAddressStreet#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeAddressSuite#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeCity#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#local.stateStruct.stateabb#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#local.stateStruct.state#">,
		<cfqueryparam cfsqltype="cf_sql_char" value="#arguments.officeZip#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officePhone#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeDirectionURL#">,
		<cfqueryparam cfsqltype="cf_sql_decimal" value="#arguments.officeLat#" scale="6">,
		<cfqueryparam cfsqltype="cf_sql_decimal" value="#arguments.officeLon#" scale="6">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeShortDesc#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeDesc#">,
        <cfqueryparam cfsqltype="cf_sql_integer" value="#local.officeOrder#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">)
		</cfquery>

		<cfquery name="local.qry_getTopOffice">
		SELECT TOP 1 officeID
		FROM plugin_offices
		ORDER BY officeID DESC
		</cfquery>

		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The office was added successfully. You will now be redirected.')>
		<cfset local.status["officeID"] = local.qry_getTopOffice.officeID>

		<cfreturn local.status>
	</cffunction>
	<!---END add office--->


	<!---Update Office--->
	<cffunction name="officeSet" displayname="Update Office" description="Validates then updates an office" access="remote" output="false" returntype="struct">

		<!---arguments--->
		<cfargument name="officeID" type="numeric" required="yes" hint="I am the ID of the office location being updated">
		<cfargument name="officeOfficeTagID" type="numeric" required="yes" hint="I am the ID of the tag this office location is linked to">
		<cfargument name="officeCity" type="string" required="yes" hint="I am the city the office is located it">
		<cfargument name="officeAddressStreet" type="string" required="yes" hint="I am the office street address">
		<cfargument name="officeAddressSuite" type="string" required="yes" hint="I am the suite information for the address, if applicable">
		<cfargument name="officeStateAbb" type="string" required="yes" hint="I am the abbreviation of the state that the office is in">
		<cfargument name="officeZip" type="string" required="yes" hint="I am the 5 digit zip code of the office">
		<cfargument name="officePhone" type="string" required="yes" hint="I am the 10 digit phone number of the office">
		<cfargument name="officeDirectionURL" type="string" required="yes" hint="I am the link to the map and directions in Google maps">
		<cfargument name="officeLat" type="string" required="yes" hint="I am the latitude of the office">
		<cfargument name="officeLon" type="string" required="yes" hint="I am the longitude of the office">
		<cfargument name="officeShortDesc" type="string" required="yes" hint="I am a short description of the office">
		<cfargument name="officeDesc" type="string" required="yes" hint="I am the longer description of the office">
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user adding the office">


		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.qry_getOfficeByID = ''>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.validateRequiredFieldStruct = structNew()>
		<cfset local.urlStruct = structNew()>
		<cfset local.stateStruct = structNew()>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["officeID"] = 0>

		<cfset local.qry_getOfficeByID = getOfficeByID(officeID = arguments.officeID)>

		<cfif local.qry_getOfficeByID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForOffices(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---clean city--->
		<cfset arguments.officeCity = replacenocase(arguments.officeCity,'Enter the Office City Here','','all')>
		<cfset arguments.officeCity = left(trim(arguments.officeCity),50)>

		<!---validate city name--->
		<cfset local.validateRequiredFieldStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.officeCity, stringName = 'the office city', maxLength = 50)>
		<cfif local.validateRequiredFieldStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateRequiredFieldStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---Trim and enforce length on other text fields--->
		<cfset arguments.officeAddressStreet = left(trim(arguments.officeAddressStreet),150)>
		<cfset arguments.officeAddressSuite = left(trim(arguments.officeAddressSuite),50)>
		<cfset arguments.officeZip = left(trim(arguments.officeZip),5)>
		<!---Trim away leading 1 in phone numbers, No area codes start with 1--->
		<cfif left(trim(arguments.officePhone),1) EQ 1>
			<cfset arguments.officePhone = replace(arguments.officePhone,'1','')>
		</cfif>
		<cfset arguments.officePhone = left(rereplace(arguments.officePhone,'[^0-9]','','all'),10)>
		<cfset arguments.officeDirectionURL = left(trim(arguments.officeDirectionURL),500)>
		<cfset arguments.officeShortDesc = variables.adminUtils.tagStripper(str = arguments.officeShortDesc,action = "strip",tagList = "br,strong,a")>
		<cfset arguments.officeShortDesc = left(trim(arguments.officeShortDesc),250)>
		<cfset arguments.officeDesc = variables.adminUtils.tagStripper(str = arguments.officeDesc,action = "strip",tagList = "br,strong,a")>
		<cfset arguments.officeDesc = left(trim(arguments.officeDesc),1000)>

		<!---SIMPLE (not blank) validation--->
		<!---validate street address--->
		<cfset local.validateRequiredFieldStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.officeAddressStreet, stringName = 'the office street address')>
		<cfif local.validateRequiredFieldStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateRequiredFieldStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		<!---validate description--->
		<cfset local.validateRequiredFieldStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.officeDesc, stringName = 'the office description')>
		<cfif local.validateRequiredFieldStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateRequiredFieldStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		<!---COMPLEX VALIDATION AND CORRECT VALUE ENFORCEMENT (URL, Lat/Lon, zip code, state/stateabb)--->
		<!---map/direction URL--->
		<cfset urlStruct = variables.adminUtils.advancedURLRegex(URL = arguments.officeDirectionURL)>
		<cfif variables.adminUtils.isURL(arguments.officeDirectionURL) NEQ 'True' or urlStruct.protocol EQ '' or urlStruct.domain EQ '' or urlStruct.domainEnding EQ ''>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please ensure that the URL for the office map/directions is valid.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		<!---lat lon--->
		<cfif validateLatLon(latitude = arguments.officeLat, longitude = arguments.officeLon) NEQ 'True'>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please ensure that the latitude and longitude for the office are valid.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		<!---zip code--->
		<cfif not isvalid('zipcode',arguments.officeZip)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a valid 5 digit zip code for the office location zip code.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		<!---telephone--->
		<cfif arguments.officePhone NEQ '' and not isvalid('telephone',arguments.officePhone)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a valid 10 digit telephone for the office or leave the field blank')>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.officeOfficeTagID = returnValidOfficeTagID(tagID = arguments.officeOfficeTagID)>
		<!---get/set valid state information--->
		<cfset local.stateStruct = returnValidStateAndAbb(stateAbb = arguments.officeStateAbb)>

		<cfquery>
		UPDATE plugin_offices
		SET officeOfficeTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.officeOfficeTagID#">,
		officeAddressStreet = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeAddressStreet#">,
		officeAddressSuite = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeAddressSuite#">,
		officeCity = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeCity#">,
		officeStateAbb = <cfqueryparam cfsqltype="cf_sql_varchar" value="#local.stateStruct.stateabb#">,
		officeState = <cfqueryparam cfsqltype="cf_sql_varchar" value="#local.stateStruct.state#">,
		officeZip = <cfqueryparam cfsqltype="cf_sql_char" value="#arguments.officeZip#">,
		officePhone = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officePhone#">,
		officeDirectionURL = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeDirectionURL#">,
		officeLat = <cfqueryparam cfsqltype="cf_sql_decimal" value="#arguments.officeLat#" scale="6">,
		officeLon = <cfqueryparam cfsqltype="cf_sql_decimal" value="#arguments.officeLon#" scale="6">,
		officeShortDesc = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeShortDesc#">,
		officeDesc = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.officeDesc#">,
		officeUserID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">
		WHERE officeID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.officeID#">
		</cfquery>

		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The office was updated successfully.')>
		<cfset local.status["officeID"] = arguments.officeID>

		<cfreturn local.status>
	</cffunction>
	<!---END update office--->

	<!---DELETE office (disable)--->
	<cffunction name="officeDelete" displayname="Delete Office" description="Delete's an office" access="remote" output="false" returntype="struct">

		<cfargument name="officeID" type="numeric" required="yes" hint="ID of office being removed">
		<cfargument name="userID" type="numeric" required="yes" hint="ID of the user deleting the office">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getOfficeByID = ''>
        <cfset local.newOrder = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForOffices(userID = arguments.userID, checkDelete = 1)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---validate office--->
		<cfset local.qry_getOfficeByID = getOfficeByID(officeID = arguments.officeID)>

		<cfif local.qry_getOfficeByID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfquery>
		UPDATE plugin_offices
		SET officeStatus = <cfqueryparam cfsqltype="cf_sql_integer" value="0">,
		officeUserID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">
		WHERE officeID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.officeID#">
		</cfquery>

        <!---update officeOrder--->
        <cfquery name="getOffices">
        SELECT officeID, officeOrder
        FROM plugin_offices
        WHERE officeStatus = <cfqueryparam cfsqltype="cf_sql_integer" value="1">
        AND officeOrder >  <cfqueryparam cfsqltype="cf_sql_integer" value="#local.qry_getOfficeByID.officeOrder#">
        </cfquery>

        <cfoutput query="getOffices">
			<cfset local.newOrder = getOffices.officeOrder - 1>

            <cfquery>
            UPDATE plugin_offices
            SET officeOrder = <cfqueryparam cfsqltype="cf_sql_integer" value="#local.newOrder#">
            WHERE officeID = <cfqueryparam cfsqltype="cf_sql_integer" value="#getOffices.officeID#">
            </cfquery>
        </cfoutput>

		<cfset local.status["success"] = 1>
        <cfset local.status["message"] =  variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The office has been deleted successfully.')>

		<cfreturn local.status>
	</cffunction>
	<!---END DELETE--->

    <!---Set the Office Order--->
    <cffunction name="SetOfficeOrder" displayname="Set Office Order" description="" access="remote" output="false" returntype="struct">

        <cfargument name="id" type="string" required="yes" hint="ID of the row that is moved (sent as pageRow-OfficeID)">
        <cfargument name="fromPosition" required="yes" type="numeric" hint="intial position(officeOrder) of the row that is moved.">
        <cfargument name="toPosition" required="yes" type="numeric" hint="new position(officeOrder) where row is dropped.">
        <cfargument name="direction" required="yes" type="string" hint="direction the stack is moving in relation to the row (forward or back)">

        <!---var scope--->
		<cfset local.status = structNew()>
        <cfset local.officeID = ''>
        <cfset local.newOrder = ''>

        <cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>

        <!---remove "pageRow-" from the incoming id arg, leaving just the officeID--->
        <cfset local.officeID = REReplace(arguments.id,'[^\d]','','ALL')>


       	<!---if the officeOrder is moving back (1 to 2) then the direction the stack (in this case, the offices being increased in order) is moving is forward.--->
        <!---we only need to change the officeOrder of offices less than the toPosition--->
        <cfif arguments.direction EQ "forward">

            <cfquery>
            UPDATE plugin_offices
            SET officeOrder =  <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.toPosition#">
            WHERE officeID = <cfqueryparam cfsqltype="cf_sql_integer" value="#local.officeID#">
            </cfquery>


            <cfquery name="getForwardRows">
            SELECT officeID, officeOrder
            FROM plugin_offices
            WHERE officeOrder <= <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.toPosition#">
            AND officeOrder > <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.fromPosition#">
            AND officeID <> <cfqueryparam cfsqltype="cf_sql_integer" value="#local.officeID#">
            AND officeStatus = <cfqueryparam cfsqltype="cf_sql_integer" value="1">
            </cfquery>


            <cfoutput query="getForwardRows">

                <cfset local.newOrder = getForwardRows.officeOrder - 1>

                <cfquery>
                UPDATE plugin_offices
                SET officeOrder = <cfqueryparam cfsqltype="cf_sql_integer" value="#local.newOrder#">
                WHERE officeID = <cfqueryparam cfsqltype="cf_sql_integer" value="#getForwardRows.officeID#">
                </cfquery>

            </cfoutput>

        <!---if the officeOrder is moving forward (2 to 1) then the direction the stack (in this case, the offices being decreased in order) is moving is back.--->
        <!---we only need to change the officeOrder of offices less than the fromPosition--->
        <cfelseif arguments.direction EQ "back">

            <cfquery>
            UPDATE plugin_offices
            SET officeOrder =  <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.toPosition#">
            WHERE officeID = <cfqueryparam cfsqltype="cf_sql_integer" value="#local.officeID#">
            </cfquery>



                <cfquery name="getBackRows">
                SELECT officeID, officeOrder
                FROM plugin_offices
                WHERE officeOrder < <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.fromPosition#">
                AND officeOrder >= <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.toPosition#">
                AND officeID <> <cfqueryparam cfsqltype="cf_sql_integer" value="#local.officeID#">
                AND officeStatus = <cfqueryparam cfsqltype="cf_sql_integer" value="1">
                </cfquery>


                <cfoutput query="getBackRows">

                    <cfset local.newOrder = getBackRows.officeOrder + 1>

                    <cfquery>
                    UPDATE plugin_offices
                    SET officeOrder = <cfqueryparam cfsqltype="cf_sql_integer" value="#local.newOrder#">
                    WHERE officeID = <cfqueryparam cfsqltype="cf_sql_integer" value="#getBackRows.officeID#">
                    </cfquery>

            </cfoutput>

        </cfif>

       	<cfset local.status["success"] = 1>
        <cfset local.status["message"] =  variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The office order has been updated successfully.')>

    	<cfreturn local.status>
    </cffunction>
    <!---END Set Office Order--->
</cfcomponent>
