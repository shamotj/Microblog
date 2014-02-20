Microblog
=========

Small PHP library for managing blog posts in file based format. Each post is in its own file with specified format. I put this together to be able to manage blog without use of database.

<pre>
YYMMDD_[post_title].[type]
</pre>

Where:
  * **YYMMDD** is date of post creation (or any other date).
  * **post_title** can be any name supported by filesystem. So be careful for chars like ! or ? etc.
  * **type** says in which format we should expect file content. It may be generally any supported format. For beggining there is support for **.html**, **.md** ad **.texy**.

 
