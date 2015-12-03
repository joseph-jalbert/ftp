<!---CMS Verdicts Plugin Components--->

<cfcomponent displayname="Verdicts Plugin Components" name="verdicts" output="false">

	<!--- call init() automatically when the CFC is instantiated --->
  	<cfset init(application.adminUtils)>

	<cffunction name="init" access="public" output="false" returntype="verdicts">

		<cfargument name="adminUtils" type="component" required="no" default="#application.adminUtils#">

		<!--- Putting the variable in the variables scope makes it available to the init() method as well as all other methods in the CFC--->
		<cfset variables.adminUtils = arguments.adminUtils>

		<cfreturn this />
	</cffunction>

	<cffunction name="resetTags" displayname="Reset Tags" description="I am a required function that can be called to clear all links to a tag that is being deleted" access="remote" output="false" returntype="void">

		<cfargument name="tagID" type="numeric" required="yes" hint="I am a tag that is about to be deleted">

		<!---if this is a practice area tag, then all verdicts using it should be set to the default PA--->
		<cfquery>
		UPDATE plugin_verdicts
		SET verdictPracticeAreaTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE verdictPracticeAreaTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

		<!---if this is an attorney tag, then all verdicts using it for attorney 1 should be set to the default (no attorney)--->
		<cfquery>
		UPDATE plugin_verdicts
		SET verdictAttorney1TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE verdictAttorney1TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

		<!---same for attorney 2 and then attorney 3--->
		<cfquery>
		UPDATE plugin_verdicts
		SET verdictAttorney2TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE verdictAttorney2TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

		<cfquery>
		UPDATE plugin_verdicts
		SET verdictAttorney3TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE verdictAttorney3TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

	</cffunction>

	<cffunction name="validateUserForVerdicts" displayname="Validate User for Verdicts" description="Validates a user for accessing the verdicts plugin" access="private" output="false" returntype="struct">

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
			<cfset local.status["message"] = 'The user does not have sufficient privileges to access verdicts. The "content create" privilege must be added by a system administrator.'>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfif arguments.checkDelete EQ 1>

			<cfif local.qry_getUserbyID.permissions_contentdelete EQ 0>
				<cfset local.status["message"] = 'The user does not have sufficient privileges to access verdicts. The "content delete" privilege must be added by a system administrator.'>
				<cfreturn local.status>
				<cfabort>
			</cfif>

		</cfif>

		<!---user is valid, logged in, and has permission--->
		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = 'User validated successfully.'>

		<cfreturn local.status>
	</cffunction>

	<cffunction name="returnValidStateAbb" displayname="Return Valid State Abb" description="Makes sure the state abbreviation is valid" access="private" output="false" returntype="string">

		<cfargument name="stateAbb" type="string" required="yes" hint="Abbreviation of the state being added">

		<cfset local.qry_getStateByAbb = ''>

		<cfset arguments.stateAbb = trim(arguments.stateAbb)>

		<cfquery name="local.qry_getStateByAbb">
		SELECT TOP 1 stateabb
		FROM state
		WHERE stateabb = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.stateAbb#">
		</cfquery>

		<cfif local.qry_getStateByAbb.recordcount EQ 0>
			<cfset arguments.stateAbb = 'UK'>
		</cfif>

		<cfreturn arguments.stateAbb>
	</cffunction>


	<cffunction name="returnValidPracticeareaTagID" displayname="Return Valid Practice Area Tag ID" description="Makes sure the practice area tag ID is valid" access="private" output="false" returntype="numeric">

		<cfargument name="tagID" type="numeric" required="yes" hint="ID of practice area tag being added">

		<cfset local.qry_getPracticeAreaTagByID = ''>

		<cfquery name="local.qry_getPracticeAreaTagByID">
		SELECT tagID
		FROM tags
		WHERE tagType = <cfqueryparam cfsqltype="cf_sql_integer" value="1">
		AND tagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

		<cfif local.qry_getPracticeAreaTagByID.recordcount EQ 0>
			<cfset arguments.tagID = 0>
		</cfif>

		<cfreturn arguments.tagID>
	</cffunction>

	<cffunction name="returnValidAttorneyTagID" displayname="Return Valid Attorney Tag ID" description="Makes sure ID for the related attorney is a valid attorney tag" access="private" output="false" returntype="numeric">

		<cfargument name="tagID" type="numeric" required="yes" hint="ID of the Attorney tag being linked">

		<cfset local.qry_getAttorneyTagByID = ''>

		<cfquery name="local.qry_getAttorneyTagByID">
		SELECT tagID
		FROM tags
		WHERE tagType = <cfqueryparam cfsqltype="cf_sql_integer" value="4">
		AND tagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

		<cfif local.qry_getAttorneyTagByID.recordcount EQ 0>
			<cfset arguments.tagID = 0>
		</cfif>

		<cfreturn arguments.tagID>
	</cffunction>

	<cffunction name="returnValidOfficeTagID" displayname="Return Valid Office Tag ID" description="Makes sure ID for the related office location is a valid office location tag" access="private" output="false" returntype="numeric">

		<cfargument name="tagID" type="numeric" required="yes" hint="ID of the Office location tag being linked">

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


	<cffunction name="returnValidDate" displayname="Return Valid Date" description="Makes sure the date is valid" access="private" output="false" returntype="string">

		<cfargument name="verdictDate" type="string" required="yes" hint="Date of verdict or settlement.">

		<cfset arguments.verdictDate = trim(arguments.verdictDate)>

		<cfif arguments.verdictDate EQ ''>
			<cfreturn arguments.verdictDate>
			<cfabort>
		</cfif>

		<cfif isDate(arguments.verdictDate) NEQ 'TRUE'>
			<cfset arguments.verdictDate = dateformat(now(),'MM/DD/YYYY')>
		</cfif>

		<cfreturn arguments.verdictDate>
	</cffunction>

	<cffunction name="getVerdictByID" displayname="Get Verdict By ID" description="Gets all the information for a verdict, by ID" access="private" output="false" returntype="query">

		<cfargument name="verdictID" type="numeric" required="yes" hint="ID of verdict">

		<cfset local.qry_getVerdictByID = ''>

		<cfquery name="local.qry_getVerdictByID">
		SELECT TOP 1 verdictID, verdictAmount, verdictPresuitOffer, verdictPracticeAreaTagID, verdictPracticeareaText, verdictAttorney1TagID, verdictAttorney2TagID, verdictAttorney3TagID, verdictDamages, verdictStateAbb, verdictVenue, verdictOfficeTagID, verdictCaseStyle, verdictRulingType, verdictDesc, verdictDate, verdictStatus, verdictUserID, verdictDateAdded
		FROM plugin_verdicts
		WHERE verdictID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictID#">
		</cfquery>

		<cfreturn local.qry_getVerdictByID>
	</cffunction>


	<!---Add Verdict--->
	<cffunction name="verdictAdd" displayname="Add Verdict" description="Validates then adds a new verdict" access="remote" output="false" returntype="struct">

		<!---arguments--->
		<cfargument name="verdictAmount" type="string" required="yes" hint="Verdict or settlement amount">
		<cfargument name="verdictRulingType" type="string" required="yes" hint="Either 'verdict' or 'settlement'">
		<cfargument name="verdictPresuitOffer" type="string" required="yes" hint="I am the amount offered before trial. When I am blank, a negative 1 (-1) will be added to the DB as an indicator">
		<cfargument name="verdictPracticeAreaTagID" type="numeric" required="yes" hint="I am the ID of the related practice area tag">
		<cfargument name="verdictPracticeareaText" type="string" required="yes" hint="A plain text description of the area of law covered by the verdict">
		<cfargument name="verdictDamages" type="string" required="yes" hint="Type of injury or damage sustained">
		<cfargument name="verdictStateAbb" type="string" required="yes" hint="Abbreviation of state where verdict occurred">
		<cfargument name="verdictVenue" type="string" required="yes" hint="Location of type of court where ruling occurred">
		<cfargument name="verdictOfficeTagID" type="numeric" required="yes" hint="I am the ID of a related office location tag">
		<cfargument name="verdictCaseStyle" type="string" required="yes" hint="A description of the style of the case">
		<cfargument name="verdictDate" type="string" required="yes" hint="Date of ruling or date verdict was received. Defaults to today">
		<cfargument name="verdictAttorney1TagID" type="numeric" required="yes" hint="I am the ID of related attorney tag">
		<cfargument name="verdictAttorney2TagID" type="numeric" required="yes" hint="I am the ID of a second related attorney tag">
		<cfargument name="verdictAttorney3TagID" type="numeric" required="yes" hint="I am the ID of a third related attorney tag">
		<cfargument name="verdictDesc" type="string" required="yes" hint="Description of the verdict">
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user adding the verdict">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.validatePATextStruct = structNew()>
		<cfset local.validateVerdictDescStruct = structNew()>
		<cfset local.qry_getTopVerdict = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["verdictID"] = 0>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForVerdicts(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---strip all but numbers for required verdict amount--->
		<cfset arguments.verdictAmount = ReReplaceNoCase(arguments.verdictAmount,"[^0-9]","","ALL")>

		<!---validate verdict amount--->
		<cfif not isnumeric(arguments.verdictAmount)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a valid numeric value for the verdict/settlement amount.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.verdictPracticeareaText = trim(arguments.verdictPracticeareaText)>

		<!---validate practice area text--->
		<cfset local.validatePATextStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.verdictPracticeareaText, stringName = 'the practice area text', maxLength = 150)>
		<cfif local.validatePATextStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validatePATextStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.verdictDesc = trim(arguments.verdictDesc)>
		<!---clean the body of all but p tags--->
		<cfset arguments.verdictDesc = variables.adminUtils.tagStripper(str = arguments.verdictDesc,action = "strip",tagList = "br")>

		<!---validate verdict description--->
		<cfset local.validateVerdictDescStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.verdictDesc, stringName = 'the verdict description', maxLength = 8000)>
		<cfif local.validateVerdictDescStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateVerdictDescStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---enforce valid ruling type--->
		<cfif arguments.verdictRulingType NEQ 'Verdict'> =
			<cfset arguments.verdictRulingType = 'Settlement'>
		</cfif>

		<!---enforce valid presuit offer--->
		<cfset arguments.verdictPresuitOffer = ReReplaceNoCase(arguments.verdictPresuitOffer,"[^0-9]","","ALL")>
		<cfif not isnumeric(arguments.verdictPresuitOffer)>
			<cfset arguments.verdictPresuitOffer = -1>
		</cfif>
		<cfset arguments.verdictPracticeAreaTagID = returnValidPracticeareaTagID(tagID = arguments.verdictPracticeAreaTagID)>
		<cfset arguments.verdictDamages = left(trim(arguments.verdictDamages),250)>
		<cfset arguments.verdictStateAbb = returnValidStateAbb(stateAbb = arguments.verdictStateAbb)>
		<cfset arguments.verdictVenue = left(trim(arguments.verdictVenue),150)>
		<cfset arguments.verdictOfficeTagID = returnValidOfficeTagID(tagID = arguments.verdictOfficeTagID)>
		<cfset arguments.verdictCaseStyle = left(trim(arguments.verdictCaseStyle),150)>
		<cfset arguments.verdictDate = returnValidDate(verdictDate = arguments.verdictDate)>

		<cfset arguments.verdictAttorney1TagID = returnValidAttorneyTagID(tagID = arguments.verdictAttorney1TagID)>
		<cfset arguments.verdictAttorney2TagID = returnValidAttorneyTagID(tagID = arguments.verdictAttorney2TagID)>
		<cfset arguments.verdictAttorney3TagID = returnValidAttorneyTagID(tagID = arguments.verdictAttorney3TagID)>

		<cfquery>
		INSERT INTO plugin_verdicts(
		verdictAmount,
		verdictPresuitOffer,
		verdictPracticeAreaTagID,
		verdictPracticeareaText,
		verdictAttorney1TagID,
		verdictAttorney2TagID,
		verdictAttorney3TagID,
		verdictDamages,
		verdictStateAbb,
		verdictVenue,
		verdictOfficeTagID,
		verdictCaseStyle,
		verdictRulingType,
		verdictDesc,
		verdictDate,
		verdictUserID)
		VALUES(
		<cfqueryparam cfsqltype="cf_sql_numeric" value="#arguments.verdictAmount#">,
		<cfqueryparam cfsqltype="cf_sql_numeric" value="#arguments.verdictPresuitOffer#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictPracticeAreaTagID#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictPracticeareaText#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictAttorney1TagID#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictAttorney2TagID#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictAttorney3TagID#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictDamages#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictStateAbb#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictVenue#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictOfficeTagID#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictCaseStyle#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictRulingType#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictDesc#">,
		<cfif arguments.verdictDate NEQ ''>
			<cfqueryparam cfsqltype="cf_sql_timestamp" value="#dateformat(arguments.verdictDate,'MM/DD/YYYY')# 00:00:00">,
		<cfelse>
			<cfqueryparam value="#arguments.verdictDate#" null="yes">,
		</cfif>
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">)
		</cfquery>

		<cfquery name="local.qry_getTopVerdict">
		SELECT TOP 1 verdictID
		FROM plugin_verdicts
		ORDER BY verdictID DESC
		</cfquery>

		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The verdict was added successfully. You will now be redirected.')>
		<cfset local.status["verdictID"] = local.qry_getTopVerdict.verdictID>

		<cfreturn local.status>
	</cffunction>
	<!---END add verdict--->


	<!---Update verdict--->
	<cffunction name="verdictsSet" displayname="Update Verdict" description="Validates then updates a verdict" access="remote" output="false" returntype="struct">

		<!---arguments--->
		<cfargument name="verdictID" type="numeric" required="yes" hint="I am the ID of the verdict being updated">
		<cfargument name="verdictAmount" type="string" required="yes" hint="Verdict or settlement amount">
		<cfargument name="verdictRulingType" type="string" required="yes" hint="Either 'verdict' or 'settlement'">
		<cfargument name="verdictPresuitOffer" type="string" required="yes" hint="I am the amount offered before trial. When I am blank, a negative 1 (-1) will be added to the DB as an indicator">
		<cfargument name="verdictPracticeAreaTagID" type="numeric" required="yes" hint="I am the ID of the related practice area tag">
		<cfargument name="verdictPracticeareaText" type="string" required="yes" hint="A plain text description of the area of law covered by the verdict">
		<cfargument name="verdictDamages" type="string" required="yes" hint="Type of injury or damage sustained">
		<cfargument name="verdictStateAbb" type="string" required="yes" hint="Abbreviation of state where verdict occurred">
		<cfargument name="verdictVenue" type="string" required="yes" hint="Location of type of court where ruling occurred">
		<cfargument name="verdictOfficeTagID" type="numeric" required="yes" hint="I am the ID of a related office location tag">
		<cfargument name="verdictCaseStyle" type="string" required="yes" hint="A description of the style of the case">
		<cfargument name="verdictDate" type="string" required="yes" hint="Date of ruling or date verdict was received. Defaults to today">
		<cfargument name="verdictAttorney1TagID" type="numeric" required="yes" hint="I am the ID of related attorney tag">
		<cfargument name="verdictAttorney2TagID" type="numeric" required="yes" hint="I am the ID of a second related attorney tag">
		<cfargument name="verdictAttorney3TagID" type="numeric" required="yes" hint="I am the ID of a third related attorney tag">
		<cfargument name="verdictDesc" type="string" required="yes" hint="Description of the verdict">
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user updating the verdict">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.qry_getVerdictByID = ''>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.validatePATextStruct = structNew()>
		<cfset local.validateVerdictDescStruct = structNew()>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["verdictID"] = 0>

		<cfset local.qry_getVerdictByID = getVerdictByID(verdictID = arguments.verdictID)>

		<cfif local.qry_getVerdictByID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForVerdicts(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---strip all but numbers for required verdict amount--->
		<cfset arguments.verdictAmount = ReReplaceNoCase(arguments.verdictAmount,"[^0-9]","","ALL")>

		<!---validate verdict amount--->
		<cfif not isnumeric(arguments.verdictAmount)>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a valid numeric value for the verdict/settlement amount.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.verdictPracticeareaText = trim(arguments.verdictPracticeareaText)>

		<!---validate practice area text--->
		<cfset local.validatePATextStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.verdictPracticeareaText, stringName = 'the practice area text', maxLength = 150)>
		<cfif local.validatePATextStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validatePATextStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.verdictDesc = trim(arguments.verdictDesc)>
		<!---clean the body of all but p tags--->
		<cfset arguments.verdictDesc = variables.adminUtils.tagStripper(str = arguments.verdictDesc,action = "strip",tagList = "br")>

		<!---validate verdict description--->
		<cfset local.validateVerdictDescStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.verdictDesc, stringName = 'the verdict description', maxLength = 8000)>
		<cfif local.validateVerdictDescStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateVerdictDescStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---enforce valid ruling type--->
		<cfif arguments.verdictRulingType NEQ 'Verdict'> =
			<cfset arguments.verdictRulingType = 'Settlement'>
		</cfif>

		<!---enforce valid presuit offer--->
		<cfset arguments.verdictPresuitOffer = ReReplaceNoCase(arguments.verdictPresuitOffer,"[^0-9]","","ALL")>
		<cfif not isnumeric(arguments.verdictPresuitOffer)>
			<cfset arguments.verdictPresuitOffer = -1>
		</cfif>
		<cfset arguments.verdictPracticeAreaTagID = returnValidPracticeareaTagID(tagID = arguments.verdictPracticeAreaTagID)>
		<cfset arguments.verdictDamages = left(trim(arguments.verdictDamages),250)>
		<cfset arguments.verdictStateAbb = returnValidStateAbb(stateAbb = arguments.verdictStateAbb)>
		<cfset arguments.verdictVenue = left(trim(arguments.verdictVenue),150)>
		<cfset arguments.verdictOfficeTagID = returnValidOfficeTagID(tagID = arguments.verdictOfficeTagID)>
		<cfset arguments.verdictCaseStyle = left(trim(arguments.verdictCaseStyle),150)>
		<cfset arguments.verdictDate = returnValidDate(verdictDate = arguments.verdictDate)>

		<cfset arguments.verdictAttorney1TagID = returnValidAttorneyTagID(tagID = arguments.verdictAttorney1TagID)>
		<cfset arguments.verdictAttorney2TagID = returnValidAttorneyTagID(tagID = arguments.verdictAttorney2TagID)>
		<cfset arguments.verdictAttorney3TagID = returnValidAttorneyTagID(tagID = arguments.verdictAttorney3TagID)>

		<cfquery>
		UPDATE plugin_verdicts
		SET verdictAmount = <cfqueryparam cfsqltype="cf_sql_numeric" value="#arguments.verdictAmount#">,
		verdictPresuitOffer = <cfqueryparam cfsqltype="cf_sql_numeric" value="#arguments.verdictPresuitOffer#">,
		verdictPracticeAreaTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictPracticeAreaTagID#">,
		verdictPracticeareaText = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictPracticeareaText#">,
		verdictAttorney1TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictAttorney1TagID#">,
		verdictAttorney2TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictAttorney2TagID#">,
		verdictAttorney3TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictAttorney3TagID#">,
		verdictDamages = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictDamages#">,
		verdictStateAbb = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictStateAbb#">,
		verdictVenue = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictVenue#">,
		verdictOfficeTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictOfficeTagID#">,
		verdictCaseStyle = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictCaseStyle#">,
		verdictRulingType = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictRulingType#">,
		verdictDesc = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.verdictDesc#">,
		<cfif arguments.verdictDate NEQ ''>
			verdictDate = <cfqueryparam cfsqltype="cf_sql_timestamp" value="#dateformat(arguments.verdictDate,'MM/DD/YYYY')# 00:00:00">,
		<cfelse>
			verdictDate = <cfqueryparam value="#arguments.verdictDate#" null="yes">,
		</cfif>
		verdictUserID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">
		WHERE verdictID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictID#">
		</cfquery>

		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The verdict was updated successfully.')>
		<cfset local.status["verdictID"] = arguments.verdictID>

		<cfreturn local.status>
	</cffunction>
	<!---END update verdict--->

	<!---DELETE verdict (disable)--->
	<cffunction name="verdictDelete" displayname="Delete Verdict" description="Delete's a verdict" access="remote" output="false" returntype="struct">

		<cfargument name="verdictID" type="numeric" required="yes" hint="ID of verdict being removed">
		<cfargument name="userID" type="numeric" required="yes" hint="ID of the user deleting the verdict">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getVerdictByID = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForVerdicts(userID = arguments.userID, checkDelete = 1)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---validate verdict--->
		<cfset local.qry_getVerdictByID = getVerdictByID(verdictID = arguments.verdictID)>

		<cfif local.qry_getVerdictByID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfquery>
		UPDATE plugin_verdicts
		SET verdictStatus = <cfqueryparam cfsqltype="cf_sql_integer" value="0">,
		verdictUserID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">
		WHERE verdictID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.verdictID#">
		</cfquery>

		<cfset local.status["success"] = 1>
        <cfset local.status["message"] =  variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The verdict has been deleted successfully.')>

		<cfreturn local.status>
	</cffunction>
	<!---END DELETE--->

</cfcomponent>
