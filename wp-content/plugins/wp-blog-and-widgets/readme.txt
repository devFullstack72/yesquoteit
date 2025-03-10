=== WP Blog and Widgets ===
Contributors:  wponlinesupport, anoopranawat, pratik-jain, piyushpatel123, patelketan
Tags: wordpress blog, custom blog template, custom blog layout, Free WordPress blog, blog page with custom post type,
Requires at least: 4.0
Tested up to: 6.7.1
Stable tag: 2.6.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add a Blog custom post type, Blog widget to WordPress. Also, work with the Gutenberg shortcode block.

== Description ==

✅ Now that you have your website ready then why don’t you **download** and try out this Blog and widget plugin to give it better functionality.

> **Note:** WP Blog and Widget plugin is created with custom post type. If you are looking for a plugin that works with your WordPress existing  Post, please use our plugin [Blog Designer – Post and Widget](https://wordpress.org/plugins/blog-designer-for-post-and-widget/).

By **downloading** our WordPress blog and widget plugin on your website, you are in a way giving a very professional touch to it. It’s a way to blog your company’s standard and position. It not only has a blog but you can also design and customize it with a brief description as well. Download and try this blog and widget plugin which comes with many other features.

[FREE DEMO](https://demo.essentialplugin.com/blog-demo/?utm_source=WP&utm_medium=Blog-Widget&utm_campaign=Read-Me) | [PRO DEMO](https://demo.essentialplugin.com/prodemo/pro-blog-and-widgets-plugin-demo/?utm_source=WP&utm_medium=Blog-Widget&utm_campaign=Read-Me)

Your customer might like the professional and fancy vibe of your site with blog and widget.

**✅ This plugin displays your blog posts using :**

* Blog (1 designs)
* Recent Blog (1 designs)
* Blog Widget (1 designs)

**Download Now** it today and explore all the features.

When you want to makeover your WordPress website theme with something extraordinary and creative, you must consider the blog and widget plugin.

WP Blog and widgets, manage and display blog, date archives, widget on your website. You can display the latest blog post on your homepage/front page as well as on the inner page.

The plugin work with shortcode so you can easily display the blog anywhere on your site.

**Also added Gutenberg block support.**

= ✅ Here is the plugin shortcode example =

**Blog**

<code>[blog]</code>

**Recent Blog**

<code>[recent_blog_post]</code>

**To display only slick 4 post:**

<code>[blog limit="4"]</code>
Where limit define the number of posts to display. You can use same parameter with recent blog shortcode.

**If you want to display Slider Slider by category then use this short code:** 

<code>[blog category="category_ID"]</code>
Enter category id to display categories wise.

**Complete shortcode example:**

<code>[blog limit="10" category="category_id" grid="2" show_author="false" show_content="true" show_full_content="true" show_category_name="true" show_date="false" content_words_limit="30" ]</code>

= ✅ Here is Template code =
<code><?php echo do_shortcode('[blog]'); ?> </code>
<code><?php echo do_shortcode('[recent_blog_post]'); ?> </code>

= ✅ Use Following Blog parameters with shortcode =
<code>[blog]</code>

* **limit** : [blog limit="10"] (Display latest 10 blog post and then pagination).
* **category** : [blog category="category_id"] (Display Blog post categories wise).
* **grid** : [blog grid="2"] (Display blog in Grid formats. To display blog in list view please do not use 'grid' parameter. ).
* **show_date** : [blog show_date="false"] (Display Blog date OR not. By default value is "True". Options are "ture OR false")
* **show_category_name** : [blog show_category_name="true" ] (Display blog post category name OR not. By default value is "True". Options are "ture OR false").
* **show_author** : [blog show_author="false"] (Display Blog author OR not. By default value is "True". Options are "ture OR false")
* **show_content** : [blog show_content="true" ] (Display blog post Short content OR not. By default value is "True". Options are "ture OR false").
* **show_full_content** : [blog show_full_content="true"] (Display Full Blog post content on main page if you do not want word limit. By default value is "false". Note : This parameter will not work with [recent_blog_post] shortcode.)
* **content_words_limit** : [blog content_words_limit="30" ] (Control blog post short content Words limit. By default limit is 20 words).
* **order** : [blog order="DESC"] (Blog order ie DESC or ASC).
* **orderby** : [blog orderby="date"] (Order by Blog ie date, ID, author, title, modified and rand etc).
* **pagination_type:** [blog pagination_type="numeric"] (Select the pagination type for News ie "numeric" OR "next-prev". Note : This parameter will not work with [recent_blog_post] shortcode. ).
* **extra_class:** [blog extra_class=""] (Enter extra CSS class for design customization).

= ✅ Use Following Recent Blog parameters with shortcode =
<code>[recent_blog_post]</code>

* **limit** : [recent_blog_post limit="10"] (Display latest 10 blog post and then pagination).
* **category** : [recent_blog_post category="category_id"] (Display Blog post categories wise).
* **grid** : [recent_blog_post grid="2"] (Display blog in Grid formats. To display blog in list view please do not use 'grid' parameter. ).
* **show_date** : [recent_blog_post show_date="false"] (Display Blog date OR not. By default value is "True". Options are "ture OR false")
* **show_author** : [recent_blog_post show_author="false"] (Display Blog author OR not. By default value is "True". Options are "ture OR false")
* **show_content** : [recent_blog_post show_content="true" ] (Display blog post Short content OR not. By default value is "True". Options are "ture OR false").
* **show_full_content** : [recent_blog_post show_full_content="true"] (Display Full Blog post content on main page if you do not want word limit. By default value is "false".)
* **show_category_name** : [recent_blog_post show_category_name="true" ] (Display blog post category name OR not. By default value is "True". Options are "ture OR false").
* **content_words_limit** : [recent_blog_post content_words_limit="30" ] (Control blog post short content Words limit. By default limit is 20 words).
* **order** : [recent_blog_post order="DESC"] (Blog order ie DESC or ASC).
* **orderby** : [recent_blog_post orderby="date"] (Order by Blog ie date, ID, author, title, modified and rand etc).
* **extra_class** : [recent_blog_post extra_class=""] (Enter extra CSS class for design customization).

If you are getting any kind of problem with the Blog page means you are not able to see all blog posts then please remodify your permalinks Structure for example 
first, select "Default" and save then again select "Custom Structure "  and save. 

Finally, the plugin adds a Recent Blog Post widget which can be placed on any sidebar available in your theme. You can set the title of this list and the number of blog posts to show.

✅ **Checkout demo for better understanding**

[FREE DEMO](https://demo.essentialplugin.com/blog-demo/?utm_source=WP&utm_medium=Blog-Widget&utm_campaign=Read-Me) | [PRO DEMO](https://demo.essentialplugin.com/prodemo/pro-blog-and-widgets-plugin-demo/?utm_source=WP&utm_medium=Blog-Widget&utm_campaign=Read-Me)

✅ **Essential Plugin Bundle Deal**

[Annual or Lifetime Bundle Deal](https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Blog-Widget&utm_campaign=Read-Me)

= ✅ Features include: =
* Added Gutenberg block support.
* Blog
* Recent Blog
* Easy to add.
* Smoothly integrates into any theme.
* Yearly, Monthly and Daily archives.
* You can create multiple post slider with different options at single page or post.
* Added Widget Options like Show Blog date, Show Blog Categories, Select Blog Categories.
* Added language for German, French (France) (Beta)
* Fully responsive. Scales with its container.
* 100% Multi Language.

== Frequently Asked Questions ==

= How to enable/disable Gutenberg editor support for Blog Posts? =

Just add this code in your theme function.php file to enable/disable Gutenberg editor support for  Blog Posts :

<code>
function prefix_gutenberg_editor_support($wpbaw_blog_args){
 $wpbaw_blog_args['show_in_rest'] = false; 
  return $wpbaw_blog_args;  
}
add_filter( 'wpbaw_blog_registered_post_type_args', 'prefix_gutenberg_editor_support' );
</code>

= How to install: =
[youtube https://www.youtube.com/watch?v=hxamkKf-80U]

= Privacy & Policy =
* We have also opt-in e-mail selection, once you download the plugin so that we can inform you and nurture you about products and its features.

== Installation ==

1. Upload the 'wp-blog-and-widgets' folder to the '/wp-content/plugins/' directory.
2. Activate the WP Blog and widgets plugin through the 'Plugins' menu in WordPress.
3. Add and manage Blog Post on your site by clicking on the  'Blog' tab that appears in your admin menu.
4. Create a page with the any name and paste this shortcode  <code> [blog] </code>.

= How to install: =
[youtube https://www.youtube.com/watch?v=hxamkKf-80U]

== Screenshots ==
1. Display Blog Posts with a grid-1 view.
2. Display Recent Blog Posts.
3. Display Blog Posts with List view.
4. Display Blog Posts with grid-3 view
5. Display Blog Posts with grid-2 view
6. Blog admin view

== Changelog ==

= 2.6.3 (17, January 2025) =
* [*] Check compatibility with WordPress version 6.7.1

= 2.6.2 (06, Nov 2024) =
* [*] Check compatibility with WordPress version 6.6.2

= 2.6.1 (25, July 2024) =
* [*] Check compatibility with WordPress version 6.6.1

= 2.6 (24, Nov 2023) =
* [*] Updated analytics SDK.
* [*] Check compatibility with WordPress version 6.4.1

= 2.5 (21, Aug 2023) =
* [*] Tested up to: 6.3

= 2.4.2 (02, Aug 2023) =
* [*] Tested up to: 6.2.2
* [*] Fixed all security related issues.

= 2.4.1 (18, May 2023) =
* [*] Tested up to: 6.2.1

= 2.4 (28, March 2023) =
* [*] Fixed - Fixed one undefined PHP variable warning.
* [*] Update - Improve escaping functions for better security.

= 2.3.1 (05, Jan 2023) =
* [*] Update - Update some escaping functions for better security.

= 2.3 (04, Jan 2023) =
* [*] Update - Use escaping functions for better security.
* [*] Remove - Removed unnecessary files and images.
* [*] Remove - Removed .po files from 'languages' folder.
* [*] Tweak - Code optimization and performance improvements.

= 2.2.6 (28, March 2022) =
* [+] Added demo link
* [-] Removed some unwanted code and files.

= 2.2.5 (11, Feb 2022) =
* [-] Removed some unwanted code and files.

= 2.2.4 (04, Feb 2022) =
* [*] Tested up to: 5.9 
* [*] Solved Gutenberg wp-editor widget issue.

= 2.2.3.1 (15, Dec 2021) =
* [*] Minor fix. 

= 2.2.3 (12, Nov 2021) =
* [*] Fix - Resolve Gutenberg WP-Editor script related issue. 
* [*] Update - Add some text and links in Readme file.
* [*] Minor change in CSS.

= 2.2.2 (16, Sep 2021) =
* [*] Tested up to: 5.8.1
* [*] Updated demo link

= 2.2.1 (19, Aug 2021) =
* [*] Updated language file and json.

= 2.2 (18, Aug 2021) =
* [*] Updated all external links
* [*] Tweak - Code optimization and performance improvements.
* [*] Fixed - Blocks Initializer Issue.

= 2.1.1 (31, May 2021) =
* [*] Tested up to: 5.7.2
* [*] Added - https link in our analytics code to avoid browser security warning.

= 2.1 (24, May 2021) =
* [*] Tested up to: 5.7.2
* [*] Tweak - Code optimization and performance improvements.

= 2.0 (23, Oct 2020) =
* [*] Update - Regular plugin maintenance. Updated readme file.
* [+] New - Click to copy the shortcode from the getting started page.
* [*] Added - Added our other Popular Plugins under blog and widget --> Install Popular Plugins From Us. This will help you to save your time during creating a website.

= 1.9 (28, August 2020) =
* [+] New - Added Gutenberg block support. Now use plugin easily with Gutenberg!
* [+] New - Added 'align' and 'extra_class' parameter for all shortcode. Now both slider shortcode is support twenty-nineteen and twenty-twenty theme Gutenberg block align and additional class feature.
* [+] New - Added 'show_full_content' parameter for recent post shortcode.
* [*] Tweak - Code optimization and performance improvements.
* [*] Template File - Main design file has been updated. If you have an override template file then verify with the latest copy.

= 1.8.2 (14, July 2020) =
* [*] Follow WordPress Detailed Plugin Guidelines for Offload Media and Analytics Code.

= 1.8.1 (28, Dec 2019) =
* [+] Added : Added 2 new parameter - order and orderby
* [*] Replaced wp_reset_query(); with wp_reset_postdata(); 
* [*] Improve some code in the shortcode file.

= 1.7 (05, June 2019) =
* [+] Added author support for the blog post type. After updating the plugin, if you are not able to see the author on the front-end side, please click on the Blog menu and assign the author for all blog posts by clicking on the Quick Edit link.

= 1.6.1 (09, Feb 2019) =
* [*] Minor change in Opt-in flow.

= 1.6 (20, Dec 2018) =
* [*] Fixed the issue where custom taxonomies were not showing after giving Gutenberg support for a custom blog post. Thanks, @sabkor for showing us this issue.

= 1.5.1 (18, Dec 2018) =
* [*] Update Opt-in flow.

= 1.5 (13-12-2018) =
* [+] Added `apply_filters` to update the blog custom post type arguments.
* [+] If you are using WordPress 5.0 OR WordPress 5.0 plus classic editor plugin, we have added `show_in_rest=> true` to enable the Gutenberg Editor support for Blog Posts or Not.

= 1.4 (08-12-2018) =
* [*] Tested with WordPress 5.0 and Gutenberg.

= 1.3.2 (05, June 2018) =
* [*] Follow some WordPress Detailed Plugin Guidelines.

= 1.3.1 (20/07/2017) =
* [+] Fixed grid issue showing 15px gap in left and right

= 1.3 (15/02/2017) =
* [+] Added new shortcode parameter "pagination_type" (Select the pagination type for blog ie "numeric" OR "next-prev" ).

= 1.2.8 =
* [+] Added "How it Works" tab under "Blog"
* [-] Removed "Pro Design" tab
* [+] Added some design to Read More button and Blog Title
* Fixed widget image display issue.

= 1.2.7 =
* Fixed Image display issue.
* Fixed CSS issue.
* Fixed widget image display issue.

= 1.2.6 =
* Added excerpt functionality in the post description.
* Resolved display post content issue.

= 1.2.5 =
* Updated PRO plugin design page.
* Added German (Switzerland), Spanish (Spain), French (Canada), Italian, and Dutch Beta translation.

= 1.2.4 =
* Fixed some CSS issues.

= 1.2.3 =
* Added Added language for German, French (France) (Beta)
* Added 2 new designs for the pro version
* Fixed some bug 

= 1.2.2 =
* Fixed some bug and added an option for pro designs

= 1.2.1 =
* Added new shortcode parameters show_author.
* Added Image option in the widget
* Fixed some bugs.

= 1.2 =
* Added new shortcode parameters show_date.
* Fixed some bugs.

= 1.1 =

* Added Widget Options like Show Blog date, Show Blog Categories, Select Blog Categories.
* Fixed some bugs

= 1.0 =
* Initial release.