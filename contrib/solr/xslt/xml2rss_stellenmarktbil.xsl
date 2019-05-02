<?xml version='1.0' encoding='UTF-8'?>

<!-- 
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->

<!-- 
  Simple transform of Solr query results to RSS
 -->

<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:georss="http://www.georss.org/georss">

  <xsl:output
       method="xml"
       encoding="utf-8"
       media-type="text/xml; charset=UTF-8"
  />
  <xsl:template match='/'>
    <rss version="2.0">
      <channel>
        <title>www.stellenmarkt-bildung.de - RSS 2.0 Feed</title>
        <link>http://www.stellenmarkt-bildung.de/</link>
        <description>RSS Feed der www.stellenmarkt-bildung.net Job-Angebote.</description>
        <language>de-de</language>
        <xsl:apply-templates select="response/result/doc"/>
      </channel>
    </rss>
  </xsl:template>
  
  <xsl:template match="doc">
    <xsl:variable name="id" select="str[@name='id']"/>
    <xsl:variable name="title" select="str[@name='title']"/>
    <xsl:variable name="company" select="str[@name='companyName']"/>
    <xsl:variable name="subTitle" select="html[@name='subTitle']"/>
    <xsl:variable name="timestamp" select="date[@name='timestamp']"/>
    <xsl:variable name="day" select="substring($timestamp, 9, 2)"/>
    <xsl:variable name="month" select="substring($timestamp, 6, 2)"/>
    <xsl:variable name="monthName">
        <xsl:if test="$month = '01'">Jan</xsl:if>
        <xsl:if test="$month = '02'">Feb</xsl:if>
        <xsl:if test="$month = '03'">Mar</xsl:if>
        <xsl:if test="$month = '04'">Apr</xsl:if>
        <xsl:if test="$month = '05'">May</xsl:if>
        <xsl:if test="$month = '06'">Jun</xsl:if>
        <xsl:if test="$month = '07'">Jul</xsl:if>
        <xsl:if test="$month = '08'">Aug</xsl:if>
        <xsl:if test="$month = '09'">Sep</xsl:if>
        <xsl:if test="$month = '10'">Oct</xsl:if>
        <xsl:if test="$month = '11'">Nov</xsl:if>
        <xsl:if test="$month = '12'">Dec</xsl:if>
    </xsl:variable>
    <xsl:variable name="year" select="substring($timestamp, 1, 4)"/>
    <xsl:variable name="time" select="substring($timestamp, 12, 8)"/>
    <xsl:variable name="lonLat" select="str[@name='lonLat']"/>
    <xsl:variable name="lon" select="substring-before($lonLat, ',')"/>
    <xsl:variable name="lat" select="substring-after($lonLat, ',')"/>
    <item>
        <title><xsl:value-of select="$title"/></title>
        <link>http://www.stellenmarkt-bildung.de/Job.<xsl:value-of select="$id"/>.1959.0.html</link>
        <guid>http://www.stellenmarkt-bildung.de/Job.<xsl:value-of select="$id"/>.1959.0.html</guid>
        <description>bei <xsl:value-of select="$company"/></description>
        <pubDate><xsl:value-of select="concat($day, ' ', $monthName, ' ', $year, ' ', $time, ' GMT')"/></pubDate>
        <georss:point><xsl:value-of select="concat($lon, ' ', $lat)"/></georss:point>
    </item>
  </xsl:template>
  
</xsl:stylesheet>
