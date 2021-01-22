<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" indent="no"/>
  <xsl:template match="/">
      <html>
       <head>
         <title>Dernières avalanches sur data-avalanche.org </title>
         <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
         <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
			<meta name="viewport" content="initial-scale=1"/>
         <link rel="stylesheet" type="text/css" href="ava.css"/>
       </head>
       <body>
	  			<xsl:apply-templates/>
        </body>
     </html>
  </xsl:template>
	
	<xsl:template match="*">
		<div>
			<xsl:attribute name="class"><xsl:value-of select="translate(name(.),':','')"/></xsl:attribute>
		  	<xsl:apply-templates select="@href"/>
		  	<xsl:apply-templates/>
		</div>
	</xsl:template>
	
	<xsl:template match="text()">
	  		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="@href">
	  		<a target='_data-aval'>
	  			<xsl:attribute name="href"><xsl:value-of select="."/></xsl:attribute>
	  			<xsl:value-of select="."/>
	  		</a>
	</xsl:template>
</xsl:stylesheet>

