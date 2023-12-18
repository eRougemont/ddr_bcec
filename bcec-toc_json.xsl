<?xml version="1.0" encoding="utf-8"?>
<!-- 
Piaget, normalisation of docx, strip things from the tei step
-->
<xsl:transform version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:html="http://www.w3.org/1999/xhtml"
  exclude-result-prefixes="html"
  >
  <xsl:output indent="no" encoding="UTF-8" method="text"/>
  <xsl:template match="/">
    <root>
    <xsl:text>{
</xsl:text>
    <xsl:for-each select="/html:html/html:body/html:div/html:aside/html:article/html:section">
      <xsl:if test="position() != 1">,
</xsl:if>
      <xsl:apply-templates select="."/>
    </xsl:for-each>
    <xsl:text>}
</xsl:text>
    </root>
  </xsl:template>
  
  <xsl:template match="html:section">
    <xsl:text>  "</xsl:text>
    <xsl:value-of select="substring-before(@id, '_toc')"/>
    <xsl:text>": [
</xsl:text>
    <xsl:apply-templates select="html:ul"/>
    <xsl:text>]
</xsl:text>
  </xsl:template>
  

  <xsl:template match="html:ul">
    <xsl:for-each select="html:li">
      <xsl:if test="position() != 1">,</xsl:if>
      <xsl:apply-templates select="."/>
    </xsl:for-each>
  </xsl:template>
  
  <xsl:template match="html:li">
    <xsl:variable name="indent">
      <xsl:text>&#10;</xsl:text>
      <xsl:value-of select="substring('                              ', 1 , 4 + count(ancestor-or-self::html:li) * 2)"/>
    </xsl:variable>
    <xsl:variable name="id" select="substring-before(ancestor::html:section/@id, '_toc')"/>
    <xsl:variable name="n">
      <xsl:number count="html:li" level="multiple"/>
    </xsl:variable>
    <xsl:variable name="label">
      <xsl:choose>
        <xsl:when test="html:label">
          <xsl:copy-of select="html:label/node()"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:copy-of select="node()"/>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:value-of select="substring($indent, 1, string-length($indent) - 2)"/>
    <xsl:text>{</xsl:text>
    <xsl:value-of select="$indent"/>
    <xsl:text>"type": "range",</xsl:text>
    <xsl:value-of select="$indent"/>
    <xsl:text>"id": "https://www.unige.ch/rougemont/bcec/</xsl:text>
    <xsl:value-of select="$id"/>
    <xsl:text>/</xsl:text>
    <xsl:value-of select="$n"/>
    <xsl:text>",</xsl:text>
    <xsl:value-of select="$indent"/>
    <xsl:text>"label": {"none": ["</xsl:text>
    <xsl:value-of select="$label"/>
    <xsl:text>"]},</xsl:text>
    <xsl:value-of select="$indent"/>
    <xsl:text>"items": [</xsl:text>
    <xsl:apply-templates select="html:ul"/>
    <xsl:value-of select="$indent"/>
    <xsl:text>]</xsl:text>
    <xsl:value-of select="substring($indent, 1, string-length($indent) - 2)"/>
    <xsl:text>}</xsl:text>
  </xsl:template>
  
</xsl:transform>