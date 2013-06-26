---
layout: page
title: PHP, Testing, Quality and other things I care about in tech
tagline: 
---
{% include JB/setup %}

<div style="font-weight: bold">Please note that I'm currently migrating all my blog posts and the list below is still missing over a year worth of posts :)</div> 

<ul class="posts">
  {% for post in site.posts %}
    <li><span>{{ post.date | date_to_string }}</span> &raquo; <a href="{{ BASE_PATH }}{{ post.url }}">{{ post.title }}</a></li>
  {% endfor %}
</ul>
