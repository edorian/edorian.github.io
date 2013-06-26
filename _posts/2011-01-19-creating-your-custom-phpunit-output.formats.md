---

layout: post
title: Creating your custom PHPUnit output formats
tags: [php, phpqatools, phpunit]

---

While tackling with someones question i decided it's time to play around with XSLT for learning purposes and i found something useful to do.<p />He wanted to extend the --testdox-html output and i proposed to just transform PHPUnits XML output using an XSLT since i didn't see a easy way to prove a custom implementation for `PHPUnit/Util/TestDox/ResultPrinter/HTML.php` and i didn't want to change the file its self.

So let's play around a bit.

### A simple test:

{:.prettyprint .linenums .lang-php}
	<?php
	class DemoTest extends PHPUnit_Framework_TestCase {
	
	  public function testPass() {
	    $this->assertTrue(true);
	  }
	
	  public function testFail() {
	    $this->assertTrue(false);
	  } 
	}

and generate some XML, for that test i used the jUnit output:

	phpunit --log-junit foo.xml DemoTest.php

and write a simple XSLT. It might not be the best way to do that but like i said: I'm just looked for something to learn about XSLT so correct my if I'm wrong.

{:.prettyprint .linenums .lang-xml}
	<?xml version="1.0"?>
	<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
	  <html>
	  <body>
	    <h1>Tests</h1>
	    <xsl:for-each select="testsuites/testsuite">
	      <h2><xsl:value-of select="@name"/></h2>
	      <ul>
	        <xsl:for-each select="testcase">
	          <li>
	            <xsl:value-of select="@name"/>
	            <xsl:if test="failure">
	              <b>Failed !</b>
	              <i><xsl:value-of select="*"/></i>
	            </xsl:if>
	          </li>
	        </xsl:for-each>
	      </ul>
	    </xsl:for-each>
	  </body>
	  </html>
	</xsl:template>
	</xsl:stylesheet>
>
and using that with the XML output using a Linux command line tool:

	xsltproc foo.xsl foo.xml > output.html

and that produces a nice litte HTML files:

	<h1>Tests</h1>
	<h2>DemoTest</h2>
	<ul>
	<li>testPass</li>
	  <li>testFail<b>Failed !</b></code></div>
	    <i>DemoTest::testFail
	  Failed asserting that <boolean:false> is true.
	  /home/edo/DemoTest.php:10
	  </i>
	</li>
	</ul>


P.S:

To make at least something a litte more useful out of that blog post:

I'm planing about writing about use cases for phpab and on how to ship your PHP_CodeSniffer coding standard with your project. If you have further suggestions I'd like to hear them.
