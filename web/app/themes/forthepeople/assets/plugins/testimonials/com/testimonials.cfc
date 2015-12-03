<!---CMS Testimonials Plugin Components--->

<cfcomponent displayname="Testimonials Plugin Components" name="testimonials" output="false"> 
	
	<!--- call init() automatically when the CFC is instantiated --->
  	<cfset init(application.adminUtils)>

	<cffunction name="init" access="public" output="false" returntype="testimonials">
		
		<cfargument name="adminUtils" type="component" required="no" default="#application.adminUtils#">
		
		<!--- Putting the variable in the variables scope makes it available to the init() method as well as all other methods in the CFC--->
		<cfset variables.adminUtils = arguments.adminUtils>
		
		<cfreturn this />
	</cffunction>
	
	<cffunction name="resetTags" displayname="Reset Tags" description="I am a required function that can be called to clear all links to a tag that is being deleted" access="remote" output="false" returntype="void">
	
		<cfargument name="tagID" type="numeric" required="yes" hint="I am a tag that is about to be deleted">
		
		<!---if this is a practice area tag, then all testimonials using it should be set to the default PA--->
		<cfquery>
		UPDATE plugin_testimonials
		SET testimonialPracticeAreaTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE testimonialPracticeAreaTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>
		
		<!---if this is an attorney tag, then all testimonials using it should be set to the default (no attorney)--->
		<cfquery>
		UPDATE plugin_testimonials
		SET testimonialAttorney1TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE testimonialAttorney1TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>
		
		<cfquery>
		UPDATE plugin_testimonials
		SET testimonialAttorney2TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE testimonialAttorney2TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>
	
	</cffunction>
	
	<cffunction name="validateUserForTestimonials" displayname="Validate User for Testimonials" description="Validates a user for accessing the testimonials plugin" access="private" output="false" returntype="struct">
		
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
			<cfset local.status["message"] = 'The user does not have sufficient privileges to access testimonials. The "content create" privilege must be added by a system administrator.'>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		
		<cfif arguments.checkDelete EQ 1>
		
			<cfif local.qry_getUserbyID.permissions_contentdelete EQ 0>
				<cfset local.status["message"] = 'The user does not have sufficient privileges to access testimonials. The "content delete" privilege must be added by a system administrator.'>
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
	
	
	<cffunction name="returnValidDate" displayname="Return Valid Date" description="Makes sure the testimonial date is valid" access="private" output="false" returntype="string">
		
		<cfargument name="testimonialDate" type="string" required="yes" hint="Date of testimonial.">

		<cfset arguments.testimonialDate = trim(arguments.testimonialDate)>

		<cfif isDate(arguments.testimonialDate) NEQ 'TRUE'>
			<cfset arguments.testimonialDate = dateformat(now(),'MM/DD/YYYY')>
		</cfif>
		
		<cfreturn arguments.testimonialDate>
	</cffunction>
	
	<cffunction name="getTestimonialByID" displayname="Get Testimonial By ID" description="Gets all the information for a testimonial, by ID" access="private" output="false" returntype="query">
		
		<cfargument name="testimonialID" type="numeric" required="yes" hint="ID of testimonial">
		
		<cfset local.qry_getTestimonialByID = ''>

		<cfquery name="local.qry_getTestimonialByID">
		SELECT TOP 1  testimonialID, testimonialClient, testimonialCity, testimonialStateabb, testimonialPracticeAreaTagID, testimonialAttorney1TagID, testimonialAttorney2TagID, testimonialOfficeTagID, testimonialEmphasis, testimonialText, testimonialDate, testimonialStatus, testimonialUserID, testimonialDateAdded
		FROM plugin_testimonials
		WHERE testimonialID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.testimonialID#">
		</cfquery>
		
		<cfreturn local.qry_getTestimonialByID>
	</cffunction>

	
	<!---Add Testimonial--->
	<cffunction name="testimonialAdd" displayname="Add Testimonial" description="Validates then adds a new testimonial" access="remote" output="false" returntype="struct">
	
		<!---arguments--->
		<cfargument name="testimonialClient" type="string" required="yes" hint="Name of client in testimonial">
		<cfargument name="testimonialCity" type="string" required="yes" hint="Venue or client city for testimonial">
		<cfargument name="testimonialStateabb" type="string" required="yes" hint="Venue or client state abbreviation">
		<cfargument name="testimonialPracticeAreaTagID" type="numeric" required="yes" hint="I am the ID of the related practice area tag">
		<cfargument name="testimonialAttorney1TagID" type="numeric" required="yes" hint="I am the ID of related attorney tag">
		<cfargument name="testimonialAttorney2TagID" type="numeric" required="yes" hint="I am the ID of related attorney tag">
		<cfargument name="testimonialOfficeTagID" type="numeric" required="yes" hint="I am the ID of related office tag">
		<cfargument name="testimonialDate" type="string" required="yes" hint="Date of event or date testimonial was received. Defaults to today.">
		<cfargument name="testimonialEmphasis" type="string" required="yes" hint="Passage of testimonial to highlight or emphasize">
		<cfargument name="testimonialText" type="string" required="yes" hint="Full text of testimonial">
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user adding the testimonial">
		
		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.validateClientStruct = structNew()>
		<cfset local.validateTestimonialStruct = structNew()>
		<cfset local.qry_getTopTestimonial = ''>
		
		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["testimonialID"] = 0>
		
		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForTestimonials(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.testimonialClient = replacenocase(arguments.testimonialClient,'Enter Client Name Here','','all')>
		<cfset arguments.testimonialClient = left(trim(arguments.testimonialClient),100)>
		
		<!---validate client name--->
		<cfset local.validateClientStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.testimonialClient, stringName = 'the client name')>
		<cfif local.validateClientStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateClientStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		
		<cfset arguments.testimonialText = trim(arguments.testimonialText)>
		<!---clean the body of all but p tags--->
		<cfset arguments.testimonialText = variables.adminUtils.tagStripper(str = arguments.testimonialText,action = "strip",tagList = "br")>
		
		<!---validate testimonial body--->
		<cfset local.validateTestimonialStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.testimonialText, stringName = 'the testimonial text')>
		<cfif local.validateTestimonialStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateTestimonialStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		
		<cfset arguments.testimonialCity = left(trim(arguments.testimonialCity),50)>
		<cfset arguments.testimonialStateabb = returnValidStateAbb(stateAbb = arguments.testimonialStateabb)>
		<cfset arguments.testimonialPracticeAreaTagID = returnValidPracticeareaTagID(tagID = arguments.testimonialPracticeAreaTagID)>
		<cfset arguments.testimonialAttorney1TagID = returnValidAttorneyTagID(tagID = arguments.testimonialAttorney1TagID)>
		<cfset arguments.testimonialAttorney2TagID = returnValidAttorneyTagID(tagID = arguments.testimonialAttorney2TagID)>
		<cfset arguments.testimonialOfficeTagID = returnValidOfficeTagID(tagID = arguments.testimonialOfficeTagID)>
		<cfset arguments.testimonialDate = returnValidDate(testimonialDate = arguments.testimonialDate)>
		<!---strip all tags from the emphasis section--->
		<cfset arguments.testimonialEmphasis = variables.adminUtils.tagStripper(str = arguments.testimonialEmphasis,action = "strip")>
		<cfset arguments.testimonialEmphasis = left(trim(arguments.testimonialEmphasis),250)>
		
		<cfquery>
		INSERT INTO plugin_testimonials(
		testimonialClient,
		testimonialCity,
		testimonialStateabb,
		testimonialPracticeAreaTagID,
		testimonialAttorney1TagID,
		testimonialAttorney2TagID,
		testimonialOfficeTagID,
		testimonialEmphasis,
		testimonialText,
		testimonialDate,
		testimonialUserID)
		VALUES(
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.testimonialClient#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.testimonialCity#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.testimonialStateabb#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.testimonialPracticeAreaTagID#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.testimonialAttorney1TagID#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.testimonialAttorney2TagID#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.testimonialOfficeTagID#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.testimonialEmphasis#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.testimonialText#">,
		<cfqueryparam cfsqltype="cf_sql_timestamp" value="#dateformat(arguments.testimonialDate,'MM/DD/YYYY')# 00:00:00">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">)
		</cfquery>
		
		<cfquery name="local.qry_getTopTestimonial">
		SELECT TOP 1 testimonialID
		FROM plugin_testimonials
		ORDER BY testimonialID DESC
		</cfquery>
		
		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The testimonial was added successfully. You will now be redirected.')>
		<cfset local.status["testimonialID"] = local.qry_getTopTestimonial.testimonialID>
		
		<cfreturn local.status>
	</cffunction>
	<!---END add testimonial--->


	<!---Update Testimonial--->
	<cffunction name="testimonialSet" displayname="Update Testimonial" description="Validates then updates a testimonial" access="remote" output="false" returntype="struct">
	
		<!---arguments--->
		<cfargument name="testimonialID" type="numeric" required="yes" hint="I am the ID of the testimonial being updated">
		<cfargument name="testimonialClient" type="string" required="yes" hint="Name of client in testimonial">
		<cfargument name="testimonialCity" type="string" required="yes" hint="Venue or client city for testimonial">
		<cfargument name="testimonialStateabb" type="string" required="yes" hint="Venue or client state abbreviation">
		<cfargument name="testimonialPracticeAreaTagID" type="numeric" required="yes" hint="I am the ID of the related practice area tag">
		<cfargument name="testimonialAttorney1TagID" type="numeric" required="yes" hint="I am the ID of related attorney tag">
		<cfargument name="testimonialAttorney2TagID" type="numeric" required="yes" hint="I am the ID of related attorney tag">
		<cfargument name="testimonialOfficeTagID" type="numeric" required="yes" hint="I am the ID of related office tag">
		<cfargument name="testimonialDate" type="string" required="yes" hint="Date of event or date testimonial was received. Defaults to today.">
		<cfargument name="testimonialEmphasis" type="string" required="yes" hint="Passage of testimonial to highlight or emphasize">
		<cfargument name="testimonialText" type="string" required="yes" hint="Full text of testimonial">
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user adding the testimonial">
		
		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.qry_getTestimonialByID = ''>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.validateClientStruct = structNew()>
		<cfset local.validateTestimonialStruct = structNew()>
		
		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["testimonialID"] = 0>
		
		<cfset local.qry_getTestimonialByID = getTestimonialByID(testimonialID = arguments.testimonialID)>
		
		<cfif local.qry_getTestimonialByID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		
		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForTestimonials(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		
		<cfset arguments.testimonialClient = replacenocase(arguments.testimonialClient,'Enter Client Name Here','','all')>
		<cfset arguments.testimonialClient = left(trim(arguments.testimonialClient),100)>
		
		<!---validate client name--->
		<cfset local.validateClientStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.testimonialClient, stringName = 'the client name')>
		<cfif local.validateClientStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateClientStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		
		<cfset arguments.testimonialText = trim(arguments.testimonialText)>
		<!---clean the body of all but p tags--->
		<cfset arguments.testimonialText = variables.adminUtils.tagStripper(str = arguments.testimonialText,action = "strip",tagList = "br")>
		
		<!---validate testimonial body--->
		<cfset local.validateTestimonialStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.testimonialText, stringName = 'the testimonial text')>
		<cfif local.validateTestimonialStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateTestimonialStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		
		<cfset arguments.testimonialCity = left(trim(arguments.testimonialCity),50)>
		<cfset arguments.testimonialStateabb = returnValidStateAbb(stateAbb = arguments.testimonialStateabb)>
		<cfset arguments.testimonialPracticeAreaTagID = returnValidPracticeareaTagID(tagID = arguments.testimonialPracticeAreaTagID)>
		<cfset arguments.testimonialAttorney1TagID = returnValidAttorneyTagID(tagID = arguments.testimonialAttorney1TagID)>
		<cfset arguments.testimonialAttorney2TagID = returnValidAttorneyTagID(tagID = arguments.testimonialAttorney2TagID)>
		<cfset arguments.testimonialOfficeTagID = returnValidOfficeTagID(tagID = arguments.testimonialOfficeTagID)>
		<cfset arguments.testimonialDate = returnValidDate(testimonialDate = arguments.testimonialDate)>
		<!---strip all tags from the emphasis section--->
		<cfset arguments.testimonialEmphasis = variables.adminUtils.tagStripper(str = arguments.testimonialEmphasis,action = "strip")>
		<cfset arguments.testimonialEmphasis = left(trim(arguments.testimonialEmphasis),250)>
		
		<cfquery>
		UPDATE plugin_testimonials
		SET testimonialClient = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.testimonialClient#">,
		testimonialCity = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.testimonialCity#">,
		testimonialStateabb = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.testimonialStateabb#">,
		testimonialPracticeAreaTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.testimonialPracticeAreaTagID#">,
		testimonialAttorney1TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.testimonialAttorney1TagID#">,
		testimonialAttorney2TagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.testimonialAttorney2TagID#">,
		testimonialOfficeTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.testimonialOfficeTagID#">,
		testimonialEmphasis = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.testimonialEmphasis#">,
		testimonialText = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.testimonialText#">,
		testimonialDate = <cfqueryparam cfsqltype="cf_sql_timestamp" value="#dateformat(arguments.testimonialDate,'MM/DD/YYYY')# 00:00:00">,
		testimonialUserID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">
		WHERE testimonialID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.testimonialID#">
		</cfquery>
		
		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The testimonial was updated successfully.')>
		<cfset local.status["testimonialID"] = arguments.testimonialID>
		
		<cfreturn local.status>
	</cffunction>
	<!---END update testimonial--->
	
	<!---DELETE testimonial (disable)--->
	<cffunction name="testimonialDelete" displayname="Delete Testimonial" description="Delete's a testimonial" access="remote" output="false" returntype="struct">
		
		<cfargument name="testimonialID" type="numeric" required="yes" hint="ID of testimonial being removed">
		<cfargument name="userID" type="numeric" required="yes" hint="ID of the user deleting the testimonial">
		
		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getTestimonialByID = ''>
		
		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		
		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForTestimonials(userID = arguments.userID, checkDelete = 1)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		
		<!---validate testimonial--->
		<cfset local.qry_getTestimonialByID = getTestimonialByID(testimonialID = arguments.testimonialID)>
		
		<cfif local.qry_getTestimonialByID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>
		
		<cfquery>
		UPDATE plugin_testimonials
		SET testimonialStatus = <cfqueryparam cfsqltype="cf_sql_integer" value="0">,
		testimonialUserID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">
		WHERE testimonialID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.testimonialID#">
		</cfquery>
		
		<cfset local.status["success"] = 1>
        <cfset local.status["message"] =  variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The testimonial has been deleted successfully.')>	
		
		<cfreturn local.status>
	</cffunction>
	<!---END DELETE--->
	
</cfcomponent>
