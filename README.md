# nmc_marketing

We have shown consent layer as soon as the login page opens. For telecom login and admin login consent layer shown . We are getting some errors in browser console regarding content security which blocking functionality. Cookies not getting set after consent layer acceptance .
To solve this issue NMC Marketing app developed which will add csp rules


### Enabling APP

From settings app search for NMC marketing app and enable it.

### Config Changes

Add below in server config

'trusted_font_urls'=>array(0 => 'https://ebs10.telekom.de/opt-in/',),
'trusted_image_urls'=>array(0 => 'https://pix.telekom.de/',1=>'http://fbc.wcfbc.net/',)


### App Repository 
https://github.com/nextmcloud/nmc_marketing/tree/nmcfeat/master


### TestCases

> ![Testcases](tests/CSPListnerTest.php)

Execute test case steps:

From server directory Execute below command

./phpunit --configuration tests/phpunit-autotest.xml apps/nmc_marketing/tests/CSPListenerTest.php


Php unit install steps(https://phpunit.de/getting-started/phpunit-9.html):-
Note:- Execute all commands at server level

1.wget -O phpunit https://phar.phpunit.de/phpunit-9.phar

2.chmod +x phpunit

Check php unit version:- 

./phpunit --version
