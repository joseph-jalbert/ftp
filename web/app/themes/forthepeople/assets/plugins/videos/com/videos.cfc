<!---CMS Videos Plugin Components--->

<cfcomponent displayname="Videos Plugin Components" name="videos" output="false" extends="plugins.videos.application">

	<!--- call init() automatically when the CFC is instantiated --->
  	<cfset init(application.adminUtils)>

	<cffunction name="init" access="public" output="false" returntype="videos">

		<cfargument name="adminUtils" type="component" required="no" default="#application.adminUtils#">

		<!--- Putting the variable in the variables scope makes it available to the init() method as well as all other methods in the CFC--->
		<cfset variables.adminUtils = arguments.adminUtils>

		<cfreturn this />
	</cffunction>

	<cffunction name="resetTags" displayname="Reset Tags" description="I am a required function that can be called to clear all links to a tag that is being deleted" access="remote" output="false" returntype="void">

		<cfargument name="tagID" type="numeric" required="yes" hint="I am a tag that is about to be deleted">

		<!---if this is a practice area tag, then all videos using it should be set to the default PA--->
		<cfquery>
		UPDATE plugin_videos
		SET videoPracticeAreaTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE videoPracticeAreaTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

		<!---if this is an attorney tag, then all videos using it should be set to the default (no attorney)--->
		<cfquery>
		UPDATE plugin_videos
		SET videoAttorneyTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="0">
		WHERE videoAttorneyTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.tagID#">
		</cfquery>

	</cffunction>

	<cffunction name="validateUserForVideos" displayname="Validate User for Videos" description="Validates a user for accessing the videos plugin" access="private" output="false" returntype="struct">

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
			<cfset local.status["message"] = 'The user does not have sufficient privileges to access videos. The "content create" privilege must be added by a system administrator.'>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfif arguments.checkDelete EQ 1>

			<cfif local.qry_getUserbyID.permissions_contentdelete EQ 0>
				<cfset local.status["message"] = 'The user does not have sufficient privileges to access videos. The "content delete" privilege must be added by a system administrator.'>
				<cfreturn local.status>
				<cfabort>
			</cfif>

		</cfif>

		<!---user is valid, logged in, and has permission--->
		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = 'User validated successfully.'>

		<cfreturn local.status>
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

	<cffunction name="returnValidVideoType" displayname="Return Valid Video Type" description="Makes sure the video type is a valid number in the acceptable range" access="private" output="false" returntype="numeric">

		<cfargument name="videoType" type="numeric" required="yes">

		<cfif not isnumeric(arguments.videoType)>
			<cfset arguments.videoType = 0>
			<cfreturn arguments.videoType>
			<cfabort>
		</cfif>
		<cfif not isValid("range",arguments.videoType,0,3)>
			<cfset arguments.videoType = 0>
			<cfreturn arguments.videoType>
			<cfabort>
		</cfif>

		<cfreturn arguments.videoType>
	</cffunction>


	<cffunction name="getVideoByID" displayname="Get Video By ID" description="Gets all the information for a video, by ID" access="private" output="false" returntype="query">

		<cfargument name="videoID" type="numeric" required="yes" hint="ID of video">

		<cfset local.qry_getVideoByID = ''>

		<cfquery name="local.qry_getVideoByID">
		SELECT TOP 1 videoID, videoUUID, videoTitle, videoAttorneyTagID, videoPracticeAreaTagID, videoType, videoDesc, videoTranscript, videoDisplay, videoUploaded, videoUserID
		FROM plugin_videos
		WHERE videoID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoID#">
		</cfquery>

		<cfreturn local.qry_getVideoByID>
	</cffunction>

	<cffunction name="getPlaylistByID" displayname="Get Video Playlist By ID" description="Gets all the information for a video playlist, by ID" access="private" output="false" returntype="query">

		<cfargument name="videoPlaylistID" type="numeric" required="yes" hint="ID of the playlist">

		<cfset local.qry_getPlaylistByID = ''>

		<cfquery name="local.qry_getPlaylistByID">
		SELECT TOP 1 videoPlaylistID, videoPlaylistName, videoPlaylistDateAdded
		FROM plugin_videosPlaylist
		WHERE videoPlaylistID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPlaylistID#">
		</cfquery>

		<cfreturn local.qry_getPlaylistByID>
	</cffunction>


	<!---Add Video--->
	<cffunction name="videoAdd" displayname="Add Video" description="Validates then adds a new video" access="remote" output="false" returntype="struct">

		<!---arguments--->
		<cfargument name="videoTitle" type="string" required="yes" hint="Title of the video">
		<cfargument name="videoType" type="numeric" required="yes" hint="Indicates whether the video is legal, a commercial, news, etc.">
		<cfargument name="videoAttorneyTagID" type="numeric" required="yes" hint="I am the ID of related attorney tag">
		<cfargument name="videoPracticeAreaTagID" type="numeric" required="yes" hint="I am the ID of the related practice area tag">
		<cfargument name="videoDesc" type="string" required="yes" hint="Description of the video">
		<cfargument name="videoTranscript" type="string" required="yes" hint="Transcript of the video">
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user adding the video">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.validateTitleStruct = structNew()>
		<cfset local.qry_getTopVideo = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["videoID"] = 0>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForVideos(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.videoTitle = trim(arguments.videoTitle)>

		<!---validate title text--->
		<cfset local.validateTitleStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.videoTitle, stringName = 'the video title', maxLength = 150)>
		<cfif local.validateTitleStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateTitleStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.videoType = returnValidVideoType(videoType = arguments.videoType)>
		<cfset arguments.videoAttorneyTagID = returnValidAttorneyTagID(tagID = arguments.videoAttorneyTagID)>
		<cfset arguments.videoPracticeAreaTagID = returnValidPracticeareaTagID(tagID = arguments.videoPracticeAreaTagID)>

		<cfset arguments.videoDesc = left(trim(arguments.videoDesc),8000)>
		<!---clean the body of all but br,a,strong tags--->
		<cfset arguments.videoDesc = variables.adminUtils.tagStripper(str = arguments.videoDesc,action = "strip",tagList = "br,a,strong")>

		<cfset arguments.videoTranscript = left(trim(arguments.videoTranscript),8000)>
		<!---clean the transcript of all but br tags--->
		<cfset arguments.videoTranscript = variables.adminUtils.tagStripper(str = arguments.videoTranscript,action = "strip",tagList = "br")>
		<cfset arguments.videoTranscript = REReplace(arguments.videoTranscript,'"',"'",'ALL')>

		<cfquery>
		INSERT INTO plugin_videos(
		videoTitle,
		videoType,
		videoAttorneyTagID,
		videoPracticeAreaTagID,
		videoDesc,
		videoTranscript,
		videoUserID)
		VALUES(
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.videoTitle#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoType#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoAttorneyTagID#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPracticeAreaTagID#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.videoDesc#">,
		<cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.videoTranscript#">,
		<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">)
		</cfquery>

		<cfquery name="local.qry_getTopVideo">
		SELECT TOP 1 videoID
		FROM plugin_videos
		ORDER BY videoID DESC
		</cfquery>

		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The video was added successfully. You will now be redirected.')>
		<cfset local.status["videoID"] = local.qry_getTopVideo.videoID>

		<cfreturn local.status>
	</cffunction>
	<!---END add video--->


	<!---Update video--->
	<cffunction name="videosSet" displayname="Update Video" description="Validates then updates a video's information" access="remote" output="false" returntype="struct">

		<!---arguments--->
		<cfargument name="videoID" type="numeric" required="yes" hint="I am the ID of the video being updated">
		<cfargument name="videoTitle" type="string" required="yes" hint="Title of the video">
		<cfargument name="videoType" type="numeric" required="yes" hint="Indicates whether the video is legal, a commercial, news, etc.">
		<cfargument name="videoAttorneyTagID" type="numeric" required="yes" hint="I am the ID of related attorney tag">
		<cfargument name="videoPracticeAreaTagID" type="numeric" required="yes" hint="I am the ID of the related practice area tag">
		<cfargument name="videoDesc" type="string" required="yes" hint="Description of the video">
		<cfargument name="videoTranscript" type="string" required="yes" hint="Transcript of the video">
		<cfargument name="videoDisplay" type="numeric" required="no" default="0" hint="I am the toggle for whether the video should be shown or not">
		<cfargument name="videoPrimaryDisplayPageID" type="numeric" required="yes" hint="I am the pageID of the primary page which the video will be displayed">	
		<cfargument name="userID" type="numeric" required="yes" hint="I am the ID of the user updating the video">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.qry_getVideoByID = ''>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.validateTitleStruct = structNew()>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
		<cfset local.status["videoID"] = 0>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForVideos(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---validate videoID--->
		<cfset local.qry_getVideoByID = getVideoByID(videoID = arguments.videoID)>

		<cfif local.qry_getVideoByID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.videoTitle = trim(arguments.videoTitle)>

		<!---validate title text--->
		<cfset local.validateTitleStruct = variables.adminUtils.CheckNotBlank(stringToCheck = arguments.videoTitle, stringName = 'the video title', maxLength = 150)>
		<cfif local.validateTitleStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateTitleStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.videoType = returnValidVideoType(videoType = arguments.videoType)>
		<cfset arguments.videoAttorneyTagID = returnValidAttorneyTagID(tagID = arguments.videoAttorneyTagID)>
		<cfset arguments.videoPracticeAreaTagID = returnValidPracticeareaTagID(tagID = arguments.videoPracticeAreaTagID)>

		<cfset arguments.videoDesc = left(trim(arguments.videoDesc),8000)>
		<!---clean the body of all but br,a,strong tags--->
		<cfset arguments.videoDesc = variables.adminUtils.tagStripper(str = arguments.videoDesc,action = "strip",tagList = "br,a,strong")>

		<cfset arguments.videoTranscript = left(trim(arguments.videoTranscript),8000)>
		<!---clean the transcript of all but br tags--->
		<cfset arguments.videoTranscript = variables.adminUtils.tagStripper(str = arguments.videoTranscript,action = "strip",tagList = "br")>
		<cfset arguments.videoTranscript = REReplace(arguments.videoTranscript,'"',"'",'ALL')>

		<!---if the video is not uploaded, the video cannot be displayed--->
		<cfif local.qry_getVideoByID.videoUploaded EQ 0>
			<cfset arguments.videoDisplay = 0>
		</cfif>

		<cfif not isValid("range",arguments.videoDisplay,0,1)>
			<cfset arguments.videoDisplay = 0>
		</cfif>

		<cfif arguments.videoPrimaryDisplayPageID NEQ 0>

			<!---make sure its a valid page and valid status--->
			<cfquery name="checkDisplayPageID">
			SELECT TOP 1 pageID, pageStatus 
			FROM page 
			WHERE pageID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPrimaryDisplayPageID#">
			</cfquery>

			<!---if page not valid, return error--->
			<cfif checkDisplayPageID.recordcount NEQ 1 AND checkDisplayPageID.pageStatus NEQ 1>
				<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>
				<cfreturn local.status />
			</cfif>

		</cfif>

		<cfquery>
		UPDATE plugin_videos
		SET videoTitle = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.videoTitle#">,
		videoType = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoType#">,
		videoAttorneyTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoAttorneyTagID#">,
		videoPracticeAreaTagID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPracticeAreaTagID#">,
		videoDesc = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.videoDesc#">,
		videoTranscript = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.videoTranscript#">,
		videoDisplay = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoDisplay#">,
		videoUserID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">,
		videoPrimaryDisplayPageID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPrimaryDisplayPageID#">
		WHERE videoID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoID#">
		</cfquery>

		<cfset local.status["success"] = 1>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The video was updated successfully.')>
		<cfset local.status["videoID"] = arguments.videoID>

		<cfreturn local.status>
	</cffunction>
	<!---END update video--->

	<!---DELETE video FILE--->
	<cffunction name="videoDeleteFile" displayname="Delete Video Files" description="Deletes a video's files" access="remote" output="false" returntype="struct">

		<cfargument name="videoID" type="numeric" required="yes" hint="ID of video whose files are being removed">
		<cfargument name="userID" type="numeric" required="yes" hint="ID of the user deleting the video">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getVideoByID = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForVideos(userID = arguments.userID, checkDelete = 1)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---validate video--->
		<cfset local.qry_getVideoByID = getVideoByID(videoID = arguments.videoID)>

		<cfif local.qry_getVideoByID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cftry>
			<cfif FileExists('#this.FMScloudPath#/#qry_getVideoByID.videoUUID#.mp4')>
				<cffile action="delete" file="#this.FMScloudPath#/#qry_getVideoByID.videoUUID#.mp4" />
			</cfif>
			<cfif FileExists('#this.FMScloudPath#/#qry_getVideoByID.videoUUID#_screenshot.jpg')>
				<cffile action="delete" file="#this.FMScloudPath#/#qry_getVideoByID.videoUUID#_screenshot.jpg" />
			</cfif>
			<cfif FileExists('#this.FMScloudPath#/#qry_getVideoByID.videoUUID#_thumb.jpg')>
				<cffile action="delete" file="#this.FMScloudPath#/#qry_getVideoByID.videoUUID#_thumb.jpg" />
			</cfif>
			<cfcatch type="any">
				<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'An error occurred while deleting the files from AWS. Please refresh the page and try deleting the video again.')>
				<cfreturn local.status>
				<cfabort>
			</cfcatch>
		</cftry>

		<cfquery>
		UPDATE plugin_videos
		SET videoDisplay = <cfqueryparam cfsqltype="cf_sql_integer" value="0">,
		videoUploaded = <cfqueryparam cfsqltype="cf_sql_integer" value="0">,
		videoUserID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.userID#">
		WHERE videoID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoID#">
		</cfquery>

		<cfset local.status["success"] = 1>
        <cfset local.status["message"] =  variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The video file has been deleted successfully. The page will now refresh to reflect your changes.')>

		<cfreturn local.status>
	</cffunction>
	<!---END DELETE video file--->


	<!---DELETE video--->
	<cffunction name="videoDelete" displayname="Delete Video" description="Delete's a video" access="remote" output="false" returntype="struct">

		<cfargument name="videoID" type="numeric" required="yes" hint="ID of video being removed">
		<cfargument name="userID" type="numeric" required="yes" hint="ID of the user deleting the video">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getVideoByID = ''>
		<cfset local.deleteStruct = structNew()>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForVideos(userID = arguments.userID, checkDelete = 1)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---validate video--->
		<cfset local.qry_getVideoByID = getVideoByID(videoID = arguments.videoID)>

		<cfif local.qry_getVideoByID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---delete the files if they exist--->
		<cfif local.qry_getVideoByID.videoUploaded NEQ 0>
			<cfset local.deleteStruct = videoDeleteFile(videoID = arguments.videoID, userID = arguments.userID)>

			<cfif local.deleteStruct.success NEQ 1>
				<cfset local.status["message"] = local.deleteStruct.message>
				<cfreturn local.status>
				<cfabort>
			</cfif>
		</cfif>

		<!---delete records--->
		<cfquery>
		DELETE FROM plugin_videos
		WHERE videoID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoID#">
		</cfquery>

		<cfset local.status["success"] = 1>
        <cfset local.status["message"] =  variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The video has been deleted successfully.')>

		<cfreturn local.status>
	</cffunction>
	<!---END DELETE--->


	<!---Add New Video Playlist--->
	<cffunction name="addNewPlaylist" displayname="Add New Video Playlist" hint="I validate then add new video playlists" access="remote" output="false" returntype="struct">

		<cfargument name="userID" type="numeric" required="yes">
		<cfargument name="videoPlaylistName" type="string" required="yes" hint="I am the name for the new playlist">

		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_checkPlaylistName = ''>
		<cfset local.qry_getTopPlaylist = ''>
		<cfset local.videoPlaylistIdentifier = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["playlistID"] = -1>
		<cfset local.status["message"] = variables.adminUtils.returnModalStatusMessage()>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForVideos(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnModalStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.videoPlaylistName = left(trim(arguments.videoPlaylistName),100)>

		<!---name is blank--->
		<cfif arguments.videoPlaylistName EQ ''>
			<cfset local.status["message"] = variables.adminUtils.returnModalStatusMessage(messageText = 'Please enter a name for the playlist.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfquery name="local.qry_checkPlaylistName">
		SELECT TOP 1 videoPlaylistID
		FROM plugin_videosPlaylist
		WHERE videoPlaylistName = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.videoPlaylistName#">
		</cfquery>

		<!---the name is already used--->
		<cfif local.qry_checkPlaylistName.recordcount EQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnModalStatusMessage(messageText = 'The name you entered is already used by another playlist.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---set the sitemap identifier--->
		<cfset local.videoPlaylistIdentifier = replacenocase(lcase(arguments.videoPlaylistName)," ","_","ALL")>

		<!---all variables okay, run insert--->
		<cfquery>
		INSERT INTO plugin_videosPlaylist(videoPlaylistName,videoPlaylistIdentifier)
		VALUES( <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.videoPlaylistName#">,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#local.videoPlaylistIdentifier#">)
		</cfquery>

		<cfquery name="local.qry_getTopPlaylist">
		SELECT TOP 1 videoPlaylistID
		FROM plugin_videosPlaylist
		ORDER BY videoPlaylistID DESC
		</cfquery>

		<cfset status["success"] = 1>
		<cfset status["playlistID"] = local.qry_getTopPlaylist.videoPlaylistID>
		<cfset status["message"] = variables.adminUtils.returnModalStatusMessage(messageText = 'The new playlist has been created successfully. The page will now redirect you to the editor for the playlist.', messageTypeClass = 'success', messageTypeName = 'Success')>

		<cfreturn status>
	</cffunction>
	<!---end Add New Video Playlist--->


	<!---DELETE playlist--->
	<cffunction name="playlistDelete" displayname="Delete Playlist" description="Delete's a video playlist" access="remote" output="false" returntype="struct">

		<cfargument name="videoPlaylistID" type="numeric" required="yes" hint="ID of playlist being removed">
		<cfargument name="userID" type="numeric" required="yes" hint="ID of the user deleting the playlist">

		<!---var scope--->
		<cfset local.status = structNew()>
		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getPlaylistByID = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForVideos(userID = arguments.userID, checkDelete = 1)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---validate video--->
		<cfset local.qry_getPlaylistByID = getPlaylistByID(videoPlaylistID = arguments.videoPlaylistID)>

		<cfif local.qry_getPlaylistByID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---delete playlist--->
		<cfquery>
		DELETE FROM plugin_videosPlaylist
		WHERE videoPlaylistID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPlaylistID#">
		</cfquery>

		<cfset local.status["success"] = 1>
        <cfset local.status["message"] =  variables.adminUtils.returnStatusMessage(messageTypeClass = "success",messageIcon = "circle-check",messageTypeName = "Success!",messageText = 'The playlist has been deleted successfully.')>

		<cfreturn local.status>
	</cffunction>
	<!---END Delete Playlist--->


	<!---modify video playlist--->
	<cffunction name="modifyPlaylist" displayname="Modify Video Playlist" hint="I process updates to a video playlist" access="remote" output="false" returntype="struct">

		<cfargument name="userID" type="numeric" required="yes">
		<cfargument name="videoPlaylistID" type="numeric" required="yes" hint="I am the ID of the playlist being updated">
		<cfargument name="playlistArray" type="string" required="yes" hint="I am the playlist, structured as a JSON array. I need to be deserialized because I am being passed in as a string.">
		<cfargument name="videoPlaylistName" type="string" required="yes" hint="I am the name for the playlist">

		<cfset local.status = structNew()>

		<cfset local.validateUserStruct = structNew()>
		<cfset local.qry_getPlaylistByID = ''>
		<cfset local.qry_checkPlaylistName = ''>
		<cfset local.qry_getCurrentVideosByPlaylistID = ''>
		<cfset local.topLevelStrKey = ''>
		<cfset local.counter = 0>
		<cfset local.qry_getVideoByID = ''>
		<cfset local.qry_checkPlaylistForVideoByID = ''>

		<cfset local.status["success"] = 0>
		<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>

		<!---validate user--->
		<cfset local.validateUserStruct = validateUserForVideos(userID = arguments.userID)>
		<cfif local.validateUserStruct.success NEQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---validate playlist--->
		<cfset local.qry_getPlaylistByID = getPlaylistByID(videoPlaylistID = arguments.videoPlaylistID)>

		<cfif local.qry_getPlaylistByID.recordcount EQ 0>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfset arguments.videoPlaylistName = left(trim(arguments.videoPlaylistName),100)>

		<!---name is blank--->
		<cfif arguments.videoPlaylistName EQ ''>
			<cfset local.status["message"] = variables.adminUtils.returnModalStatusMessage(messageText = 'Please enter a name for the playlist.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<cfquery name="local.qry_checkPlaylistName">
		SELECT TOP 1 videoPlaylistID
		FROM plugin_videosPlaylist
		WHERE videoPlaylistName = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.videoPlaylistName#">
		AND videoPlaylistID <> <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPlaylistID#">
		</cfquery>

		<!---the name is already used--->
		<cfif local.qry_checkPlaylistName.recordcount EQ 1>
			<cfset local.status["message"] = variables.adminUtils.returnModalStatusMessage(messageText = 'The name you entered is already used by another playlist.')>
			<cfreturn local.status>
			<cfabort>
		</cfif>

		<!---check the nav json--->
		<cfif IsJSON(arguments.playlistArray) EQ 'FALSE'>
			<!---<cfset status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'json')>--->
			<cfreturn status>
			<cfabort>
		</cfif>

		<!---we know the json is valid, so we are going to deserialize it--->
		<cfset arguments.playlistArray = DeserializeJSON(arguments.playlistArray)>

		<cftransaction>

		<!---let's get the existing videos--->
		<cfquery name="local.qry_getCurrentVideosByPlaylistID">
		SELECT videoPlaylistID, videoID, videoOrder
		FROM plugin_videosLink
		WHERE videoPlaylistID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPlaylistID#">
		</cfquery>

		<!---now delete current links--->
		<cfquery>
		DELETE
		FROM plugin_videosLink
		WHERE videoPlaylistID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPlaylistID#">
		</cfquery>

		<!---ok, we know we have a valid user, playlist, name, and array, so lets try to start our update and loop--->
		<cftry>

			<cfquery>
			UPDATE plugin_videosPlaylist
			SET videoPlaylistName = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.videoPlaylistName#">
			WHERE videoPlaylistID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPlaylistID#">
			</cfquery>

			<!---there are videos for the playlist--->
			<cfif isArray(arguments.playlistArray) and not ArrayIsEmpty(arguments.playlistArray)>

				<!---loop over the top level playlist array (which contains structures for each video item--->
				<cfloop index="local.topLevelStrKey" array="#arguments.playlistArray#">

					<!---the struct here contains the videos--->
					<cfif not StructIsEmpty(local.topLevelStrKey)>

						<!---validate video--->
						<cfset local.qry_getVideoByID = getVideoByID(videoID = topLevelStrKey.videoID)>

						<!---video is valid--->
						<cfif local.qry_getVideoByID.recordcount EQ 1>

							<!---make sure it is not already on the playlist--->
							<cfquery name="local.qry_checkPlaylistForVideoByID">
							SELECT TOP 1 videoID
							FROM plugin_videosLink
							WHERE videoID = <cfqueryparam cfsqltype="cf_sql_integer" value="#topLevelStrKey.videoID#">
							AND videoPlaylistID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPlaylistID#">
							</cfquery>

							<!---video not already on playlist--->
							<cfif local.qry_checkPlaylistForVideoByID.recordcount EQ 0>

								<!---increment the counter--->
								<cfset local.counter = local.counter + 1>

								<!---insert link--->
								<cfquery>
								INSERT INTO plugin_videosLink(
								videoPlaylistID,
								videoID,
								videoOrder)
								VALUES(
								<cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPlaylistID#">,
								<cfqueryparam cfsqltype="cf_sql_integer" value="#topLevelStrKey.videoID#">,
								<cfqueryparam cfsqltype="cf_sql_integer" value="#local.counter#">)
								</cfquery>

							</cfif>
							<!---video not already on playlist--->

						</cfif>
						<!---valid video--->

					</cfif>
					<!---valid top level item struct--->

				</cfloop>

			</cfif>
			<!---there are videos--->

			<!---this will need to hold our nav restore, based on the initial queries we ran--->
			<cfcatch type="any">

				<cfset status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'An error occurred while processing your update to this playlist. Please refresh the page and try again.')>

				<!---restore the playlist--->
				<cfoutput query="local.qry_getCurrentVideosByPlaylistID">
					<cfquery>
					INSERT INTO plugin_videosLink(
					videoPlaylistID,
					videoID,
					videoOrder)
					VALUES(
					<cfqueryparam cfsqltype="cf_sql_integer" value="#videoPlaylistID#">,
					<cfqueryparam cfsqltype="cf_sql_integer" value="#videoID#">,
					<cfqueryparam cfsqltype="cf_sql_integer" value="#order#">)
					</cfquery>
				</cfoutput>

				<cfreturn status>
				<cfabort>
			</cfcatch>

		</cftry>

		</cftransaction>

		<cfset status["success"] = 1>
		<cfset status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Your update to this playlist has been processed successfully.', messageTypeClass = 'success', messageTypeName = 'Success')>

		<cfreturn status>
	</cffunction>

	<cffunction name="setVideoSiteMapSettings" displayname="Set Video Sitemap Settings" access="remote" output="false" returntype="struct">

			<cfargument name="videoPlaylistID" type="numeric" required="true">
			<cfargument name="userID" type="numeric" required="true">
			<cfargument name="videoPlaylistIdentifier" type="string" required="true">
			<cfargument name="videoPlaylistSiteMap" type="boolean" required="true">

			<cfset local.status = structNew()>

			<cfset local.validateUserStruct = structNew()>
			<cfset local.qry_getPlaylistByID = ''>

			<cfset local.status["success"] = 0>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage()>

			<!---validate user--->
			<cfset local.validateUserStruct = validateUserForVideos(userID = arguments.userID)>
			<cfif local.validateUserStruct.success NEQ 1>
				<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = local.validateUserStruct.message)>
				<cfreturn local.status />
			</cfif>

			<!---validate playlist--->
			<cfset local.qry_getPlaylistByID = getPlaylistByID(videoPlaylistID = arguments.videoPlaylistID)>

			<cfif local.qry_getPlaylistByID.recordcount EQ 0>
				<cfreturn local.status>
			</cfif>

			<!---trim identifier name--->
			<cfset arguments.videoPlaylistIdentifier = left(trim(arguments.videoPlaylistIdentifier),50)> 

			<!---name is blank but sitemap set to enable--->
			<cfif NOT len(arguments.videoPlaylistIdentifier) AND arguments.videoPlaylistSiteMap EQ true >
				<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = 'Please enter a name for the site map identifier.')>
				<cfreturn local.status />
			</cfif>

			<cfquery>
			UPDATE plugin_videosPlaylist
			SET videoPlaylistIdentifier = <cfqueryparam cfsqltype="cf_sql_varchar" value="#arguments.videoPlaylistIdentifier#">,
			videoPlaylistSiteMap = <cfqueryparam cfsqltype="cf_sql_bit" value="#arguments.videoPlaylistSiteMap#">
			WHERE videoPlaylistID = <cfqueryparam cfsqltype="cf_sql_integer" value="#arguments.videoPlaylistID#">
			</cfquery>

			<cfset local.status["success"] = 1>
			<cfset local.status["message"] = variables.adminUtils.returnStatusMessage(messageText = "Your update to this playlist's sitemap settings has been processed successfully.", messageTypeClass = 'success', messageTypeName = 'Success')>

		<cfreturn local.status />
	</cffunction>



</cfcomponent>
