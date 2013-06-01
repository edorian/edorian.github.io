---

layout: post
title: Enhanced MySql Administrator server stats
tags: [mysql]

---

If your using the [MySql GUI Tools](http://dev.mysql.com/downloads/gui-tools/) you might have noticed that the "Server stats" are somewhat lacking.

## Out of the box stats

![Default view first tab][normalOne]
![Default view second tab][normalTwo]

But since the tool supports customize all the graphs and adding new ones

## Enhanced view

![Enhanced view first tab][enhancedOne]
![Enhanced view second tab][enhancedTwo]

---------

If your not to firm with all the Stats it can spit out for you I'd suggest you read [http://hackmysql.com/mysqlreportguide](http://hackmysql.com/mysqlreportguide) for an overview. My aim was to recreate most of the value this reporting tool provides.

To get all those Stats you need to download the XML File and copy it into your `%APPDATA%/MySql/` folder or into your GuiTools folder if you use the portable Version. The file is already there so you shouldn't have a problem finding it.

[mysqladmin_health.xml][XML]


[normalOne]: /assets/posts/2010-03-29/default-look-1.png
[normalTwo]: /assets/posts/2010-03-29/default-look-2.png
[enhancedOne]: /assets/posts/2010-03-29/enhanced-look-1.png
[enhancedTwo]: /assets/posts/2010-03-29/enhanced-look-2.png
[XML]: /assets/posts/2010-03-29/enhanced-look-2.png
