<cfcomponent extends="RootApplication">

	<!--- call init() automatically when the CFC is instantiated --->
  <cfset init()>

  <cfinclude template="../../config/plugins/videos/config.cfm">

	<cffunction name="init" access="public" output="false" returntype="application">
		<cfreturn this />
	</cffunction>

</cfcomponent>